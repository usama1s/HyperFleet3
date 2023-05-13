@php
use App\Models\VehicleClass;
use Illuminate\Support\Str;
use App\Models\Voucher;

$vehicle = new App\Models\Vehicle();

if (!auth()->user()) {
session(['login_ref_url' => url()->full()]);
}

@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Customer Info</title>


    <link rel="stylesheet" href="{{ asset('/public/css/simplepicker.css') }}">
    <script src="https://js.stripe.com/v3/"></script>
    @include('assets.css')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css"
        integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
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

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .multistep-tab {
        max-height: 0;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;


    }


    @keyframes mymove {
        0% {
            max-height: 0vh;
        }

        100% {
            max-height: 100vh;
        }
    }

    .multistep-tab.active {
        max-height: 100%;
        opacity: 1;
        animation: mymove 1.5s;
    }

    @media (max-width: 1024px) {
        .toggleSidebar {
            width: 0;
            padding: 0 !important;
            overflow: hidden;
        }
    }

    .current .bar-length div,
    .completed .bar-length div {
        background: #8b5cf6;
        width: 100%;
    }

    .completed .pg-item-box,
    .current .pg-item-box {
        background: #8b5cf6 !important;
    }

    .completed .pg-item-box span,
    .current .pg-item-box span {
        color: #fff !important;
    }

    .next .bar-length div {
        background: #8b5cf6;
        width: 33%;
    }

    .sidebox .fas {
        width: 20px;
        font-size: 16px;
    }
    </style>
</head>

<body class="bg-gray-200 h-full w-full min-w-[430px] font-sans grid lg:grid-cols-3 xl:grid-cols-4 gap-10">
    <i class="fas fa-arrow-right bg-white p-2 rounded-[50%] absolute lg:hidden top-3" id="barsIcon"></i>
    <main class="w-full py-6 col-span-2 lg:col-span-2 xl:col-span-3">
        <form action="{{ route('confirm.booking') }}" method="POST">
            @csrf

            <!-- progress bar -->
            <div class="flex my-4 progress-bars">
                <div class="w-1/4 bar completed">
                    <div class="relative mb-2">
                        <div
                            class="mx-auto flex pg-item-box h-10 w-10 items-center rounded-full bg-violet-500 text-lg text-white">
                            <span class="w-full text-center text-white"> 1 </span>
                        </div>
                    </div>
                    <div class="text-center text-xs md:text-base">Vehicle</div>
                </div>

                <div class="w-1/4 bar current">
                    <div class="relative mb-2">
                        <div class="align-center absolute flex content-center items-center align-middle" style="
                  width: calc(100% - 2.5rem - 1rem);
                  top: 50%;
                  transform: translate(-50%, -50%);
                ">
                            <div
                                class="bar-length align-center w-full flex-1 items-center pg-item rounded bg-gray-300 align-middle">
                                <div class="w-0 rounded bg-violet-300 py-1 "></div>
                            </div>
                        </div>

                        <div
                            class="mx-auto flex h-10 pg-item-box w-10 items-center rounded-full bg-violet-500 text-lg text-white">
                            <span class="w-full text-center text-white">2</span>
                        </div>
                    </div>

                    <div class="text-center text-xs md:text-base">EXTRAS</div>
                </div>

                <div class="w-1/4 bar next">
                    <div class="relative mb-2">
                        <div class="align-center absolute flex content-center items-center align-middle" style="
                  width: calc(100% - 2.5rem - 1rem);
                  top: 50%;
                  transform: translate(-50%, -50%);
                ">
                            <div
                                class="bar-length align-center w-full flex-1 items-center pg-item rounded bg-gray-300 align-middle">
                                <div class="w-0 rounded bg-violet-300 py-1 "></div>
                            </div>
                        </div>

                        <div
                            class="mx-auto flex h-10 w-10 pg-item-box items-center rounded-full border-2 border-gray-200 bg-white text-lg text-white">
                            <span class="w-full text-center text-gray-600"> 3 </span>
                        </div>
                    </div>

                    <div class="text-center text-xs md:text-base">DETAILS</div>
                </div>

                <div class="w-1/4 bar">
                    <div class="relative mb-2">
                        <div class="align-center absolute flex content-center items-center align-middle" style="
                  width: calc(100% - 2.5rem - 1rem);
                  top: 50%;
                  transform: translate(-50%, -50%);
                ">
                            <div
                                class="bar-length align-center w-full flex-1 items-center pg-item rounded bg-gray-300 align-middle">
                                <div class="w-0 rounded bg-violet-300 py-1 "></div>
                            </div>
                        </div>

                        <div
                            class="mx-auto flex h-10 w-10 pg-item-box items-center rounded-full border-2 border-gray-200 bg-white text-lg text-white">
                            <span class="w-full text-center text-gray-600"> 4 </span>
                        </div>
                    </div>

                    <div class="text-center text-xs md:text-base">PAYMENT</div>
                </div>
            </div>
            <!-- alert -->
            <div class="ml-10 mr-10 lg:mr-0 mt-8 bg-white p-1 rounded-lg">
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
            <!-- vehicle card -->
            <div class=" ml-10 mr-10 lg:mr-0 mt-8">
                <div class="flex flex-col gap-8">
                    <div class="bg-white w-full grid md:grid-cols-4 rounded-lg p-2">
                        <div class="lg:border-r-2 px-4 py-3">
                            @php

                            $img_url = asset('public/assets/vehicles') . '/' . $vehicles->image;

                            $headers = get_headers($img_url);

                            if (stripos($headers[0], '404 Not Found')) {
                            $img_url = asset('public/images/img-placeholder.jpg');
                            }

                            @endphp
                            <img src="{{ $img_url }}" alt="" class="h-44 object-contain" />
                            <span class="text-gray-400">{{ $vehicles->vehicleClass->name }}</span>

                            <h5 class="text-xl font-bold text-blue-950">Private Transfer</h5>
                        </div>
                        <div class="lg:border-r-2 px-4 py-3 col-span-2">
                            <h2 class="text-2xl font-bold text-blue-950">
                                {{ $vehicles->manufacturer }} {{ $vehicles->car_model }}
                                <span
                                    class="bg-violet-400 px-1 rounded-lg text-base">{{ $vehicles->supplier->company_name }}</span>
                            </h2>
                            <div class="flex gap-6 text-xl font-semibold text-gray-600 my-3">
                                <h4 class="text-lg">Up to{{ $vehicles->seats }} Passenger</h4>
                                <h4 class="text-lg">{{ $vehicles->luggage }} Medium suitcase</h4>
                            </div>
                            <p class="mt-4 border-b-2 border-dotted border-b-violet-400 w-fit">
                                {{ $vehicles->price }}
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
                        <div class="flex flex-col gap-2 lg:items-end pl-4 pr-6 pt-3">
                            <p class="font-bold text-blue-950">Total one-way price</p>
                            <h1 class="text-xl text-gray-700">{{ Config('currency-symbol') }}
                                {{ session('grand_total') }}
                            </h1>
                            <h6>&nbsp; (all taxes included)</h6>

                            {{-- <span class="text-violet-700 font-bold">Free Cancellation</span> --}}
                            <span class="pt-0 text-gray-800 font-semibold">includes VAT, fees & tips</span>
                            <p><br /></p>
                            <input type="hidden" name="vehicle_class_id"
                                value="{{ encrypt($vehicles->vehicleClass->id) }}">
                            <input type="hidden" name="supplier_id" value="{{ encrypt($vehicles->supplier_id) }}">

                        </div>
                    </div>
                </div>
            </div>
            <!-- Extrans and Notes -->
            <div class="ml-10 mr-10 lg:mr-0 mt-8 bg-white px-6 py-4 rounded-lg">

                <h1 class="text-2xl font-blue-900 font-semibold">
                    Extras and notes
                </h1>
                <div class="mt-6 multistep-tab active">
                    <span class="text-md font-semibold">Flight/train number</span>

                    <input type="search" name="tickets"
                        class="bg-purple-white w-full rounded border-2 border-gray-300 p-3 shadow mt-1"
                        placeholder="Example: LH1868" />

                    <p class="text-gray-500 text-sm pb-6">
                        Please provide a flight number(The driver will monitor you flight)
                    </p>

                    <div class="border-2 border-b-0 border-gray-300 grid xl:grid-cols-7 gap-4 px-6 py-4 rounded-t-lg">
                        <div class="col-span-1"></div>
                        <div class="col-span-4">
                            <h4 class="text-lg text-gray-600 font-semibold flex gap-4">
                                Child seat
                                <p
                                    class="p-0.5 text-sm border-green-400 border-2 rounded-md border-solid text-green-400">
                                    FREE
                                </p>
                            </h4>
                            <p class="text-sm min-w-max">
                                Suitable for toddlers weighing 0-18kg (approx 0 to 4year).
                            </p>
                        </div>
                        <div class="col-span-2 relative mt-1 flex h-10 flex-row rounded-lg bg-transparent">
                            <button type="button" data-action="decrement"
                                class="h-full w-10 cursor-pointer rounded-l bg-gray-300 text-gray-600 outline-none hover:bg-gray-400 hover:text-gray-700">
                                <span class="m-auto text-2xl font-thin">−</span>
                            </button>
                            <input type="number"
                                class="max-w-[98px] text-md md:text-base cursor-default flex items-center bg-gray-300 text-center font-semibold text-gray-700 outline-none hover:text-black focus:text-black focus:outline-none"
                                name="child_input_number" value="0" />
                            <button type="button" data-action="increment"
                                class="h-full w-10 cursor-pointer rounded-r bg-gray-300 text-gray-600 hover:bg-gray-400 hover:text-gray-700">
                                <span class="m-auto text-2xl font-thin">+</span>
                            </button>
                        </div>
                    </div>
                    <div class="border-2 border-b-0 border-gray-300 grid xl:grid-cols-7 gap-4 px-6 py-4">
                        <div class="col-span-1"></div>
                        <div class="col-span-4">
                            <h4 class="text-lg text-gray-600 font-semibold flex gap-4">
                                Booster Seats
                                <p
                                    class="p-0.5 text-sm border-green-400 border-2 rounded-md border-solid text-green-400">
                                    FREE
                                </p>
                            </h4>
                            <p class="text-sm min-w-max">
                                Suitable for toddlers weighing 0-18kg (approx 0 to 4year).
                            </p>
                        </div>
                        <div class="col-span-2 relative mt-1 flex h-10 flex-row rounded-lg bg-transparent">
                            <button type="button" data-action="decrement2"
                                class="h-full w-10 cursor-pointer rounded-l bg-gray-300 text-gray-600 outline-none hover:bg-gray-400 hover:text-gray-700">
                                <span class="m-auto text-2xl font-thin">−</span>
                            </button>
                            <input type="number"
                                class="max-w-[98px] text-md md:text-base cursor-default flex items-center bg-gray-300 text-center font-semibold text-gray-700 outline-none hover:text-black focus:text-black focus:outline-none"
                                name="booster_input_number" value="0" />
                            <button type="button" data-action="increment2"
                                class="h-full w-10 cursor-pointer rounded-r bg-gray-300 text-gray-600 hover:bg-gray-400 hover:text-gray-700">
                                <span class="m-auto text-2xl font-thin">+</span>
                            </button>
                        </div>
                    </div>
                    <div class="border-2 border-gray-300 flex gap-6 text-sm py-4 px-6 rounded-b-lg">
                        <span></span>
                        <p>
                            Please write into the "notes for the chauffeur" the age and
                            weight if you child/s in order to provide an appropriate device.
                        </p>
                    </div>

                    <p class="text-md font-semibold mt-8 mb-2">Notes for the chauffeur</p>

                    <textarea class="bg-purple-white w-full rounded border-2 border-gray-300 p-3 shadow"
                        name="special_instruction" placeholder="Additional Instructions"></textarea>
                    <hr class="my-5" />
                    <div class="flex justify-end">
                        <button type="button"
                            class="continue-btn bg-violet-500 hover:bg-violet-600 px-6 py-3 text-white font-semibold rounded-md">
                            CONTINUE
                        </button>
                    </div>
                </div>

            </div>
            <!-- Lead Passenger -->
            <div class="ml-10 mr-10 lg:mr-0 mt-8 bg-white px-6 py-4 rounded-lg">
                <h1 class="text-2xl font-blue-900 font-semibold">
                    Lead Passengerss
                </h1>
                <div class="mt-6 multistep-tab mt-6 rounded-md">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-1">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First
                                name*</label>
                            <input type="text" id="fname" name="lead_first_name" required
                                class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                        </div>

                        <div class="col-span-1">
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Last
                                Name*</label>
                            <input type="text" id="lname" name="lead_lastname" required
                                class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mt-4">Email
                            Address</label>
                        <input type="email" name="lead_email"
                            class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                    </div>
                    <p class="text-gray-500 text-sm pb-6 pt-2">
                        We'll send you booking voucher here
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-1">
                            <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile phone
                                number*</label>
                            <input type="number" name="lead_mobile" required
                                class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                        </div>
                    </div>
                    <div class="mt-6 bg-violet-200 px-6 py-2">
                        <h1 class="text-sm font-bold text-blue-950 xl:text-lg">
                            Free SMS/text-messgae updates
                        </h1>
                        <p class="py-1 text-sm font-semibold text-gray-500">
                            Book today, lock the price. You can cancel for free with in the
                            <span class="font-bold text-gray-800">29 March 2023</span> and
                            get a full refund of the transfer
                        </p>
                    </div>
                    <hr class="mt-10 mb-5" />
                    <div>
                        <label for="meet" class="block text-sm font-medium text-gray-700 mt-4">Meet &
                            Greet
                        </label>
                        <input type="text" name="lead_meet"
                            class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                    </div>
                    <p class="text-gray-500 text-sm pb-6 pt-2">
                        Meeting with a name sign, Enter the name you want written on the
                        sign
                    </p>
                    <div class="flex justify-end">
                        <button type="button"
                            class="continue-btn bg-violet-500 hover:bg-violet-600 px-6 py-3 text-white font-semibold rounded-md">
                            CONTINUE
                        </button>
                    </div>
                </div>
            </div>
            <!-- Payment -->
            <div class="ml-10 mr-10 lg:mr-0 mt-8 bg-white px-6 py-4 rounded-lg">
                <button class="text-2xl font-blue-900 font-semibold">Payment</button>
                <div class="multistep-tab mt-6 rounded-md my-div hide-div">

                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-1">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">Do you have any
                                discount code</label>
                            <input type="text" id="fnamecopy" name="discount_code"
                                class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                        </div>
                    </div>



                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-1">
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                            <input type="text" id="fnamecopy" name="payment_first_name" required
                                class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                        </div>

                        <div class="col-span-1">
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name *</label>
                            <input type="text" id="lnamecopy" name="payment_lastname" required
                                class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                        </div>
                    </div>
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 mt-4">Company</label>
                        <input type="text" name="payment_company"
                            class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                    </div>
                    <div>
                        <label for="payment_email" class="block text-sm font-medium text-gray-700 mt-4">Address *</label>
                        <input type="text" name="payment_email" required
                            class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                    </div>
                    <p class="text-gray-500 text-sm pb-6 pt-2">
                        We'll send you booking voucher here
                    </p>
                    <div class="grid grid-cols-3 gap-6">
                        <div class="col-span-1">
                            <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                            <input type="text" name="payment_city" required
                                class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                        </div>

                        <div class="col-span-1">
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">ZIP/ Postal code
                                *</label>
                            <input type="text" name="payment_postal_code" required
                                class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow" />
                        </div>
                        <div class="col-span-1">
                            <label for="country" class="block text-sm font-medium text-gray-700">Country *</label>

                            <select name="payment_country" required
                                class="bg-purple-white mt-1 w-full rounded border-2 border-gray-300 p-3 shadow">
                                <option value="">Select Country</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="China">China</option>
                                <option value="Amarica">Amarica</option>
                                <option value="England">England</option>
                                <option value="India">India</option>
                                <option value="Dubai">Dubai</option>


                            </select>

                        </div>
                    </div>
                    <div class="mt-6 bg-violet-200 px-6 py-2">
                        <h1 class="text-sm font-bold text-blue-950 xl:text-lg">
                            Free SMS/text-messgae updates
                        </h1>
                        <p class="py-1 text-sm font-semibold text-gray-500">
                            Book today, lock the price. You can cancel for free with in the
                            <span class="font-bold text-gray-800">29 March 2023</span> and
                            get a full refund of the transfer
                        </p>
                    </div>
                    <hr class="mt-10 mb-5" />
                    <div class="form-group col-md-12">
                        <label for="payment meethod" class="block text-sm font-medium text-gray-700">Payment
                            Method</label> <br>

                        <label for="online payment" class="block text-sm font-medium text-gray-700">
                            <input type="radio" checked class="paymentRadio" name="payment_method"
                                value="online_payment"> <i class="fab fa-paypal"></i>
                            Online Payment
                        </label> <br>

                        <label for="cash" class="block text-sm font-medium text-gray-700"><input type="radio"
                                class="paymentRadio" name="payment_method" value="cash_payment" data-toggle="collapse"
                                href="#collapseExample" role="button" aria-expanded="false"
                                aria-controls="collapseExample">
                            <i class="fas fa-money-bill-alt"></i>
                            Cash
                        </label>

                    </div>
                    <p class="text-gray-500 text-sm pb-6 pt-2">
                        Meeting with a name sign, Enter the name you want written on the
                        sign
                    </p>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="continue-btn bg-violet-500 hover:bg-violet-600 px-6 py-3 text-white font-semibold rounded-md">
                            Book Now
                        </button>
                    </div>
                </div>
        </form>
        <!-- </section> -->
    </main>
    <!-- sidebar -->

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
                            <i class="fas fa-map-marker-alt text-violet-500"></i>
                            <p>{{ session('drop_off') }}.</p>
                        </div>
                        {{-- <p>Barcelona, Spain</p> --}}
                    </div>
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fas fa-calendar-alt text-violet-500"></i>
                        <h2 class="mb-2">{{ date('D, d M Y', strtotime(session('pickup_date'))) }}</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-violet-500"></i>
                        <h2 class="">{{ session('pickup_time') }}</h2>
                    </div>
                    <hr class="mt-4" />
                    <!-- <div class="flex flex-col my-4">
                    <span class="text-xs text-center w-full mb-1">Book smart! Add a return journey</span>
                    <button class="bg-violet-500 hover:bg-violet-600 px-6 py-3 text-white font-semibold rounded-md">
                        ADD A RETURN
                    </button>
                </div> -->
                    <hr />
                    <div class="my-4 text-lg">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user-friends text-violet-500"></i>
                            <p>
                                @if (session('no_of_passengers'))
                                {{ session('no_of_passengers') }}
                                {{ session('no_of_passengers') > 1 ? 'Persons' : 'Person' }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-violet-500"></i>
                            <p>
                                @if (session('user_distance'))
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


    <!-- @include('pages.apis.login') -->






    @include('assets.js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {

        // $("#sign-in, #new-cust").on("change",function(){

        //     console.log($(this).val())

        //     if($(this).val()=="sign-in"){
        //         $("#customer-login").show()
        //         $("#payment-form").hide()
        //     }

        //     if($(this).val()=="new-cust"){

        //         $("#payment-form").show()
        //         $("#customer-login").hide()
        //     }

        // })

        $(".form-control").on('focus', function() {

            $(this).removeClass('is-invalid')
        })

        var paymentRadioVal = $('.paymentRadio').val()

        if (paymentRadioVal == 'online_payment') {

            $("#online_payment_view").slideDown()
        }

        $('.paymentRadio').change(function() {

            if ($(this).val() == 'cash_payment') {

                $("#online_payment_view").slideUp()

            } else if ($(this).val() == 'online_payment') {

                $("#online_payment_view").slideDown()
            }

        })
    })
    </script>

    <script src="/dist/multiStep.js"></script>

    <script>
    const bars = document.getElementById("barsIcon");
    const tooglesidemenu = document.getElementById("tooglesidebar");
    bars.addEventListener("click", function() {
        tooglesidemenu.classList.toggle("toggleSidebar");
    });

    const closebtn = document.getElementById("toggleClose");
    const tooglesidemenuclose = document.getElementById("tooglesidebar");
    closebtn.addEventListener("click", function() {
        tooglesidemenuclose.classList.toggle("toggleSidebar");
    });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        const tabs = $(".multistep-tab");
        let progressBars = $(".progress-bars .bar");

        let currentTab = 0;

        $(".continue-btn").click(function() {
            let inputs = tabs.eq(currentTab).find("input");
            let textArea = tabs.eq(currentTab).find("textarea");
            // let selects = tabs.eq(currentTab).find("select");
            let checkInputs = false;


            inputs.push(...textArea);
            // inputs.push(...selects);
            inputs.each(index => {
                if (inputs[index].hasAttribute("required") && inputs[index].value == "") {
                    checkInputs = true;
                }
            });
            if (checkInputs) {
                alert("Please fill all required fields.");
            } else {
                tabs.eq(currentTab).removeClass("active");
                progressBars.eq(currentTab + 1).removeClass("current");
                progressBars.eq(currentTab + 1).addClass("completed");
                currentTab++;
                if (currentTab >= tabs.length) {
                    return;
                }
                tabs.eq(currentTab).addClass("active");
                progressBars.eq(currentTab + 1).removeClass("next");
                progressBars.eq(currentTab + 1).addClass("current");
                if (currentTab >= tabs.length) {
                    return;
                }
                progressBars.eq(currentTab + 2).addClass("next");
            }
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        $('#same-as-billing').click(function() {
            if ($(this).is(':checked')) {
                $('#fnamecopy').val($('#fname').val());
                $('#lnamecopy').val($('#lname').val());
            } else {
                $('#fnamecopy').val('');
                $('#lnamecopy').val('');
            }
        });
    });
    </script>
    <script>
    const decrementBtn = document.querySelector("[data-action='decrement']");
    const decrementBtn2 = document.querySelector("[data-action='decrement2']");
    const incrementBtn = document.querySelector("[data-action='increment']");
    const incrementBtn2 = document.querySelector("[data-action='increment2']");
    const inputNumber = document.querySelector(
        "input[name='child_input_number']"
    );
    const inputNumber2 = document.querySelector(
        "input[name='booster_input_number']"
    );

    decrementBtn.addEventListener("click", () => {
        if (inputNumber.value > 0) {
            inputNumber.value = parseInt(inputNumber.value) - 1;
        }

    });
    decrementBtn2.addEventListener("click", () => {
        if (inputNumber2.value > 0) {
            inputNumber2.value = parseInt(inputNumber2.value) - 1;
        }

    });

    incrementBtn.addEventListener("click", () => {
        if (inputNumber.value < 99) {
            inputNumber.value = parseInt(inputNumber.value) + 1;
        }
    });
    incrementBtn2.addEventListener("click", () => {
        if (inputNumber2.value < 99) {
            inputNumber2.value = parseInt(inputNumber2.value) + 1;
        }
    });

    inputNumber.addEventListener("input", () => {
        if (inputNumber.value > 99) {
            inputNumber.value = 99;
        }
    });
    inputNumber2.addEventListener("input", () => {
        if (inputNumber2.value > 99) {
            inputNumber2.value = 99;
        }
    });
    </script>
</body>

</html>