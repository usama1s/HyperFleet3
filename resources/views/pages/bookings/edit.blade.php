@php
    use Illuminate\Support\Str;
    use App\Models\VehicleClass;
@endphp
@extends('layouts.app')

@section('title', 'Edit Booking')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
<li class="breadcrumb-item"><a href="{{ url('/bookings') }}">Bookings </a></li>
    <li class="breadcrumb-item active">Edit</li>
    
  </ol>

  @endsection  

@section('content')

{{-- ROW ONE Vehicle General Information --}}
<div class="container">
<div class="row">
    <div class="col-md-12">
        <div class="center-block">
          <!-- SELECT2 EXAMPLE -->
          <div class="mb-2">
            <button class="btn btn-info" id="p2p-btn">Point-to-Point</button>
            <button class="btn btn-info" id="hourly-btn">Hourly Booking</button>
          </div>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <form action="{{route('bookings.update',$booking->id)}}" method="POST">
                @method('put')
                @csrf
                <input type="hidden" id="booking_type" name="booking_type" value="point-2-point">
                <div class="card card-default">
                    <div class="card-header">
                      <h3 class="card-title">Booking Information</h3>
          
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                      </div>
                    </div>
                    <div class="card-body">
                <div class="row vehicle_row odd">
                    <div class="form-group col-md-6">
                        <label>Pickup Point</label>
                        <input  class="form-control" type="text" name="pickup_point"  value="{{ $booking->pickup_point }}" id="pickup_point">
                        @error('pickup_point')
                        <div class="invalid-feedback" style="display:block;" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <div id="pointtopoint">
                            <label>Drop off</label>
                            <input  class="form-control" type="text" name="drop_off"  value="{{$booking->drop_off }}" id="drop_off">
                            @error('drop_off')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div id="hourlybooking">
                            <label>Duration</label>
                            <select name="hourlybooking" id="hours" class="form-control">
                                
                                <option value="">Select Hours</option>                

                                @for ($i = 1; $i <= 24; $i++)                                                            
                                    @if ($booking->duration == $i)
                                    <option value="{{$i}}" selected>{{$i}} {{ Str::plural('hour',$i )}}   </option>
                                    @else
                                    <option value="{{$i}}">{{$i}} {{ Str::plural('hour',$i )}}   </option>
                                    @endif  
                                @endfor
                                
                            </select>
                            @error('hourlybooking')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row vehicle_row even">
                        <div class="form-group col-md-3">
                            <label>Pickup Date</label>
                            <input  class="form-control datetimepicker-input" type="text" name="pickup_date"  id="pickup_date" data-toggle="datetimepicker" data-target="#pickup_date" autocomplete="off">
                            @error('pickup_date')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                
               
                    <div class="form-group col-md-3">
                        <label>Pickup Time</label>
                        <input  class="form-control datetimepicker-input" type="text" name="pickup_time" id="pickup_time" data-toggle="datetimepicker" data-target="#pickup_time" required autocomplete="off">
                        @error('pickup_time')
                        <div class="invalid-feedback" style="display:block;" role="alert">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
            

                    <div class="form-group col-md-3">
                        <label>No. of Adults</label>
                        <input  class="form-control" type="number" name="no_of_adults"  value="{{ $booking->no_of_adults }}" id="No. of Adults">
                    
                    </div>

                    <div class="form-group col-md-3">
                        <label>No. of Bags</label>
                        <input  class="form-control" type="number" name="no_of_bags"  value="{{ $booking->no_of_bags }}" id="no_of_bags">
                     
                    </div>
                </div>
                
                    <div class="row vehicle_row odd">
                    
                        <div class="form-group col-md-6">
                            <label>Vehicle Class</label>
                            <select class="form-control vehicle_class_id" name="vehicle_class" id="vehicle_class_id" class="height:50px">
                                <option value="">Select Vehicle Class</option>
                                  @foreach ( VehicleClass::all() as $item)                                  
                                    @if ($booking->v_class == $item->id)
                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                    @else
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endif                                 
                                  @endforeach                                                               
                            </select>
                              @error('vehicle_class')
                              <div class="invalid-feedback" style="display:block;" role="alert">
                                  {{ $message }}
                              </div>
                              @enderror
                        </div>     

                        <div class="form-group col-md-3">
                            <label>Price</label>
                            <input  class="form-control" type="number" name="price"  value="{{ $booking->grand_price }}" id="price">
                            @error('price')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div> 
                        
                        <div class="form-group col-md-3">
                            <label>Voucher Code(Optional)</label>
                            <input  class="form-control" type="text" name="voucher_code"  value="{{ $booking->voucher_code }}" id="voucher_code">
                            @error('voucher_code')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                                         
                    </div>
                    
                  </div>
                </div>
                <div class="card card-default">
                    <div class="card-header">
                      <h3 class="card-title">Customer Information</h3>
          
                      <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                      </div>
                    </div>
                    <div class="card-body">
                        <div class="row vehicle_row even">
                            <div class="form-group col-md-3">
                                <label>First Name</label>
                                <input  class="form-control" type="text" name="first_name"  value="{{ $booking->first_name }}" id="first_name">
                                @error('first_name')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
    
                            <div class="form-group col-md-3">
                                <label>Last Name</label>
                                <input  class="form-control" type="text" name="last_name"  value="{{ $booking->last_name }}" id="email">
                                @error('last_name')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-3">
                                <label>Contact no</label>
                                <input  class="form-control" type="text" name="contact_no"  value="{{ $booking->contact_no }}" id="contact_no">
                                @error('contact_no')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label>Email</label>
                                <input  class="form-control" type="text" name="email" id="email" value="{{ $booking->email }}">
                                @error('email')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row vehicle_row odd">

                            <div class="form-group col-md-4">
                                <label>Flight Number</label>
                                <input  class="form-control" type="text" name="flightnumber"  value="{{ $booking->flightnumber }}" id="flightnumber" >
                                @error('flightnumber')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-4">
                                <label>Pickup Sign</label>
                                <input  class="form-control" type="text" name="pickup_sign"  value="{{ $booking->pickup_sign }}" id="pickup_sign" >
                                @error('pickup_sign')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                         
                            <div class="form-group col-md-4">
                                <label>Special Instructions</label>
                                <input  class="form-control" type="text" name="special_instructions"  value="{{ $booking->special_instructions }}" id="special_instructions">
                                @error('special_instructions')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                
                        

                        <div class="row vehicle_row odd">
                            <div class="col-md-12">
                              <div class="d-flex justify-content-end" style="margin: 50px 20px;">
                                  <input class="btn btn-lg btn-success" type="submit" value="Save" style="width:200px">
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
               </form>
            </div>
            
          </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('js')
<script>

    $(function(){
        $("#vehicle_class_id").select2();
        $("#hours").select2();
        $("#p2p-btn").addClass('active');
        $('#hourlybooking').hide();

        $("#p2p-btn").click(function(){
            point2point();
        });

        $("#hourly-btn").click(function(){
            hourly();
        });

        var booking_type = "{{$booking->type}}";

        if(booking_type == "point-2-point"){
            point2point();
        }

        if(booking_type == "hourly"){
            hourly();
        }

        $('#pickup_date').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: '{{$booking->pickup_date}}'
      });      
      $('#expiry_date').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: '{{ $booking->expiry_date }}'
      }); 

      var pickup_time = "{{ $booking->pickup_time }}";
      pickup_time = pickup_time.split(':');
      $('#pickup_time').datetimepicker({
        format: 'LT',
        defaultDate:  moment({
                  hour :pickup_time[0], minute :pickup_time[1]
          })
        });
      
    
    });

    function point2point(){
            $("#hourly-btn").removeClass('active')
            $('#hourlybooking').hide();
            $('#hours').val('');
            $('#hours').trigger('change');
            $('#pointtopoint').show();
            $("#p2p-btn").addClass('active');
            $("#booking_type").val("point-2-point");
    }

    function hourly(){
            $("#p2p-btn").removeClass('active')
            $('#hourlybooking').show();
            $('#pointtopoint').hide();
            $('#drop_off').val('');
            $("#hourly-btn").addClass('active');
            $("#booking_type").val("hourly");
    }

    var pickup_point = places({
    appId: "pl85FXT44F74",
    apiKey: "322ee8a2ecb5c80593e382020d8f9342",
    container: document.querySelector('#pickup_point')
  });

  var drop_off = places({
    appId: "pl85FXT44F74",
    apiKey: "322ee8a2ecb5c80593e382020d8f9342",
    container: document.querySelector('#drop_off')
  });
</script>
@endsection
