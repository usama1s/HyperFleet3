@php
use Illuminate\Support\Str;
@endphp

@extends('auth.driver.layouts.app')

@section('title', 'Active Bookings')

@section('breadcrumb')

    <div class="page_sub_menu">
        <a href="{{url('/driver/pendingbookings')}}" class="btn btn-sm btn-info">Pending Bookings</a>
        <a href="{{url('/driver/todaybookings')}}" class="btn btn-sm btn-warning">Today Bookings</a>
        <a href="{{url('/driver/acceptedbookings')}}" class="btn btn-sm btn-info">Active Bookings</a>
        <a href="{{url('/driver/completedbookings')}}" class="btn btn-sm btn-success">Completed Bookings</a>
        <a href="{{url('/driver/canceledbookings')}}" class="btn btn-sm btn-danger">Canceled Bookings</a>
    </div>

@endsection  



@section('content')

      
    <table id="all_bookings" style="width:100% background:#fff;" class="display table responsive">
      <thead>
          <tr>
            
              <th>Pickup Point</th>        
              <th>Pickup Date</th>
              <th>Pickup Time</th>
              <th>Drop Off / Duration</th>   
              <th>Customer Name</th>    
              <th>Customer Contact</th>           
              <th>Status</th>
              <th>Actions</th>
           
              <!-- <th>Status</th> -->
          </tr>
      </thead>
      <tbody>
     

        @foreach ($bookings as $booking)

        @php

        if ($booking->pickup_date < date("Y-m-d")){
          $danger = 'bg-danger text-white';
          $is_active = false;
        }
        else{
          $is_active= true;
          $danger = "";
        }
        
        @endphp
      @if ($booking->pickup_date < date("Y-m-d"))
          
      @endif
      <tr class="{{$danger}}">
          <td>{{ $booking->pickup_point }}</td>      
          <td>{{ $booking->pickup_date }}</td>
          <td>{{ $booking->pickup_time }}</td>
          <td>
            {{ $booking->drop_off }}
            @if(!is_null($booking->duration))
            {{ $booking->duration}} {{ Str::plural('hour',$booking->duration )}}
            @endif
          </td>
          <td>{{ $booking->first_name }} {{ $booking->last_name }}</td>
          <td>{{ $booking->contact_no }}</td>

          <td>
            @if ($booking->status == 'accepted')
                  <p class="text-info">accepted</p> 
                @elseif($booking->status == 'active')
                  <p class="text-info">Ride Started</p>
                @elseif($booking->status == 'client')
                  <p class="text-info">Client Picked Up</p>
                @else
                  <p>Unknown</p>
            @endif
          </td>
        <td>
          @if ($is_active)
            @if ($booking->status=="accepted")
            <a class="btn btn-sm btn-info" href="{{url("driver/startride/$booking->id")}}">Start ride</a> 

            @elseif ($booking->status=="active")
            <a class="btn btn-sm btn-info" href="{{url("driver/pickupclient/$booking->id")}}">Picked Up</a> 
            
            <a class="btn btn-sm btn-danger" class="text-danger" href="{{url("driver/no-show/$booking->id")}}">No Show</a>
            
            @elseif($booking->status=="client")  
            <a class="btn btn-sm btn-success" href="{{url("driver/finishride/$booking->id")}}" booking_id="{{$booking->id}}" id="finishBtn">Finish Ride </a> 
            @else  
                <p>unknown action</p>
            @endif 

            @else 
                <p>Expired</p>
          @endif
           
        </td> 
        </tr>
              
        @endforeach

      </tbody>
    </table>


    {{-- Collect Payment from Cliet Modal --}}

    <!-- Modal -->
    <div class="modal fade" id="PaymentCollectFromClient" tabindex="-1" role="dialog" aria-labelledby="PaymentCollectFromClientLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="PaymentCollectFromClientLabel">Payment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <h1 class="text-center">Your Balance is  {{Config('currency-symbol')}}<span id="booking_balance">00</span></h1>
            <div id="amount_div">
              <form id="rideCompleteForm" action="" method="get">

                <label for="">Amount</label>
                <input type="number" name="amount" id="amount" placeholder="Amount" class="form-control">
              
              </form>
              
            </div>

            <div id="payment_div">
              
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="collect_payment_btn" class="btn btn-primary">Complete Ride</button>
          </div>
        </div>
      </div>
    </div>
      
@endsection

@section('js')

    <script>

        var sno = 1;
        var booking_data = null;
        $(document).ready(function() {
          $('#all_bookings').DataTable();
          


          $("#finishBtn").click(function(event){
            event.preventDefault();

            var button = $(this) 
            console.log(button)
            button.text('loading..');
            var booking_id = button.attr('booking_id');
            var url =  button.attr('href');
           $("#booking_balance").empty().text('00.00');
           $("#payment_div").empty().hide();
           $("#amount_div").hide();

           $.ajax({
             url:"{{url('api/bookings')}}/"+booking_id,
             method:"POST",
             success: function(result){
              booking_data = result;
              
              button.text('Finish Ride');
              $("#booking_balance").text(result.booking.grand_price);
              $("#rideCompleteForm").attr("action",url);
              if(result.booking.payment_method == 'cash'){
                $("#booking_balance").text(result.booking.grand_price);
                $("#amount_div").show();
              }else{
                $("#amount").val(result.booking.grand_price);
                $("#payment_div").text("Paid by "+result.booking.payment_method).show();
              
                
              }
              $('#PaymentCollectFromClient').modal('show');
             }
           });

           

          });

          // collect_payment_btn

          $("#collect_payment_btn").click(function(){
            var amount =  parseInt($("#amount").val());

           if(isNaN(amount)){
             alert("please enter amount");
             return false;
           }
           
           if(amount < booking_data.booking.grand_price){
            alert("please enter full amount");
             return false;
           }

           $("#rideCompleteForm").submit();
          });

      
              
        });
    </script>

 @endsection