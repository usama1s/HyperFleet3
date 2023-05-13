@php
use Illuminate\Support\Str;
@endphp
@extends('auth.driver.layouts.app')

@section('title', 'Pending Bookings')

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


<table id="pending_bookings" style="width:100% background:#fff;" class="display table responsive">
  <thead>
    <tr>

      <th>Pickup Point</th>
      <th>Pickup Date</th>
      <th>Pickup Time</th>
      <th>Drop Off / Duration</th>
      <th>Customer Name</th>
      <th>Customer Contact</th>
      <th>Adults</th>
      <th>Bags</th>
      <th>Action</th>

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
          @if ($booking->no_of_adults)
            {{ $booking->no_of_adults }}
            @else 
              NA
          @endif
      </td>
      <td>

        @if ($booking->no_of_bags)
            {{ $booking->no_of_bags }}
        @else 
          NA
        @endif
      </td>

      <td>
        <form action="{{route('driver.bookingaction')}}" method="POST">
          @csrf
          @method('post')
          <input type="hidden" name="booking_id" value="{{$booking->id}}">
          <span> 
            <input class="btn btn-sm btn-success" type="submit" name="accept" value="accept">
            <input class="btn btn-sm btn-danger" type="submit" name="reject" value="reject">
          </span>
         
        </form>
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
    var table = $('#pending_bookings').DataTable();

    let userID = document.head.querySelector('meta[name="user-id"]').content;

    Echo.private('App.User.' + userID)
      .notification((notification) => {
        url = "{{route('driver.bookingaction')}}";
        if (notification.booking_id.duration == null) {
          notification.booking_id.duration = ""
        }

        if (notification.booking_id.drop_off == null) {
          notification.booking_id.drop_off = ""
        }
        table.row.add([
          notification.booking_id.pickup_point,
          notification.booking_id.pickup_date,
          notification.booking_id.pickup_time,
          notification.booking_id.drop_off + notification.booking_id.duration,
          notification.booking_id.contact_no,
          notification.booking_id.no_of_adults,
          notification.booking_id.no_of_bags,
          '<form action="' + url +
          '" method="POST">@csrf @method("post")<input  type="hidden" name="booking_id" value="' +
          notification.booking_id.id +
          '"><input class="btn btn-link text-success" type="submit" name="accept" value="accept"><input class="btn btn-link text-danger" type="submit" name="reject" value="reject"></form>'
        ]).draw(false);
      });

  });
</script>

@endsection