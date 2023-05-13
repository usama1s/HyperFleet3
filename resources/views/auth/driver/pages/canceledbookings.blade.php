@php
use Illuminate\Support\Str;
@endphp

@extends('auth.driver.layouts.app')

@section('title', 'Canceled Bookings')

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

      
    <table id="all_bookings" style="width:100% background:#fff;" class="display table">
      <thead>
          <tr>
            
              <th>Pickup Point</th>        
              <th>Pickup Date</th>
              <th>Pickup Time</th>
              <th>Drop Off / Duration</th>
                        
              <th>Canceled at</th>
                   
              <!-- <th>Status</th> -->
          </tr>
      </thead>
      <tbody>
        @foreach ($bookings as $booking)
        <tr>
          <td>{{ $booking->pickup_point }}</td>      
          <td>{{ $booking->pickup_date }}</td>
          <td>{{ $booking->pickup_time }}</td>
          <td>
            {{ $booking->drop_off }}
            @if(!is_null($booking->duration))
            {{ $booking->duration}} {{ Str::plural('hour',$booking->duration )}}
            @endif
          </td>
      

          <td>{{ date("d-m-Y g:i a", strtotime($booking->updated_at)) }}</td>

        </tr>
              
        @endforeach

      </tbody>
    </table>
  
@endsection

@section('js')

    <script>

        var sno = 1;
        $(document).ready(function() {
    $('#all_bookings').DataTable();
        
});
    </script>

 @endsection