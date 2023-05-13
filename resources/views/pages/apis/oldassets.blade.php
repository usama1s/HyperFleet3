<!-- @include('assets.css')
    <link rel="stylesheet" type="text/css" href="https://js.api.here.com/v3/3.1/mapsjs-ui.css" />

<style>
        .ap-input-icon {
            display: none;
        }

        .input-group input,
        textarea {
            border: 1px solid #eeeeee;
            box-sizing: border-box;
            margin: 0;
            outline: none;
            padding: 10px;
        }

        input[type="button"] {
            -webkit-appearance: button;
            cursor: pointer;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        .input-group {
            clear: both;
            margin: 15px 0;
            position: relative;
        }

        .input-group input[type='button'] {
            background-color: #d6d6d6;
            min-width: 38px;
            width: auto;
            transition: all 300ms ease;
        }

        .input-group .button-minus,
        .input-group .button-plus {
            font-weight: bold;
            height: 38px;
            padding: 0;
            width: 38px;
            position: relative;
        }

        .input-group .button-minus:hover,
        .input-group .button-plus:hover {
            background-color: #a09999;
        }

        .input-group .input-field,
        .input-field-bags {
            position: relative;
            height: 38px;
            left: -4px;
            text-align: center;
            width: 62px;
            display: inline-block;
            font-size: 16px;
            top: 1.2px;
            margin: 0 0 5px;
            resize: vertical;
        }

        .button-plus {
            left: -13px;
        }

        .input-group input[type="number"] {
            -moz-appearance: textfield;
            -webkit-appearance: none;
        }

        #map {
            height: 400px;
            width: 100%;
        }

        #booking_type_btn_wappers {
            display: flex;
        }

        .booking_type_btn {
            flex: 1;
        }

        body {
            height: auto;
            background: url(https://img.freepik.com/premium-photo/portrait-black-young-woman-with-suitcase-hailing-taxi-city-street-smiling_236854-44375.jpg?w=740);
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            position: relative;
        }

        body::after {
            content: "";
            position: absolute;
            margin-top: -5px !important;
            left: 0px;
            top:0px;
            display: block;
            width: 100%;
            height: 100vh;
            background: #00000082;
            z-index: -1;
        }
    </style> -->



    <div class="container" style="width:1000px">
        <div class="mb-1 mt-2" id="booking_type_btn_wappers">
            <button class="btn btn-info booking_type_btn" id="p2p-btn">Point-to-Point</button>
            <button class="btn btn-info booking_type_btn" id="hourly-btn">Hourly Booking</button>
        </div>
        <form action="{{ route('vehicle.choose') }}" method="GET">
            @csrf
            <input type="hidden" id="booking_type" name="booking_type" value="point-2-point">

            <input type="hidden" id="pickup_cords" name="pickup_cords" value="">
            <input type="hidden" id="dropoff_cords" name="dropoff_cords" value="">
            <input type="hidden" id="userdistance" name="userdistance" value="">

            <div class="card card-default" style="background: #efefefa6;">

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Pickup Point*</label>
                            <input class="form-control @error('pickup_point') is-invalid @enderror" type="text"
                                name="pickup_point" value="{{ old('pickup_point') }}" id="pickup_point"
                                placeholder="Pickup Point">
                            @error('pickup_point')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror

                            <div id="pointtopoint">
                                <label>Drop off*</label>
                                <input class="form-control @error('drop_off') is-invalid @enderror" type="text"
                                    name="drop_off" value="{{ old('drop_off') }}" id="drop_off" placeholder="Drop off">
                                @error('drop_off')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>


                            <div id="hourlybooking">
                                <label>Duration*</label>
                                <select name="hourlybooking" id="hours"
                                    class="form-control @error('hourlybooking') is-invalid @enderror">
                                    <option value="">Select Hours</option>

                                    @for ($i = 1; $i <= 24; $i++)
                                        @if (old('hourlybooking') == $i)
                                            <option value="{{ $i }}" selected>{{ $i }}
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

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Pickup Date*</label>
                                    <input
                                        class="form-control datetimepicker-input @error('pickup_date') is-invalid @enderror"
                                        type="text" name="pickup_date" id="pickup_date" placeholder="Pickup Date"
                                        data-toggle="datetimepicker" data-target="#pickup_date" autocomplete="off">
                                    @error('pickup_date')
                                        <div class="invalid-feedback" style="display:block;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Pickup Time*</label>
                                    <input
                                        class="form-control datetimepicker-input @error('pickup_time') is-invalid @enderror"
                                        type="text" name="pickup_time" id="pickup_time" placeholder="Pickup Time"
                                        data-toggle="datetimepicker" data-target="#pickup_time" autocomplete="off">
                                    @error('pickup_time')
                                        <div class="invalid-feedback" style="display:block;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Voucher Code (Optional)</label>
                                    <input class="form-control" type="text" name="voucher_code" id="voucher_code"
                                        placeholder="Voucher Code (Optional)">
                                    <div class="voucher_code_error text-danger" style="font-size: smaller;"></div>
                                    <div class="voucher_code_success text-success" style="font-size: smaller;"></div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="input-group">
                                    <div class="form-group col-md-6">

                                        <label for="">Number of passengers <br><span
                                                class="text-center">(Optional)</span></label> <br>

                                        <input type="button" class="button-minus" value="-" name=""
                                            id="btn-minus" data-field="quantity">

                                        <input type="text" placeholder="" class="input-field" value=""
                                            name="no_of_passengers">

                                        <input type="button" value="+" id="btn-plus" class="button-plus"
                                            data-field="num_passengers">
                                        @error('no_of_passengers')
                                            <div class="invalid-feedback" style="display:block;" role="alert">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="">Number of bags <br>(Optional)</label> <br>

                                        <input type="button" class="button-minus" value="-" name=""
                                            id="btn-minus-bags">

                                        <input type="text" placeholder="" value="" class="input-field-bags"
                                            name="no_of_bags">

                                        <input type="button" value="+" id="btn-plus-bags" class="button-plus"
                                            data-field="num_passengers">
                                        @error('no_of_bags')
                                            <div class="invalid-feedback" style="display:block;" role="alert">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="form-group col-md-6">

                            <div id="map"></div>

                        </div>

                        <div class="col-md-12">

                            <input class="btn btn-success float-right" type="submit" value="Start booking">

                        </div>
                    </div>
                    {{-- row end --}}

                    {{-- row end --}}


                </div>
        </form>

    </div>


    @include('assets.js')


    <script async defer type="text/javascript"
        src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initMap&language=en">
    </script>