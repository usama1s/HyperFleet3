@php
use Illuminate\Support\Str;
use App\Models\VehicleClass;
use App\Models\Voucher;
use App\Models\Driver;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Customer;
$supplier = new Supplier();
$driver = new Driver();
@endphp

@extends('layouts.app')

@section('title', 'Add New Booking')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
        <li class="breadcrumb-item"><a href="{{ url('/bookings') }}">Bookings </a></li>
        <li class="breadcrumb-item active">Create</li>

    </ol>

@endsection

@section('content')
    <div class="container">
        {{-- ROW ONE Vehicle General Information --}}
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
                            <form action="{{ route('bookings.store') }}" method="POST" id="booking-form">
                                @csrf
                                <input type="hidden" id="booking_type" name="booking_type" value="point-2-point">
                                <div class="card card-default">
                                    <div class="card-header">
                                        <h3 class="card-title">Booking Information</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                    class="fas fa-minus"></i></button>

                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row vehicle_row odd">
                                            <div class="form-group col-md-6">
                                                <label>Pickup Point</label>
                                                <input class="form-control @error('pickup_point') is-invalid @enderror"
                                                    type="text" name="pickup_point" value="{{ old('pickup_point') }}"
                                                    id="pickup_point" placeholder="Pickup Point">
                                                @error('pickup_point')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                                <br>
                                                <label>Vehicle Class</label>
                                                @php
                                                    $arr = [];
                                                @endphp
                                                <select class="form-control select2js" name="vehicle_class"
                                                    id="vehicle_class_id">
                                                    <option value="">Select Vehicle Class</option>
                                                    @foreach (VehicleClass::all() as $item)
                                                        @php
                                                            $arr[$item->id] = $item->price;
                                                        @endphp
                                                        @if (Request::old('vehicle_class') == $item->id)
                                                            <option value="{{ $item->id }}" selected>
                                                                {{ $item->name }}</option>
                                                        @else
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @php
                                                    $json_v_prices = json_encode($arr);                                                    
                                                @endphp
                                                <script>
                                                    window.vehicle_prices = JSON.parse(`{!! $json_v_prices !!}`);
                                                </script>
                                                @error('vehicle_class')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <div id="pointtopoint">
                                                    <label>Drop off</label>
                                                    <input class="form-control @error('drop_off') is-invalid @enderror"
                                                        type="text" name="drop_off" value="{{ old('drop_off') }}"
                                                        id="drop_off" placeholder="Drop off">
                                                    @error('drop_off')
                                                        <div class="invalid-feedback" style="display:block;" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </div>
                                                    @enderror
                                                        <br>
                                                    <label>Distance</label>

                                                    <input class="form-control" type="text" id="distance" readonly>
                                                </div>
                                                <div id="hourlybooking">
                                                    <label>Duration</label>
                                                    <select name="hourlybooking" id="hours" class="form-control">
                                                        <option value="">Select Hours</option>

                                                        @for ($i = 1; $i <= 24; $i++)
                                                            @if (old('hourlybooking') == $i)
                                                                <option value="{{ $i }}" selected>
                                                                    {{ $i }} {{ Str::plural('hour', $i) }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $i }}">{{ $i }}
                                                                    {{ Str::plural('hour', $i) }} </option>
                                                            @endif
                                                        @endfor

                                                    </select>
                                                    @error('hourlybooking')
                                                        <div class="invalid-feedback" style="display:block;" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row vehicle_row even">
                                            <div class="form-group col-md-3">
                                                <label>Pickup Date</label>
                                                <input
                                                    class="form-control datetimepicker-input @error('pickup_date') is-invalid @enderror"
                                                    type="text" name="pickup_date" id="pickup_date"
                                                    placeholder="Pickup Date" data-toggle="datetimepicker"
                                                    data-target="#pickup_date" autocomplete="off">
                                                @error('pickup_date')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>Pickup Time</label>
                                                <input
                                                    class="form-control datetimepicker-input @error('pickup_time') is-invalid @enderror"
                                                    type="text" name="pickup_time" id="pickup_time"
                                                    placeholder="Pickup Time" data-toggle="datetimepicker"
                                                    data-target="#pickup_time" required autocomplete="off">
                                                @error('pickup_time')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>No. of Adults</label>
                                                <input class="form-control" type="number" name="no_of_adults"
                                                    value="{{ old('no_of_adults') }}" id="No. of Adults"
                                                    placeholder="No. of Passenger">
                                                @error('no_of_adults')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label>No. of Bags</label>
                                                <input class="form-control" type="number" name="no_of_bags"
                                                    value="{{ old('no_of_bags') }}" id="no_of_bags"
                                                    placeholder="No. of Bags">
                                                @error('no_of_bags')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row vehicle_row odd">

                                            <div class="form-group col-md-4">
                                                <label>Assign to Supplier</label>
                                                <select class="form-control select2js" name="supplier_name"
                                                    id="assign_to_supplier">
                                                    <option value="">Select Supplier</option>
                                                    @foreach (Supplier::all() as $item)
                                                        @if (Request::old('supplier_name') == $item->user_id)
                                                            <option value="{{ $item->user_id }}" selected>
                                                                {{ $supplier->fullName($item->user_id) }}</option>
                                                        @else
                                                            <option value="{{ $item->user_id }}">
                                                                {{ $supplier->fullName($item->user_id) }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('supplier')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Voucher Code (Optional)</label>
                                                <input class="form-control" type="text" name="voucher_code"
                                                    value="{{ old('voucher_code') }}" id="voucher_code"
                                                    placeholder="Voucher Code (Optional)"
                                                    value="{{ old('voucher_code') }}">
                                                <div class="voucher_code_error text-danger" style="font-size: smaller;">
                                                </div>
                                                <div class="voucher_code_success text-success"
                                                    style="font-size: smaller;"></div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Price</label>
                                                <input class="form-control @error('price') is-invalid @enderror"
                                                    type="number" name="price" id="price"
                                                    value="{{ old('price') }}" placeholder="Price"
                                                    step="any">
                                                @error('price')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                                <p id="pricing_type"></p>
                                            </div>

                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="card card-default">
                                    <div class="card-header">
                                        <h3 class="card-title">Customer Information</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                    class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label>Select Customer</label>
                                                <select class="form-control select2js" name="customer" id="customer_id">
                                                    <option value="">New Customer</option>
                                                    @foreach (Customer::all() as $customer)
                                                        @if (Request::old('customer') == $customer->id)
                                                            <option value="{{ $customer->id }}" selected>
                                                                {{ $customer->fullname() }}</option>
                                                        @else
                                                            <option value="{{ $customer->id }}">
                                                                {{ $customer->fullname() }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Flight Number</label>
                                                <input class="form-control @error('flightnumber') is-invalid @enderror" type="text" name="flightnumber" value="{{ old('flightnumber') }}"
                                                    id="flightnumber" placeholder="EK524" value="{{ old('flightnumber') }}">
                                                @error('flightnumber')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row vehicle_row even">
                                            <div class="form-group col-md-4">
                                                <label>First Name</label>
                                                <input class="form-control @error('first_name') is-invalid @enderror"
                                                    type="text" name="first_name" value="{{ old('first_name') }}"
                                                    id="first_name" placeholder="First Name"
                                                    value="{{ old('first_name') }}">
                                                @error('first_name')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Last Name</label>
                                                <input class="form-control @error('last_name') is-invalid @enderror"
                                                    type="text" name="last_name" value="{{ old('last_name') }}"
                                                    id="last_name" placeholder="Last Name"
                                                    value="{{ old('last_name') }}">
                                                @error('last_name')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Contact No.</label>
                                                <input class="form-control @error('contact_no') is-invalid @enderror"
                                                    type="text" name="contact_no" value="{{ old('contact_no') }}"
                                                    id="contact_no" placeholder="Contact No."
                                                    value="{{ old('contact_no') }}">
                                                @error('contact_no')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row vehicle_row odd">
                                            <div class="form-group col-md-4">
                                                <label>Email</label>
                                                <input class="form-control @error('email') is-invalid @enderror"
                                                    type="email" name="email" value="{{ old('email') }}"
                                                    id="email" placeholder="Email" value="{{ old('email') }}">
                                                @error('email')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Special Instructions</label>
                                                <input
                                                    class="form-control @error('special_instructions') is-invalid @enderror"
                                                    type="text" name="special_instructions"
                                                    value="{{ old('special_instructions') }}" id="special_instructions"
                                                    placeholder="Special Instructions"
                                                    value="{{ old('special_instructions') }}">
                                                @error('special_instructions')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>Pickup Sign</label>
                                                <input class="form-control @error('pickup_sign') is-invalid @enderror"
                                                    type="text" name="pickup_sign" value="{{ old('pickup_sign') }}"
                                                    id="pickup_sign" placeholder="Pickup Sign"
                                                    value="{{ old('pickup_sign') }}">

                                                @error('pickup_sign')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row vehicle_row even">
                                            <div class="form-group col-md-12">
                                                <label>Payment Method</label>

                                                <select name="payment_method" class="form-control select2js"
                                                    id="payment_select">
                                                    <option value="" selected>Select Payment method</option>
                                                    <option value="cash">Cash</option>
                                                    <option value="credit_card">Credit Card</option>

                                                </select>

                                                <input id="nonce" name="payment_method_nonce" type="hidden" />
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="bt-drop-in-wrapper">
                                                            <div id="bt-dropin" style="display:none"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                                                <script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>

                                                <script>
                                                    var form = document.querySelector('#booking-form');
                                                    var client_token = "{{ $clientToken }}";
                                                    braintree.dropin.create({
                                                        authorization: client_token,
                                                        selector: '#bt-dropin',

                                                    }, function(createErr, instance) {
                                                        if (createErr) {
                                                            console.log('Create Error', createErr);
                                                            return;
                                                        }
                                                        form.addEventListener('submit', function(event) {
                                                            event.preventDefault();
                                                            instance.requestPaymentMethod(function(err, payload) {
                                                                if (err) {
                                                                    if (err.message == "No payment method is available.") {
                                                                        form.submit();
                                                                    } else {
                                                                        console.log('Request Payment Method Error', err);
                                                                        return;

                                                                    }
                                                                }
                                                                // Add the nonce to the form and submit
                                                                document.querySelector('#nonce').value = payload.nonce;
                                                                form.submit();
                                                            });
                                                        });
                                                    });
                                                </script>

                                                @error('payment_method')
                                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>



                                        <div class="row vehicle_row odd">

                                            <div class="col-md-6">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td>Booking Price</td>
                                                            <td>
                                                                {{ Config('currency-symbol') }} <span
                                                                    id="input_price">{{ old('price') }}</span>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>VAT</td>
                                                            <td>
                                                                {{ Config('currency-symbol') }} <span
                                                                    id="vat">0.00</span>
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>Discount</td>
                                                            <td>
                                                                {{ Config('currency-symbol') }} <span
                                                                    id="discount">0.00</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Grand Total</th>
                                                            <td>
                                                                {{ Config('currency-symbol') }} <span
                                                                    id="total_price">{{ old('grand_total') }}</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>

                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-end" style="margin: 50px 20px;">
                                                    <input class="btn btn-lg btn-success" type="submit" value="Save"
                                                        style="width:200px">
                                                </div>
                                            </div>

                                            <input type="hidden" name="grand_total" id="grand_total"
                                                value="{{ old('grand_total') }}">

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
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={{ env('GOOGLE_MAP_KEY', null) }}">
    </script>
    <script>
        var placeA = null;
        var placeB = null;
        var route = null
        var pricing_scheme_selector = null;

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
                    console.log(result);
                    distance.value = (result.routes[0].legs[0].distance.value / 1000) + " KM";
                    route = result.routes[0].legs[0];
                } else {
                    distance.value = 0;
                    route = null;
                }
            });


        }

        $(document).ready(function() {

            var input = document.getElementById('pickup_point');
            var input2 = document.getElementById('drop_off');

            directionsService = new google.maps.DirectionsService();
            placeA = new google.maps.places.Autocomplete(input);
            placeB = new google.maps.places.Autocomplete(input2);

            google.maps.event.addListener(placeA, 'place_changed', function() {

                $("#assign_to_driver").val("null").change();
                calcRoute(placeA.getPlace().geometry.location, placeB.getPlace().geometry.location);
            });
            google.maps.event.addListener(placeB, 'place_changed', function() {

                $("#assign_to_driver").val("null").change();
                calcRoute(placeA.getPlace().geometry.location, placeB.getPlace().geometry.location);
            });


            var fetch_driver_count = 0;
            $(".form-control").on('focus', function() {

                $(this).removeClass('is-invalid')
            })

            $("#payment_select").on('change', function() {

                if ($(this).val() == "credit_card") {
                    $("#bt-dropin").slideDown();
                } else {
                    $("#bt-dropin").slideUp();
                }

            })


            selected_drivers = new Object();
            vat = {{ config('vat') }}
            var voucher = null;
            var price = null;

            $("#vehicle_class_id").change(function() {
                var value = $(this).val();
                var prices = window.vehicle_prices;

                price = parseInt(prices[value]).toFixed(2);
                document.getElementById("price").value = price;

                $("#input_price").text(price);

                var nvat = parseFloat(vat);


                

                calculateGrandPrice(price, nvat);
                $("#assign_to_driver").val("null").change();


            });

            $("#pricing_scheme_selector").change(function(){
                 pricing_scheme_selector = $(this).val();
                
                getPricingScheme(pricing_scheme_selector);
            });

            $("#assign_to_supplier").change(function() {
                var val = $(this).val();

                if (val == "") {
                    $("#assign_to_driver_container").show();
                    $("#vehicle_container").show();
                } else {
                    $("#assign_to_driver_container").hide();
                    $("#vehicle_container").hide();
                }
            });

            $('#assign_to_supplier, #vehicle_class_id').on('change', function() {

                var supplier_id = $("#assign_to_supplier").val();
                var v_class = $("#vehicle_class_id").val();
                var pickup_time = $("#pickup_time").val();

                $("#vehicle_input").val('');
                


                fetchDrivers(supplier_id, v_class, pickup_time);
                

            });

            $("#hours").on("change", function(){
                
                $("#assign_to_driver").val("null").change();
                
            
            });

            $('#pickup_time').on('focusout', function() {

                var supplier_id = $("#assign_to_supplier").val();
                var v_class = $("#vehicle_class_id").val();
                var pickup_time = $("#pickup_time").val();


                if (v_class != "") {

                    fetchDrivers(supplier_id, v_class, pickup_time);
                    
                }

            });


            $("#price").on('change', function() {
                price = $(this).val();
                console.log(price);
                $("#input_price").text(price);


                nprice = parseFloat(price)
                nvat = parseFloat(vat)

                calculateGrandPrice(nprice, nvat);


            });


            function calculateGrandPrice($price, $vat) {
                if (voucher == null) {
                    discout = 0;
                } else {
                    if (voucher.type == "flat") {
                        discout = voucher.value;
                    } else if (voucher.type == "percentage") {
                        discout = $price * (voucher.value / 100)
                    }
                }

                $price = $price - discout;
                pprice = $price * ($vat / 100)

                total_price = ($price + pprice);

                $("#vat").text(pprice.toFixed(2));
                $("#discount").text(discout.toFixed(2));
                $("#total_price").text(total_price.toFixed(2));

                $("#grand_total").val(total_price.toFixed(2))
            }


            $("#assign_to_driver").change(function() {


                

                driver_id = $(this).val();
                if (driver_id == "null") {

                    $("#vehicle_input").val('');
                    $("#price").val('');
                    $("#input_price").text('0.00');

                    nprice = 0
                    nvat = 0

                    pprice = 0
                    total_price = 0

                    $("#vat").text(pprice.toFixed(2))
                    $("#total_price").text(total_price.toFixed(2))

                    $("#grand_total").val(total_price.toFixed(2))

                    return;
                }
                $("#vehicle_input").val(selected_drivers[driver_id].manufacturer);

               
                getPricingScheme(pricing_scheme_selector);
               
                // nprice = parseFloat(selected_drivers[driver_id].price)
                nvat = parseFloat(vat)

                // pprice =  nprice * (vat / 100) 
                // total_price = pprice + nprice;

                // $("#vat").text(pprice.toFixed(2))
                //    $("#total_price").text(total_price)

                //    $("#grand_total").val(total_price.toFixed(2))

            });


            function getPricingScheme(pricing_scheme_id){

                
                var reqPrms = {};
                if (placeB != null) {
                    reqPrms = {

                        pickup_point: {
                            lng: placeA.getPlace().geometry.location.lng(),
                            lat: placeA.getPlace().geometry.location.lat()
                        },
                        drop_off_point: {
                            lng: placeB.getPlace().geometry.location.lng(),
                            lat: placeB.getPlace().geometry.location.lat()
                        },
                        hourly: null,
                        distance: distance.value,


                    }
                } else {

                    reqPrms = {

                        pickup_point: {
                            lng: placeA.getPlace().geometry.location.lng(),
                            lat: placeA.getPlace().geometry.location.lat()
                        },
                        drop_off_point: null,
                        hourly: $("#hours").val(),
                        distance: distance.value,


                    }

                }

                $.ajax({
                    url: "{{ url('api/check-pricing') }}/" + pricing_scheme_id,
                    type: 'POST',
                    data: reqPrms,
                    success: function(data) {
                        if(data.status){
                            $("#driver_error").text("").show();
                            $("#price").val(data.price);
                            $("#input_price").text(data.price);

                            $("#pricing_type").text(data.type_of_pricing);

                            nprice = parseFloat(data.price)
                            nvat = parseFloat(vat)

                            calculateGrandPrice(nprice, nvat);

                            
                        }else{
                            $("#pricing_type").text('');
                            $("#price").val('');
                            $("#input_price").val('');
                            $("#driver_error").html("<strong>" + data.message + "</strong>").show();

                        }
                    }
                });

            }



            function fetchDrivers(s_id, v_id, pickup_time) {

                fetch_driver_count++

                $.ajax({

                    url: "{{ url('api/assignBooking') }}",
                    type: 'POST',
                    data: {

                        supplier_id: s_id,
                        v_class: v_id,
                        pickup_time: pickup_time,


                    },
                    success: function(data) {
                        console.log(data);
                        selected_drivers = new Object();
                        $("#driver_error").text("").show();
                        $("#assign_to_driver").empty();



                        $('#assign_to_driver').append(`
                       <option value ="null"> Select Driver </option>
                             `)

                        if (data.status != "false") {

                            for (i in data) {

                                first_name = data[i].first_name;
                                last_name = data[i].last_name;
                                user_id = data[i].user_id;



                                selected_drivers[user_id] = data[i];

                                if (user_id == "{{ old('driver') }}") {
                                    $('#assign_to_driver').append(`
                                     <option value="${user_id}" selected> ${first_name} ${last_name}</option>
                                     `)
                                } else {
                                    $('#assign_to_driver').append(`
                                        <option value="${user_id}"> ${first_name} ${last_name}</option>
                                    `)
                                }


                            }
                        } else {
                            selected_drivers = new Object();
                            if (fetch_driver_count != 1) {

                                $("#driver_error").html("<strong>" + data.error + "</strong>").show();

                            }
                        }





                        // $("#vehicle_input").append().val(data.manufacturer)


                    }
                })
            }

            $('#voucher_code').focusout(function(e) {
                // if(!$(this).val()==''){
                var voucher_code = $("input[name='voucher_code']").val()
                var token = $("meta[name='csrf-token']").attr("content")

                $.ajax({
                    url: "{{ route('voucher.validate') }}",
                    type: 'POST',
                    data: {
                        _token: token,
                        voucher_code: voucher_code,
                        supplier_id: "{{ auth()->user()->id }}"
                    },
                    success: function(data) {

                        if (voucher_code != '') {

                            if (data.success == "true") {
                                voucher = data;

                                var nvat = parseFloat(vat)
                                var nprice = parseFloat($("#price").val());

                                calculateGrandPrice(nprice, nvat);


                                $(".voucher_code_error").html('');
                                $(".voucher_code_success").html("You got " + data.discout +
                                    " discount");


                            } else {
                                voucher = null;
                                $(".voucher_code_success").html('');
                                $(".voucher_code_error").html(data.error);
                            }

                        } else {
                            voucher = null;
                            $(".voucher_code_error").html('');
                            $(".voucher_code_success").html('');

                            var nvat = parseFloat(vat)
                            var nprice = parseFloat($("#price").val());

                            calculateGrandPrice(nprice, nvat);



                        }


                    }
                })



                /// }
            })

            $("#customer_id").change(function() {
                var customer_id = $(this).val();
                $.ajax({
                    url: "{{ url('api/get-customer') }}/" + customer_id,
                    method: "POST",
                    success: (user) => {
                        $("#first_name").val(user.first_name);
                        $("#last_name").val(user.last_name);
                        $("#contact_no").val(user.contact_no);
                        $("#email").val(user.email);
                    }
                });
            });

            $(function() {
                $("#assign_to_driver").select2();
                $("#assign_to_supplier").select2();
                $("#customer_id").select2();
                $("#hours").select2();
                $("#p2p-btn").addClass('active');
                $('#hourlybooking').hide();

                $("#p2p-btn").click(function() {
                    point2point();
                });

                $("#hourly-btn").click(function() {
                    hourly();
                });

                var old_booking_type = "{{ old('booking_type') }}";

                if (old_booking_type == "point-2-point") {
                    point2point();
                }

                if (old_booking_type == "hourly") {
                    hourly();
                }

                $('#pickup_date').datetimepicker({
                    format: 'YYYY-MM-DD',
                    defaultDate: '{{ old('pickup_date') }}'
                });
                $('#expiry_date').datetimepicker({
                    format: 'YYYY-MM-DD',
                    defaultDate: '{{ old('expiry_date') }}'
                });

                var time = "{{ old('pickup_time') }}";






                if (time != "") {

                    var hours = Number(time.match(/^(\d+)/)[1]);
                    var minutes = Number(time.match(/:(\d+)/)[1]);
                    var AMPM = time.match(/\s(.*)$/)[1];
                    if (AMPM == "PM" && hours < 12) hours = hours + 12;
                    if (AMPM == "AM" && hours == 12) hours = hours - 12;
                    var sHours = hours.toString();
                    var sMinutes = minutes.toString();
                    if (hours < 10) sHours = "0" + sHours;
                    if (minutes < 10) sMinutes = "0" + sMinutes;

                    $('#pickup_time').datetimepicker({
                        format: 'LT',
                        defaultDate: moment({
                            hour: sHours,
                            minute: sMinutes
                        })
                    });
                } else {
                    $('#pickup_time').datetimepicker({
                        format: 'LT'
                    });
                }

                var supplier_id = $("#assign_to_supplier").val();
                var v_class = $("#vehicle_class_id").val();
                var pickup_time = $("#pickup_time").val();



                fetchDrivers(supplier_id, v_class, pickup_time);



            });


            function point2point() {
                $("#hourly-btn").removeClass('active')
                $('#hourlybooking').hide();
                $('#hours').val('');
                $('#hours').trigger('change');
                $('#pointtopoint').show();
                $("#p2p-btn").addClass('active');
                $("#booking_type").val("point-2-point");

               
                var input2 = document.getElementById('drop_off');
                placeB = new google.maps.places.Autocomplete(input2);
            }

            function hourly() {
                $("#p2p-btn").removeClass('active')
                $('#hourlybooking').show();
                $('#pointtopoint').hide();
                $('#drop_off').val('');
                $("#hourly-btn").addClass('active');
                $("#booking_type").val("hourly");
                placeB = null;
            }

            //     var pickup_point = places({
            //     appId: "pl85FXT44F74",
            //     apiKey: "322ee8a2ecb5c80593e382020d8f9342",
            //     container: document.querySelector('#pickup_point')
            //   });

            //   var drop_off = places({
            //     appId: "pl85FXT44F74",
            //     apiKey: "322ee8a2ecb5c80593e382020d8f9342",
            //     container: document.querySelector('#drop_off')
            //   });


        });
    </script>
@endsection
