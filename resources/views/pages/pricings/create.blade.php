@extends('layouts.app')

@section('title', 'Add New Pricing')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
  <li class="breadcrumb-item"><a href="{{url('pricings')}}">Pricings </a></li>
<li class="breadcrumb-item active">Create</li>

</ol>

@endsection

@section('content')
<div class="container">

  @php
     
  if (isset( $_GET['booking_agent']) &&  $_GET['booking_agent'] != "") {
    $target_url =  route('pricings.store')."?booking_agent=".$_GET['booking_agent'];
  }else if (isset( $_GET['supplier_id']) &&  $_GET['supplier_id'] != "") {
    $target_url =  route('pricings.store')."?supplier_id=".$_GET['supplier_id'];
  }
  else{
    $target_url =  route('pricings.store');
  }
  @endphp

  <form method="POST" action="{{ $target_url }}" enctype="multipart/form-data">
    @csrf
            <input type="hidden" name="pickup_latitude" id="pickup_latitude" value="0" />
            <input type="hidden" name="pickup_longitude" id="pickup_longitude" value="0" />

    <div class="row ">
      <div class="col-md-12">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Add Pricing Scheme</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
             
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
<!--               <h5>BASE</h5> -->
                <div class="col-md-5">
                <div class="form-group">
                  <label>Name</label>
                  <input class="form-control @error('pricing_name') is-invalid @enderror" type="text" name="pricing_name" value="{{ old('pricing_name') }}"
                    id="pricing_name" placeholder="Pricing Name" required>

                  @error('pricing_name')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
                    <div class="form-group">
                        <label>Base Point</label>
                        <input class="form-control @error('pickup_point') is-invalid @enderror" type="text"
                            name="pickup_point"  value="{{ old('pickup_point') }}" id="pickup_point" required>

                        @error('pickup_point')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div><input class="form-control @error('dropoff_point') is-invalid @enderror" type="text"
                            name="dropoff_point" placeholder="Dropoff Location" value="{{ old('dropoff_point') }}" id="dropoff_point" hidden>
                    <div class="form-group">
                        <label>Pickup Radius</label>
                        <input class="form-control @error('pickup_radius') is-invalid @enderror" type="number"
                            name="pickup_radius" value="{{ old('pickup_radius') ?? '0.5' }}" step=".1" id="pickup_radius" required>

                        @error('pickup_radius')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>    
                    
            </div>
 <div style="padding:8px;min-height:120px;visibility:hidden;" class="col-md-7" id="map"></div>
            </div>
            <div class="row" style="padding:8px;width:100%;min-height:24px;">&nbsp;</div>
                
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>City Transfer Price, up to 10.0 km fixed price </label>
                  <input class="form-control @error('upto_ten') is-invalid @enderror" type="text" name="upto_ten" value="{{ old('upto_ten') }}"
                    id="upto_ten" placeholder="0.0" required>

                  @error('upto_ten')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Short Transfer Price, 10.0 km - 100.0 km per km</label>
                  <input class="form-control @error('ten_to_hundred') is-invalid @enderror" type="text" name="ten_to_hundred" value="{{ old('ten_to_hundred') }}"
                    id="ten_to_hundred" placeholder="0.0" required>

                  @error('ten_to_hundred')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              </div>

            <div class="row">              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Medium Transfer Price, 100.0 km - 200.0 km per km</label>
                  <input class="form-control @error('hundred_to_twoHundred') is-invalid @enderror" type="text" name="hundred_to_twoHundred" value="{{ old('hundred_to_twoHundred') }}"
                    id="hundred_to_twoHundred" placeholder="0.0" required>

                  @error('hundred_to_twoHundred')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Long Transfer Price, 200.0 km or more per km</label>
                    <input class="form-control @error('twoHundred_and_above') is-invalid @enderror" type="text" name="twoHundred_and_above" value="{{ old('twoHundred_and_above') }}"
                      id="twoHundred_and_above" placeholder="0.0" required>

                    @error('twoHundred_and_above')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                      <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                  </div>
              </div>
              
            </div>

            <br>

            <div class="row">
              <h5>TIME</h5>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Time-based price per hour</label>
                  <input class="form-control @error('price_per_hour') is-invalid @enderror" type="text" name="price_per_hour" value="{{ old('price_per_hour') }}"
                    id="price_per_hour" placeholder="0.0" required>

                  @error('price_per_hour')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Time-based price per day (8 hours)</label>
                  <input class="form-control @error('price_per_day') is-invalid @enderror" type="text" name="price_per_day" value="{{ old('price_per_day') }}"
                    id="price_per_day" placeholder="0.0" required>

                  @error('price_per_day')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                  <label>Minimum Hours</label>
                  <select class="form-control minimum_hours" name="minimum_hours" id="minimum_hours"
                    class="height:50px" required>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                  </select>

                  @error('minimum_hours')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>              

            </div><br>
            <!-- /.col -->

            <div class="row">
              <h5>EXTRA</h5>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Additional pickup fee per pickup</label>
                  <input class="form-control @error('pickup_fee_per_pickup') is-invalid @enderror" type="text" name="pickup_fee_per_pickup" value="{{ old('pickup_fee_per_pickup') }}"
                    id="pickup_fee_per_pickup" placeholder="0.0" required>

                  @error('pickup_fee_per_pickup')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Additional waiting time per minute</label>
                  <input class="form-control @error('waiting_time_per_min') is-invalid @enderror" type="text" name="waiting_time_per_min" value="{{ old('waiting_time_per_min') }}"
                    id="waiting_time_per_min" placeholder="0.0" required >

                  @error('waiting_time_per_min')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="form-group">
                  <label>Airport pickup fee</label>
                  <input class="form-control @error('airport_pickup_fee') is-invalid @enderror" type="text" name="airport_pickup_fee" value="{{ old('airport_pickup_fee') }}"
                    id="airport_pickup_fee" placeholder="0.0" required>

                  @error('airport_pickup_fee')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>              

            </div><br>

            <div class="row">
              <h5>DISCOUNTS</h5>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Connecting job discount (in %)</label>
                  <input class="form-control @error('job_discount') is-invalid @enderror" type="text" name="job_discount" value="{{ old('job_discount') }}"
                    id="job_discount" placeholder="0.0" required>

                  @error('job_discount')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
            </div>
                      
            <div class="row">
              <div class="col-md-12">
                <div class="d-flex justify-content-end" style="margin: 50px 20px;">
                  <input class="btn btn-lg btn-success" type="submit" value="Save" style="width:200px">
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

  </form>
</div>
@endsection
    
@section('js')
    <script async defer type="text/javascript"
        src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initMap&language=en">
    </script>
    <script>
      var directionsService = null;
      var directionsRenderer = null;
      var CircleA = null;
      var CircleB = null;
      var placeA = null;
      var placeB = null;

        function initMap() {
             directionsService = new google.maps.DirectionsService();
             directionsRenderer = new google.maps.DirectionsRenderer();

            const myLatLng = {
                lat: 41.37977981567383,
                lng: 2.1785600185394287
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 9,
                center: myLatLng,
            });

            directionsRenderer.setMap(map);

            CircleA = new google.maps.Circle({
                  strokeColor: "#FF0000",
                  strokeOpacity: 0.8,
                  strokeWeight: 2,
                  fillColor: "#FF0000",
                  fillOpacity: 0.35,
                  map,
                  center: myLatLng,
                  radius: $("#pickup_radius").val() * 1000,
                  visible: false
                });

                CircleB = new google.maps.Circle({
                  strokeColor: "#FF0000",
                  strokeOpacity: 0.8,
                  strokeWeight: 2,
                  fillColor: "#FF0000",
                  fillOpacity: 0.35,
                  map,
                  center: myLatLng,
                  radius: $("#dropoff_radius").val() * 1000,
                  visible: false
                });



            const options = {

                fields: ["address_components", "geometry", "icon", "name"],

                types: ["establishment"],
            };

            const pickup_point = document.getElementById("pickup_point");
            const dropoff_point = document.getElementById("dropoff_point");

            const pickup_autocomplete = new google.maps.places.Autocomplete(pickup_point, options);
            const dropoff_autocomplete = new google.maps.places.Autocomplete(dropoff_point, options);

           
            

            google.maps.event.addListener(pickup_autocomplete, 'place_changed', function () {
               
              placeA = pickup_autocomplete.getPlace();
              placeB = dropoff_autocomplete.getPlace();
              // console.log(placeA);
              CircleA.setCenter(placeA.geometry.location);
              CircleA.setVisible(true);
                calcRoute(placeA.geometry.location, placeB.geometry.location);
                $('#pickup_latitude').val(placeA.geometry['location'].lat());
                $('#pickup_longitude').val(placeA.geometry['location'].lng());
                $('#dropoff_latitude').val(placeB.geometry['location'].lat());
                $('#dropoff_longitude').val(placeB.geometry['location'].lng());

            });

            google.maps.event.addListener(dropoff_autocomplete, 'place_changed', function () {

              placeA = pickup_autocomplete.getPlace();
              placeB = dropoff_autocomplete.getPlace();
              
                
              CircleB.setCenter(placeB.geometry.location);
              CircleB.setVisible(true);
                calcRoute(placeA.geometry.location, placeB.geometry.location);
                $('#pickup_latitude').val(placeA.geometry['location'].lat());
                $('#pickup_longitude').val(placeA.geometry['location'].lng());
                $('#dropoff_latitude').val(placeB.geometry['location'].lat());
                $('#dropoff_longitude').val(placeB.geometry['location'].lng());
            });

        } // end of initMap Func()
        window.initMap = initMap;


        function calcRoute(start, end) {
            // var start = document.getElementById("pickup_point").value;
            // var end = document.getElementById("dropoff_point").value;
           
            var request = {
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode.DRIVING
            };
            directionsService.route(request, function(result, status) {
                
                if (status == 'OK') {
                    directionsRenderer.setDirections(result);
                    distance.value = (result.routes[0].legs[0].distance.value / 1000);
                }else{
                  distance.value = 0;
                }
            });

            
        }

        $("#pickup_radius").change(function(){
          var pickup_radius = $(this).val() * 1000;
          CircleA.setRadius(pickup_radius);
        });

        $("#dropoff_radius").change(function(){
          var dropoff_radius = $(this).val() * 1000;
          CircleB.setRadius(dropoff_radius);
        });
        </script>

        <script type="text/javascript">
        $(document).ready(function() {
            $(".form-control").on('focus', function() {

                $(this).removeClass('is-invalid')
            })

            $('.minimum_hours').select2();
        });
    </script>
@endsection