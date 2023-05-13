@php
use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="base-root-path" content="{{ config('app.url') }}">
    <title>Booking Form</title>
    <link rel="stylesheet" href="{{ asset('/public/css/simplepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />
    <style>
        #map {
            height: 100%;
            width: 100%;
        }
     
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input[type=number] {
  -moz-appearance: textfield;
}
    </style>
   

</head>
<body class="min-w-[430px]">
    <div class="bg-booking-bg bg-cover min-h-screen flex items-center py-10 px-16">
        <div
            class="bg-gray-200 max-w-screen-xl mx-auto w-full relative grid lg:grid-cols-2 gap-5 rounded-lg rounded-tl-none">
            <label for="Toggle3"
                class="absolute top-[75px] -left-[132px] inline-flex items-center p-2 rounded-md cursor-pointer -rotate-90 ">
                <input id="Toggle3" type="checkbox" class="hidden peer">
                <span class="px-4 py-2 rounded-l-md rounded-bl-none bg-gray-300 bg-violet-500" id="tabP2Pbtn">Point to
                    Point</span>
                <span class="px-4 py-2 rounded-r-md rounded-br-none bg-gray-300" id="tabHourlyBtn">Hourly</span>
            </label>
            <div class="flex  px-8 py-6">
                <div class="mx-auto items-center justify-center w-full max-w-[550px] ">
                    <div class="flex justify-between items-center">
                        <h1 class="text-[18px] sm:text-3xl font-bold underline text-blue-950">
                            Booking Form
                        </h1>
                        <div>
                            <input type="checkbox" id="roundtrip" name="roundtrip" value="yes" class="sm:w-4 sm:h-4">
                            <label for="roundtrip" class="text-base sm:text-xl font-bold text-blue-950">Round
                                Trip</label>
                        </div>
                    </div>
                    <form action="{{ route('vehicle.choose') }}" method="GET" class="mt-10">
                        @csrf
                        <input type="hidden" id="booking_type" name="booking_type" value="point-2-point">

                        <input type="hidden" id="pickup_cords" name="pickup_cords" value="">
                        <input type="hidden" id="dropoff_cords" name="dropoff_cords" value="">
                        <input type="hidden" id="userdistance" name="userdistance" value="">
                        <div class="-mx-3 flex flex-wrap">
                            <div class="w-full px-3 sm:w-1/2">
                                <div class="mb-5">
                                    <label class="mb-3 block text-base font-medium text-blue-950">
                                        Pickup Point*
                                    </label>
                                    <input type="text" name="pickup_point" @if(session('pickup_point')) value="{{ session('pickup_point') }}" @else value="" @endif 
                                        id="pickup_point" placeholder="Select a pickup point"
                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-[0.45rem] px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                                    @error('pickup_point')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-full px-3 sm:w-1/2 block" id="dropOffToggle">
                                <div class="mb-5">
                                    <label class="mb-3 block text-base font-medium text-blue-950">
                                        Drop Off*
                                    </label>
                                    <input type="text" name="drop_off" value="{{ old('drop_off') }}" id="drop_off"
                                        placeholder="Select a drop off point"
                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-[0.45rem] px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                                    @error('drop_off')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="w-full px-3 sm:w-1/2 hidden" id="hourlyToggle">
                                <div class="mb-5">
                                    <label class="mb-3 block text-base font-medium text-blue-950">
                                        Duration*
                                    </label>
                                    <div class="relative inline-block text-left w-full">

                                        <select name="hourlybooking" id="hours"
                                            class="w-full rounded-md border border-[#e0e0e0] bg-white px-6 py-[0.45rem] text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md @error('hourlybooking') is-invalid @enderror">
                                            <option value="">Select Hours</option>

                                            @for ($i = 1; $i <= 24; $i++) @if (old('hourlybooking')==$i) <option
                                                value="{{ $i }}" selected>{{ $i }}
                                                {{ Str::plural('hour', $i) }} </option>
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
                        </div>
                        <div class="-mx-3 flex flex-wrap">
                            <div class="w-full px-3 sm:w-1/2">
                                <div class="mb-5">
                                    <label for="date" class="mb-3 block text-base font-medium text-blue-950">
                                        Pickup Date
                                    </label>

                                    <input type="text" name="date" id="pickup-date-time" placeholder="26 April 2023 "
                                        data-toggle="datetimepicker" data-target="#pickup_date"
                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-[0.45rem] px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md @error('pickup_date') is-invalid @enderror"
                                        autocomplete="off" value="" />
                                    <input type="text" name="pickup_date" id="pickup-date" value="" hidden />
                                    <input type="text" name="pickup_time" id="pickup-time" value="" hidden />
                                    @error('pickup_date')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- <div class="w-full px-3 sm:w-1/2">
                                <div class="mb-5">
                                    <label for="time" class="mb-3 block text-base font-medium text-blue-950">
                                        Pickup Time
                                    </label>
                                    <input type="time" name="pickup_time" autocomplete="off"
                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-[0.45rem] px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md  @error('pickup_time') is-invalid @enderror" />
                                    @error('pickup_time')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div> -->
                        </div>
                        <div class="-mx-3 flex flex-wrap hidden" id="toogleinputfields">
                            <div class="w-full px-3 sm:w-1/2">
                                <div class="mb-5">
                                    <label for="date" class="mb-3 block text-base font-medium text-blue-950">
                                        Return Pickup Date
                                    </label>
                                    <input type="text" name="date" id="return-pickup-date-time"
                                        placeholder="26 April 2023 " data-toggle="datetimepicker"
                                        data-target="#pickup_date"
                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-[0.45rem] px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md @error('pickup_date') is-invalid @enderror"
                                        autocomplete="off" value="" />
                                    <input type="text" name="return-pickup-date" id="return-pickup-date" value=""
                                        hidden />
                                    <input type="text" name="return-pickup-time" id="return-pickup-time" value=""
                                        hidden />
                                </div>
                            </div>
                            <!-- <div class="w-full px-3 sm:w-1/2">
                                <div class="mb-5">
                                    <label for="time" class="mb-3 block text-base font-medium text-blue-950">
                                        Return Pickup Time
                                    </label>
                                    <input type="time" name="time" id="time"
                                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-[0.45rem] px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md" />
                                </div>
                            </div> -->
                        </div>
                        <div class="-mx-3 flex flex-wrap">
                            <div class="mb-5 flex items-center space-x-6 w-full px-3 sm:w-1/2"">
                <div class=" flex items-start flex-col">
                                <label class="mb-3 block text-base font-medium text-blue-950">
                                    Number of passengers (Optional)
                                </label>
                                <div class="flex flex-row h-10 w-full rounded-lg relative bg-transparent mt-1">
                                    <button type="button" onclick="decrement()"
                                        class=" bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-20 rounded-l cursor-pointer outline-none">
                                        <span class="m-auto text-2xl font-thin">−</span>
                                    </button>
                                    <input type="number"
                                        class="outline-none focus:outline-none text-center w-full bg-gray-300 font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700"
                                        name="no_of_passengers" id="inpnumber" value="0" />
                                    <button type="button" onclick="increment()"
                                        class="bg-gray-300 text-gray-600 hover:text-gray-700 hover:bg-gray-400 h-full w-20 rounded-r cursor-pointer">
                                        <span class="m-auto text-2xl font-thin">+</span>
                                    </button>
                                    @error('no_of_passengers')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                </div>
                {{-- row end --}}

                {{-- row end --}}
                <div>
                    <button
                        class="hover:shadow-form rounded-md bg-violet-500 py-[0.45rem] px-8 text-center text-base font-semibold text-white outline-none"
                        type="submit">
                        Start Booking
                    </button>
                </div>
                </form>
            </div>
        </div>
        <div class="w-full lg:h-full min-h-[500px]">
            <div id="map"></div>
        </div>
    </div>
    </div>




    @include('assets.js')
<script src="https://cdn.tailwindcss.com"></script>
<script src="{{ asset('/public/js/simplepicker.js') }}"></script>
    <script async defer type="text/javascript"
        src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initMap&language=en">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        const checkbox = document.getElementById("roundtrip");
        const toogleinputfields = document.getElementById("toogleinputfields");
        checkbox.addEventListener('click', function () {
            toogleinputfields.classList.toggle("hidden")
            console.log("first")
        });

        const ptopbtn = document.getElementById("tabP2Pbtn");
        const hourlybtn = document.getElementById("tabHourlyBtn");

        const togglePointToPoint = document.getElementById("dropOffToggle");
        const toggleHourly = document.getElementById("hourlyToggle");
        ptopbtn.addEventListener("click", function () {
            togglePointToPoint.style.display = "block";
            toggleHourly.style.display = "none";
            ptopbtn.classList.add("bg-violet-500");
            hourlybtn.classList.remove("bg-violet-500");
        })
        hourlybtn.addEventListener("click", function () {
            togglePointToPoint.style.display = "none";
            toggleHourly.style.display = "block";
            hourlybtn.classList.add("bg-violet-500");
            ptopbtn.classList.remove("bg-violet-500");
        })

    </script>
    <script>
        const simplepicker = new SimplePicker({
            zIndex: 10
        });

        const simplepicker2 = new SimplePicker({
            zIndex: 10,
        });

        let pickupDateActive = false;
        let returnPickupDateActive = false;

        const pickupDateTime = document.querySelector('#pickup-date-time');
        const pickupDate = document.querySelector("#pickup-date");
        const pickupTime = document.querySelector("#pickup-time");
        const returnPickupDateTime = document.querySelector('#return-pickup-date-time');
        const returnPickupDate = document.querySelector("#return-pickup-date");
        const returnPickupTime = document.querySelector("#return-pickup-time");

        pickupDateTime.addEventListener('click', () => {
            simplepicker.open();
            pickupDateActive = true;
            returnPickupDateActive = false;
        });

        returnPickupDateTime.addEventListener('click', () => {
            simplepicker.open();
            returnPickupDateActive = true;
            pickupDateActive = false;
        });

        simplepicker.on("submit", (date, readableDate) => {
            const parts = readableDate.split(" ");
            const day = parts[1];
            const month = parts[0];
            const time = parts[3] + " " + parts[4];
            const year = parts[2];
            console.log(parts);
            console.log("if" + pickupDateActive);
            if (pickupDateActive) {

                pickupDateTime.value = `${month} ${day} ${year} ${time}`;
                pickupDate.value = `${year}-${month}-${day} `;
                pickupTime.value = `${time}`;
            }
        });

        simplepicker2.on("submit", (seconddate, secondreadableDate) => {
            const parts = secondreadableDate.split(" ");
            const day = parts[1];
            const month = parts[0];
            const time = parts[3] + " " + parts[4];
            const year = parts[2];

            if (returnPickupDateActive) {
                // returnPickupDateTime = `${month} ${day} ${year} ${time}`; 
                returnPickupDate.value = `${year}-${month}-${day} `;
                returnPickupTime.value = `${time}`;
            }
        });

        simplepicker.on('close', () => {
            console.log("picker 1 Closed");
        });
        simplepicker2.on("close", () => {
            console.log("Picker 2 Closed");
        });

    </script>
    <script>
        var directionsService = null;
        var directionsRenderer = null;
        var pickup_point_cords = null;
        var drop_off_cords = null;

        function initMap() {
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();

            const myLatLng = {
                lat: 41.297445,
                lng: 2.083294099999989
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: myLatLng,
            });

            directionsRenderer.setMap(map);


            const options = {

                fields: ["address_components", "geometry", "icon", "name"],

                types: ["establishment"],
            };

            const pickup_point = document.getElementById("pickup_point");
            const dropoff_point = document.getElementById("drop_off");

            const pickup_autocomplete = new google.maps.places.Autocomplete(pickup_point, options);
            const dropoff_autocomplete = new google.maps.places.Autocomplete(dropoff_point, options);



            pickup_autocomplete.addListener('place_changed', function () {
                var place = pickup_autocomplete.getPlace();
                pickup_point_cords = {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng()
                };

                $("#pickup_cords").val(pickup_point_cords.lat + "," + pickup_point_cords.lng);
                calcRoute(pickup_point_cords, drop_off_cords);

            });


            dropoff_autocomplete.addListener('place_changed', function () {
                var place = dropoff_autocomplete.getPlace();
                drop_off_cords = {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng()
                };
                $("#dropoff_cords").val(drop_off_cords.lat + "," + drop_off_cords.lng);

                calcRoute(pickup_point_cords, drop_off_cords);
            });




        } // end of initMap Func()
        window.initMap = initMap;


        $(".form-control").on('focus', function () {

            $(this).removeClass('is-invalid')
        });

        // Voucher Code
        $('#voucher_code').focusout(function (e) {
            // if(!$(this).val()==''){


            var voucher_code = $("input[name='voucher_code']").val()
            var token = $("meta[name='csrf-token']").attr("content")


            $.ajax({
                url: "{{ route('voucher.validate') }}",
                type: 'POST',
                data: {
                    _token: token,
                    voucher_code: voucher_code,
                    supplier_id: '{{ session('
                    ref_id ') }}'
                },
                success: function (data) {
                    console.log(data);

                    if (voucher_code != '') {

                        if (data.success == "true") {
                            $(".voucher_code_error").html('');
                            $(".voucher_code_success").html("You got " + data.discout +
                                " discount");

                        } else {
                            $(".voucher_code_success").html('');
                            $(".voucher_code_error").html(data.error);
                        }
                    } else {

                        $(".voucher_code_error").html('');
                        $(".voucher_code_success").html('');

                    }
                },
                error: function (a, b, c) {
                    console.log(a);
                    console.log(b);
                    console.log(c);
                }
            })

            /// }
        })


        // Bags and passenger counter
        value = 0;
        $(".input-field, .input-field-bags").keydown(function (e) {
            var str = e.key;

            if (str != 'Backspace') {

                if (str == 'ArrowUp') {
                    var old = parseInt($(this).val());

                    if (isNaN(old)) {
                        $(this).val("0");
                        old = 0;
                    }
                    $(this).val(old + 1);
                }

                if (str == 'ArrowDown') {
                    var old = parseInt($(this).val());

                    if (old > 0) {

                        $(this).val(old - 1);

                    }
                }

                if (!str.match(/[0-9]/g)) {
                    return false;
                }

            }
        });

        $(".input-field, .input-field-bags").focusout(function (e) {
            if ($(this).val() == "") {
                $(this).val('0')
            }

        });

        $("#btn-plus").each(function () {

            $(this).on("click", function () {

                value++;
                $(".input-field").val(value);

            })
        })

        $("#btn-minus").each(function () {

            $(this).on("click", function () {
                if (value > 0) {
                    value--;
                    $(".input-field").val(value);
                }

            })
        })

        $("#btn-plus-bags").each(function () {

            $(this).on("click", function () {

                value++;
                $(".input-field-bags").val(value);

            })
        })

        $("#btn-minus-bags").each(function () {

            $(this).on("click", function () {
                if (value > 0) {
                    value--;
                    $(".input-field-bags").val(value);
                }

            })
        })

        // Initilize the objects
        $(function () {


            $("#vehicle_class_id").select2();
            $("#hours").select2();
            $("#p2p-btn").addClass('active');
            $('#hourlybooking').hide();

            $("#p2p-btn").click(function () {
                point2point();
            });

            $("#hourly-btn").click(function () {
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
                defaultDate: '{{ old('
                pickup_date ') }}'
            });
            $('#expiry_date').datetimepicker({
                format: 'YYYY-MM-DD',
                defaultDate: '{{ old('
                expiry_date ') }}'
            });

            var pickup_time = "{{ old('pickup_time') }}";

            if (pickup_time != "") {
                pickup_time = pickup_time.split(':');
                $('#pickup_time').datetimepicker({
                    format: 'LT',
                    defaultDate: moment({
                        hour: pickup_time[0],
                        minute: pickup_time[1]
                    })
                });
            } else {
                $('#pickup_time').datetimepicker({
                    format: 'LT'
                });
            }

        });

        function point2point() {
            $("#hourly-btn").removeClass('active')
            $('#hourlybooking').hide();
            $('#hours').val('');
            $('#hours').trigger('change');
            $('#pointtopoint').show();
            $("#p2p-btn").addClass('active');
            $("#booking_type").val("point-2-point");
        }

        function hourly() {
            $("#p2p-btn").removeClass('active')
            $('#hourlybooking').show();
            $('#pointtopoint').hide();
            $('#drop_off').val('');
            $("#hourly-btn").addClass('active');
            $("#booking_type").val("hourly");
        }

        function calcRoute(start, end) {

            if (start == null) {

                return false;
            }

            if (end == null) {

                return false;
            }

            var request = {
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode.DRIVING
            };
            directionsService.route(request, function (result, status) {

                console.log(request, status);

                if (status == 'OK') {
                    directionsRenderer.setDirections(result);
                    console.log(result);
                    $("#calculated_distance").remove();

                    $("#map").parent().append(
                        `
            <div class="text-center" id="calculated_distance">
                ${result.routes[0].legs[0].distance.text}
            </div>
            `);

                    userdistance.value = (result.routes[0].legs[0].distance.value / 1000);

                } else {
                    alert("please enter different location");
                    userdistance.value = null;
                    return false;
                }
            });


        }

        //Auto address Search api
        //     var pickup_point = places({
        //     appId: "pl85FXT44F74",
        //     apiKey: "322ee8a2ecb5c80593e382020d8f9342",
        //     container: document.querySelector('#pickup_point')
        //   });

        var input = document.getElementById('pickup_point');
        var input2 = document.getElementById('drop_off');

        input.addEventListener("keydown", function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        })

        input2.addEventListener("keydown", function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        })


        $('#drop_off').focusout(function () {
            if ($(this).val() == "") {

                drop_off_cords = "";
                $(this).addClass("is-invalid");
                $(this).removeClass('is-valid');
                return false;
            } else {
                $(this).removeClass("is-invalid");
                $(this).addClass("is-valid");
                $("#err-drop_off").empty();
            }
            calcRoute(pickup_point_cords, drop_off_cords);
        });


        $('#pickup_point').focusout(function () {
            var val = $(this).val();

            if (val == "") {

                drop_off_cords = "";
                $(this).addClass("is-invalid");
                $(this).removeClass('is-valid');
            } else {
                $(this).removeClass("is-invalid");
                $(this).addClass("is-valid");
                $("#err-pickup_point").empty();
            }
        });

    </script>
    <script>
let numberInput = document.getElementById("inpnumber");

function increment() {
  numberInput.value++;
}

function decrement() {
  if (numberInput.value > 0) {
    numberInput.value--;
  }
}     

$("#tabHourlyBtn").click(function(){
    $("#booking_type").val("hourly");
})

$("#tabP2Pbtn").click(function(){
    $("#booking_type").val("point-2point");
})
    </script>
</body>

</html>
