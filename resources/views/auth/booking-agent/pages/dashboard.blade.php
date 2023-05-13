@extends('auth.booking-agent.layouts.app')

@section('title', 'Booking Agent Dashboard')

@section('breadcrumb')


@endsection  

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h4>Active Bookings</h4>
          <p>&nbsp;</p>
        </div>
        <div class="icon">
          <i class="fas fa-book-open"></i>
        </div>
        <a href="{{url('my-booking-agent/booking')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h4>Completed Bookings</h4>
          <p>&nbsp;</p>
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
          <h4>Cancelled Bookings</h4>
          <p>&nbsp;</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="{{url('driver/canceledbookings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
 

     
  </div>
@endsection

