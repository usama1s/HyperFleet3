@php
use Illuminate\Support\Str;
@endphp
@extends('auth.driver.layouts.app')

@section('title', 'Today Bookings')

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


<table id="today_bookings" style="width:100% background:#fff;" class="display table">
  <thead>
    <tr>

      <th>Pickup Point</th>
      <th>Pickup Date</th>
      <th>Pickup Time</th>
      <th>Drop Off / Duration</th>
      <th>Customer Name</th>
      <th>Customer Contact</th>
      <th>status</th>


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
      <td>{{ $booking->first_name }} {{ $booking->last_name }}</td>
      <td>{{ $booking->contact_no }}</td>
     
      <td>
        @if ($booking->status == 'accepted')
              <p class="text-info">accepted</p> 
            @elseif($booking->status == 'active')
              <p class="text-succcess">Ride Started</p>
            @elseif($booking->status == 'client')
              <p class="text-info">Client Picked Up</p>
            @elseif($booking->status == 'noshow')
              <p class="text-warning">Client no show</p>
            @else
              <p>Unknown</p>
        @endif
      </td>

    </tr>

    @endforeach

  </tbody>
</table>

@endsection

@section('js')

    <script>

        var sno = 1;
        $(document).ready(function() {
    $('#today_bookings').DataTable();
        
});
    </script>

 @endsection