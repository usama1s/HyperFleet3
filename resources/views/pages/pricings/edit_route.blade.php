@extends('layouts.app')

@section('title', 'Edit Route')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
  <li class="breadcrumb-item"><a href="{{url('pricings')}}">Pricings </a></li>
<li class="breadcrumb-item active">Edit</li>

</ol>

@endsection

@section('content')
<div class="container">

  <form method="POST" action="{{route('route.update')}}" enctype="multipart/form-data">
    @csrf


   
    <div class="row">
      <div class="col-md-6">
          <input type="hidden" name="route_id" value="{{$route->id}}">
          <input type="hidden" name="pickup_latitude" id="pickup_latitude" value="{{ $pickup_lat }}" />
          <input type="hidden" name="pickup_longitude" id="pickup_longitude" value="{{ $pickup_long }}" />
          <input type="hidden" name="dropoff_latitude" id="dropoff_latitude" value="{{ $dropoff_lat }}" />
          <input type="hidden" name="dropoff_longitude" id="dropoff_longitude" value="{{ $dropoff_long }}" />
          <div class="form-group">
            <label>Pickup Point</label>
            <input class="form-control @error('pickup_point') is-invalid @enderror" type="text" name="pickup_point" value="{{ $start_point }}"
              id="pickup_point" required>

            @error('pickup_point')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong>
            </div>
            @enderror
          </div>
          <div class="form-group">
            <label>Pickup Radius</label>
            <input class="form-control @error('pickup_radius') is-invalid @enderror" type="number" name="pickup_radius" value="{{ $route->start_radius }}"
              id="pickup_radius" step=".1" required>

            @error('pickup_radius')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong>
            </div>
            @enderror

          </div>
          <div class="form-group">
            <label>Dropoff Point</label>
            <input class="form-control @error('dropoff_point') is-invalid @enderror" type="text" name="dropoff_point" value="{{ $end_point }}"
              id="dropoff_point" required>

            @error('dropoff_point')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong>
            </div>
            @enderror
          </div>
          <div class="form-group">
            <label>Dropoff Radius</label>
            <input class="form-control @error('dropoff_radius') is-invalid @enderror" type="number" name="dropoff_radius" value="{{ $route->end_radius }}"
              id="dropoff_radius" step=".1" required>

            @error('dropoff_radius')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong>
            </div>
            @enderror

          </div>
          <div class="form-group">
            <label>Price</label>
            <input class="form-control @error('price') is-invalid @enderror" type="text" name="price" value="{{ $route->price }}"
              id="price" required>

            @error('price')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong>
            </div>
            @enderror

          </div>
          <div class="form-group">
            <label>Distance</label>
            <input class="form-control @error('distance') is-invalid @enderror" type="text" name="distance" value="{{ $route->distance }}" id="distance" required readonly>

            @error('distance')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong>
            </div>
            @enderror

          </div>
          <div class="form-group form-check">
            @if($route->isValidForReturn == 1)
            <input type="checkbox" class="form-check-input" id="valid_for_return" name="valid_for_return" checked>
            @else
            <input type="checkbox" class="form-check-input" id="valid_for_return" name="valid_for_return">
            @endif
            <label class="form-check-label" for="validForReturn">Valid for Return</label>
         </div>
          <div class="d-flex justify-content-end" style="margin: 40px 228px;">
            <input class="btn btn-lg btn-success" type="submit" value="Update" style="width:100px">
          </div>


        

      </div>
      <div class="col-md-6" id="map"></div>


  
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
             geocoder = new google.maps.Geocoder();

             var startLatLng =  new google.maps.LatLng($('#pickup_latitude').val(), $('#pickup_longitude').val());
             var endLatLng  =  new google.maps.LatLng($('#dropoff_latitude').val(), $('#dropoff_longitude').val());

             geocoder.geocode( { 'location': startLatLng}, function(results, status) {
                if (status == 'OK') {
                  placeA =results[0];
                  console.log("this is geocoder", placeA);
                  
                } else {
                  alert('Geocode was not successful for the following reason: ' + status);
                }
              });

              geocoder.geocode( { 'location': endLatLng}, function(results, status) {
                if (status == 'OK') {
                  placeB =results[0];
                } else {
                  alert('Geocode was not successful for the following reason: ' + status);
                }
              });

            
            const myLatLng = {
                lat: $('#pickup_latitude').val(),
                lng: $('#pickup_longitude').val()
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 5,
                center: myLatLng,
            });

            directionsRenderer.setMap(map);

            calcRoute(startLatLng,endLatLng);

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
             

                calcRoute(placeA.geometry.location, placeB.geometry.location);
                $('#pickup_latitude').val(placeA.geometry['location'].lat());
                $('#pickup_longitude').val(placeA.geometry['location'].lng());
                $('#dropoff_latitude').val(placeB.geometry['location'].lat());
                $('#dropoff_longitude').val(placeB.geometry['location'].lng());
            });

            google.maps.event.addListener(dropoff_autocomplete, 'place_changed', function () {

             
              placeB = dropoff_autocomplete.getPlace();
              
                
              
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

            console.log(start,end);
            var request = {
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode.DRIVING
            };
            directionsService.route(request, function(result, status) {
                
                if (status == 'OK') {
                    directionsRenderer.setDirections(result);
                    distance.value = (result.routes[0].legs[0].distance.value / 1000);

                    
                    CircleA.setCenter(result.routes[0].legs[0].start_location);
                    CircleA.setVisible(true);

                    CircleB.setCenter(result.routes[0].legs[0].end_location);
                    CircleB.setVisible(true);


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

       



        $(document).ready(function() {
            $(".form-control").on('focus', function() {

                $(this).removeClass('is-invalid')
            })

            $('.minimum_hours').select2();
        });
    </script>
@endsection