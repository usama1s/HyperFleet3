@php
use App\Models\Shift;
use App\Models\Driver;
 $shift = new Shift;
  $driver = Driver::where('user_id',Auth::user()->id)->first();

  $shift_start = $shift->getStart($driver->shift_id);
  $shift_end = $shift->getEnd($driver->shift_id);


  

  // $t1 =  new DateTime( date("g:i a", strtotime($shift_start)));
  // $t2 =  new DateTime();


  // $diff = $t1->diff($t2);

  // $working_hours = $diff->format("%H : %I");


  $end =  date("F d,Y H:i", strtotime($shift_end));


@endphp
@extends('auth.driver.layouts.app')

@section('title', 'Driver Dashboard')

@section('breadcrumb')

<div class="float-right d-print-inline-flex" style="font-size: xx-large;">
 {{ $shift_start }} - {{ $shift_end }}
 <br>

   <span id="driver_shift_ends" style="font-size: x-large;">6:20:2</span>

   <script>

     var end = "{{ $end }}:00";
     
    end = new Date(end);

    diff_min(end);

    function diff_min(dt2) 
    {

      var x = setInterval(function() {
        

      var now = new Date().getTime();
      var distance =(dt2.getTime() - now) / 1000;

  
     
  
      var hours = Math.floor((distance % ( 60 * 60 * 24)) / ( 60 * 60));
      var minutes = Math.floor((distance % ( 60 * 60)) / ( 60));
      var seconds = Math.floor((distance % ( 60)) );

     if(seconds >0){
        $("#driver_shift_ends").html('<b>Shift Ends In:</b>'+" "+ hours +": "+minutes+": "+seconds+"" );
     }
      else{
        $("#driver_shift_ends").html("<b>Shift timing is over</b>");
      }

      

      

      if (distance < 0) {
        clearInterval(x);

      }

      },1000);
   
      
    }

   </script>
 
</div>
@endsection  

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>150</h3>

          <p>Today Bookings</p>
        </div>
        <div class="icon">
          <i class="fas fa-book-open"></i>
        </div>
        <a href="{{url('driver/todaybookings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>53<sup style="font-size: 20px">%</sup></h3>

          <p>Complete Bookings</p>
        </div>
        <div class="icon">
          <i class="fas fa-male"></i>
        </div>
        <a href="{{url('driver/completedbookings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>44</h3>

          <p>Cancelled Bookings</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="{{url('driver/canceledbookings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>1</h3>

          <p>Vehicle</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
      <a href="{{url('driver/vehicle')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->

     
  </div>
@endsection

