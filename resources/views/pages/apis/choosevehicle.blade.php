@php
use App\Models\VehicleClass;
use App\Models\Voucher;
use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('/public/css/simplepicker.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css"
        integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
    <title>Choose Vehicle</title>
    <style>
        /* width */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 5px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #a78bfa;
            border-radius: 5px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #6d28d9;
        }

        @media (max-width: 1024px) {
            .toggleSidebar {
                width: 0;
                padding: 0 !important;
                overflow: hidden;
            }
        }
        .sidebox .fas{
    width:20px;
    font-size: 16px;
}
    </style>
</head>

<body>

    <body class="bg-gray-200 h-full w-full min-w-[430px] font-sans">
        <!-- topbar -->
        <div class="bg-white p-4 hidden xl:block">
            <div class="grid grid-cols-4 md:gap-10 gap-80 px-10 overflow-x-auto whitespace-nowrap">
                <div class="flex gap-5 items-center max-w-[280px]">
                    <i class="fas fa-clock fa-lg" style="color: #172554; font-size: xx-large"></i>
                    <div>
                        <h4 class="font-semibold">Free Cancellation</h4>
                        <p>upto 24 hours before pickup</p>
                    </div>
                </div>
                <div class="flex gap-5 items-center max-w-[286px]">
                    <i class="fas fa-plane-departure" style="color: #172554; font-size: xx-large"></i>
                    <div>
                        <h4 class="font-semibold">Flight Tracking</h4>
                        <p>Driver will monitor your flight</p>
                    </div>
                </div>
                <div class="flex gap-5 items-center max-w-[286px]">
                    <i class="fas fa-users" style="color: #172554; font-size: xx-large"></i>
                    <div>
                        <h4 class="font-semibold">Licensed Chauffeurs</h4>
                        <p>Maximum Confort and safety</p>
                    </div>
                </div>
                <div class="flex gap-5 items-center max-w-[286px]">
                    <i class="fas fa-clock fa-lg" style="color: #172554; font-size: xx-large"></i>
                    <div>
                        <h4 class="font-semibold">24/7 Support</h4>
                        <p>Dedicated customer service</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 xl:grid-cols-4 gap-10">
            <i class="fas fa-arrow-right bg-white p-2 rounded-[50%] absolute lg:hidden top-3" id="barsIcon"></i>
            <div class="w-full py-6 col-span-2 lg:col-span-2 xl:col-span-3">
                <!-- alert -->
                <div class="ml-10 mr-10 lg:mr-0 mt-4 bg-white p-1 rounded-lg">
                    <div class="bg-violet-200 px-6 py-2">
                        <h1 class="font-semibold xl:text-4xl text-2xl text-blue-950">
                            Free Cancellation 24h
                        </h1>
                        <p class="text-gray-500 text-base py-2 font-semibold">
                            Book today, lock the price. You can cancel for free with in the
                            <span class="font-bold text-gray-800">29 March 2023</span> and get
                            a full refund of the transfer
                        </p>
                    </div>
                </div>
                <!-- cards -->
                @foreach ($vehicles as $vehicle)

                <form action="{{route('customer.info')}}" method="GET">
                    @csrf

                    <div class="multistep-tab ml-10 mr-10 lg:mr-0 mt-8">
                        <!-- card1 -->
                        <div class="bg-white w-full grid grid-cols-1 md:grid-cols-4 rounded-lg p-2">
                            <div class="lg:border-r-2 px-4 py-3">
                                @php


                                $img_url = asset('public/assets/vehicles')."/".$vehicle->image;

                                $headers=get_headers($img_url);

                                if(stripos($headers[0],"404 Not Found")){
                                $img_url = asset('public/images/img-placeholder.jpg');
                                }

                                @endphp
                                <img src="{{ $img_url }}" alt="" class="h-44 object-contain" />
                                <span class="text-gray-400">{{ $vehicle->vehicleClass->name }}</span>
                                <h5 class="text-xl font-bold text-blue-950">Private Transfer</h5>
                            </div>
                            <div class="lg:border-r-2 px-4 py-3 col-span-2">
                                <h2 class="text-2xl font-bold text-blue-950">
                                    {{ $vehicle->manufacturer }} {{ $vehicle->car_model }}
                                    <span
                                        class="bg-violet-400 px-1 rounded-lg text-base">{{$vehicle->supplier->company_name}}</span>
                                </h2>
                                <div class="flex gap-6 text-xl font-semibold text-gray-600 my-3">
                                    <h4 class="text-xl">Up to{{ $vehicle->seats }} Passenger</h4>
                                    <h4 class="text-xl">{{ $vehicle->luggage }} Medium suitcase</h4>
                                </div>
                                <p class="mt-4 border-b-2 border-dotted border-b-violet-400 w-fit">
                                    {{$vehicle->pricing['title']}} ( {{$vehicle->calculated_price['type_of_pricing']}} )
                                </p>
                                {{-- <p
                                class="mt-1.5 border-b-2 border-dotted border-b-violet-400 w-fit" >
                                Free Waiting time
                                    </p>
                                    <p
                                        class="mt-1.5 border-b-2 border-dotted border-b-violet-400 w-fit"
                                    >
                                        Door-to-door
                                    </p>
                                    <p
                                        class="mt-1.5 border-b-2 border-dotted border-b-violet-400 w-fit"
                                    >
                                        Porter service
                                </p> --}}
                            </div>
                            @php
                            $calculated_tax = $vehicle->calculated_price['price'] * (Config('vat')/100);
                            $grand_total = $vehicle->calculated_price['price'] + $calculated_tax;
                            $sumdisplay = number_format((float)$grand_total, 2, '.', '');
                            @endphp
                            <div class="flex flex-col gap-2 lg:items-center justify-between pl-4 pr-6 pt-3">
                                <div class='flex flex-col items-end'>
                                    <p class="font-bold text-blue-950">Total one-way price</p>
                                    <h1 class="text-3xl text-gray-700">{{Config('currency-symbol')}}{{$sumdisplay}}
                                    </h1>
                                    <h6>(all taxes included)</h6>

                                    {{-- <span class="text-violet-700 font-bold">Free Cancellation</span> --}}
                                    <span class="pt-0 text-gray-800 font-semibold">includes VAT, fees & tips</span>
                                </div>

                                <div><input type="hidden" name="id" value="{{ $vehicle->id }}" id="">
                                    <input type="hidden" name="vehicle_class_id"
                                        value="{{ encrypt($vehicle->vehicleClass->id) }}">
                                    <input type="hidden" name="calculated_price" value="{{ encrypt($grand_total) }}">
                                    <input type="hidden" name="supplier_id"
                                        value="{{ encrypt($vehicle->supplier_id) }}">
                                    <button
                                        class="mb-4  bg-violet-500 hover:bg-violet-600 px-6 py-3 text-white font-semibold rounded-md w-full">
                                        Book Now
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>

                @empty($vehicle)
                <h1> No offers found</h1>
                @endempty

                @endforeach
                

                {{-- <div class="multistep-tab">2</div>
                <div class="multistep-tab">3</div>
                <div class="">
                    <button type="button" id="prevBtn" onclick="nextPrev(-1)">
                        Previous
                    </button>
                    <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                </div> --}}
            </div>

            <!-- rightbar -->
            <div id="tooglesidebar"
                class="mr-2 lg:block lg:relative fixed  lg:bg-none pt-6 bg-gray-200 toggleSidebar lg:h-auto transition-all duration-200 top-0 px-2">
                <div class="lg:hidden w-full flex pr-5 pb-2 justify-end">
                    <i class="fas fa-times" id="toggleClose"></i>
                </div>
                <div class="overflow-y-auto lg:overflow-y-hidden h-screen lg:h-auto pb-6">
                    <div class="col-span-1 bg-white text-gray-400 px-6 py-4 my-4 rounded-md">
                        <div class="sidebox">
                            <div class="flex md:block flex-row-reverse justify-between items-center pb-4">
                                <h1 class="text-xl font-bold text-blue-900">Your Transfer</h1>
                            </div>
                            <hr />

                            <h5 class="text-lg font-semibold text-blue-900" style="margin-left:20px;">
                                outward journey
                            </h5>
                            <div class="mb-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-violet-500"></i>
                                    <p>{{ session('pickup_point') }}.</p>
                                </div>
                                {{-- <p>Barcelona, Spain</p> --}}
                            </div>
                            <div class="mb-4">
                                <div class="flex items-center gap-2">
                                    @if (!is_null(session('drop_off')))
                                    <i class="fas fa-map-marker-alt text-violet-500"></i>
                                    <p>{{ session('drop_off') }}.</p>
                                    @else
                                    <i class="fas fa-clock text-violet-500"></i>
                                    <p> {{ session('hourlybooking') }}
                                        {{ Str::plural('hour',session('hourlybooking') )}}</p>
                                    @endif
                                </div>
                                {{-- <p>Barcelona, Spain</p> --}}
                            </div>
                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-calendar-alt text-violet-500"></i>

                                <h2 class="mb-2">{{ session('pickup_date') }}</h2>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-clock text-violet-500"></i>
                                <h2 class="">{{ session('pickup_time') }}</h2>
                            </div>
                            <hr class="mt-4" />
                            <!-- <div class="flex flex-col my-4">
                                <span class="text-xs text-center w-full mb-1">Book smart! Add a return journey</span>
                                <a href="{{ route('embed') }}"><button
                                    class="bg-violet-500 hover:bg-violet-600 px-6 py-3 text-white font-semibold rounded-md">
                                    ADD A RETURN
                                </button></a>
                            </div> -->
                            <hr />
                            <div class="my-4 text-lg">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user-friends text-violet-500"></i>
                                    <p>
                                        @if ( session('no_of_passengers') )
                                        {{ session('no_of_passengers') }}
                                        {{ (session('no_of_passengers') > 1)?'Persons': 'Person' }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-violet-500"></i>
                                    <p>
                                        @if ( session('user_distance'))
                                        {{ session('user_distance') }} KM
                                        @endif

                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-violet-500"></i>
                                    <p>0h 23m</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-1 bg-white rounded-md">
                        @if (session('hourlybooking'))

                        <iframe width="300" height="400" frameborder="0" style="border:0; width:100%;"
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDi-ti0jL5rO_FIXUESeUoF4TJXqa6T2Ko&q={{ session('pickup_cords') }}"
                            allowfullscreen>
                        </iframe>

                        @else

                        <iframe width="300" height="400" frameborder="0" style="border:0; width:100%;"
                            src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyDi-ti0jL5rO_FIXUESeUoF4TJXqa6T2Ko&origin={{ session('pickup_cords') }}&destination={{ session('dropoff_cords') }}&mode=driving"
                            allowfullscreen>
                        </iframe>

                        @endif


                    </div>
                </div>
            </div>
        </div>
        <!-- topbar -->
        <div class="bg-white p-4 xl:hidden block">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-10 px-10">
                <div class="flex gap-5 items-center min-w-[278px]">
                    <i class="fas fa-clock fa-lg" style="color: #172554; font-size: xx-large"></i>
                    <div>
                        <h4 class="font-semibold">Free Cancellation</h4>
                        <p>upto 24 hours before pickup</p>
                    </div>
                </div>
                <div class="flex gap-5 items-center min-w-[278px]">
                    <i class="fas fa-plane-departure" style="color: #172554; font-size: xx-large"></i>
                    <div>
                        <h4 class="font-semibold">Flight Tracking</h4>
                        <p>Driver will monitor your flight</p>
                    </div>
                </div>
                <div class="flex gap-5 items-center min-w-[278px]">
                    <i class="fas fa-users" style="color: #172554; font-size: xx-large"></i>
                    <div>
                        <h4 class="font-semibold">Licensed Chauffeurs</h4>
                        <p>Maximum Confort and safety</p>
                    </div>
                </div>
                <div class="flex gap-5 items-center min-w-[278px]">
                    <i class="fas fa-clock fa-lg" style="color: #172554; font-size: xx-large"></i>
                    <div>
                        <h4 class="font-semibold">24/7 Support</h4>
                        <p>Dedicated customer service</p>
                    </div>
                </div>
            </div>
        </div>
    </body>

    @include('assets.js')
    <script src="{{ asset('/public/js/multiStep.js') }}"></script>
    <script>
        const bars = document.getElementById("barsIcon");
        const tooglesidemenu = document.getElementById("tooglesidebar");
        bars.addEventListener("click", function () {
            tooglesidemenu.classList.toggle("toggleSidebar");
        });

        const closebtn = document.getElementById("toggleClose");
        const tooglesidemenuclose = document.getElementById("tooglesidebar");
        closebtn.addEventListener("click", function () {
            tooglesidemenuclose.classList.toggle("toggleSidebar");
        });

    </script>
</body>

</html>
