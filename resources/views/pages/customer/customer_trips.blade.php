@php
    use Illuminate\Support\Str;
@endphp

@extends('pages.customer.layouts.app')

@section('content')
    
<div class="container">
  <nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <a class="nav-item nav-link active" id="nav-upcoming-tab" data-toggle="tab" href="#nav-upcoming" role="tab" aria-controls="nav-upcoming" aria-selected="true">Upcoming</a>
      <a class="nav-item nav-link" id="nav-past-tab" data-toggle="tab" href="#nav-past" role="tab" aria-controls="nav-past" aria-selected="false">Past</a>
      {{-- <a class="nav-item nav-link" id="nav-unconfirmed-tab" data-toggle="tab" href="#nav-unconfirmed" role="tab" aria-controls="nav-unconfirmed" aria-selected="false">Unconfirmed</a> --}}
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-upcoming" role="tabpanel" aria-labelledby="nav-upcoming-tab">
      <table style="width:100% background:#fff;" class="table">
          <thead>
            <tr>
        
              <th>Pickup Point</th>
              <th>Pickup Date/Time</th>
              <th>Drop Off / Duration</th>
              <th>Adults</th>
              <th>Bags</th>
        
            </tr>
          </thead>
          <tbody>
            @foreach ($upcoming_bookings as $booking)
            <tr>
              <td>{{ $booking->pickup_point }}</td>
              
              <td><strong> {{date("D, d M Y",strtotime($booking->pickup_date))}} - {{ date("h:ia",strtotime($booking->pickup_time)) }} </strong></td>
        
              <td>
                {{ $booking->drop_off }}
                @if(!is_null($booking->duration))
                {{ $booking->duration}} {{ Str::plural('hour',$booking->duration )}}
                @endif
              </td>
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
        
            </tr>
        
            @endforeach
        
          </tbody>
      </table>
  </div>
  <div class="tab-pane fade" id="nav-past" role="tabpanel" aria-labelledby="nav-past-tab">
    <table style="width:100% background:#fff;" class="table">
      <thead>
        <tr>
    
          <th>Pickup Point</th>
          <th>Pickup Date/Time</th>
          <th>Drop Off / Duration</th>
          <th>Adults</th>
          <th>Bags</th>

    
        </tr>
      </thead>
      <tbody>
        @foreach ($past_bookings as $booking)
        <tr>
          <td>{{ $booking->pickup_point }}</td>
          <td>{{ $booking->pickup_date }} {{ $booking->pickup_time }}</td>

          <td>
            {{ $booking->drop_off }}
            @if(!is_null($booking->duration))
            {{ $booking->duration}} {{ Str::plural('hour',$booking->duration )}}
            @endif
          </td>
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
    
        </tr>
    
        @endforeach
    
      </tbody>
  </table>
  </div>
  {{-- <div class="tab-pane fade" id="nav-unconfirmed" role="tabpanel" aria-labelledby="nav-unconfirmed-tab">

    <h1>Unconfirmed</h1>
  </div> --}}
</div>
</div>
@endsection
