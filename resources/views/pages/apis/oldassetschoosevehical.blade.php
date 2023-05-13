@include('assets.css')
<style>
      .list{
        list-style: none;
      }
      .list li{
          margin: 10px 0px;
      }
      .list .kk{
        border-left: 4px dotted #000;
        margin:0px !important;
        height: 36px;
        margin-left: 6px !important;        

      }
      .list li img{
          width: 35px;
          height: 35px;
      }
      .list li i{
        font-size: 20px;
         margin-right: 11px;
         width: 14px;
      }

      img{
        height: 250px;
        object-fit: cover;
      }

     </style>





<div class="container">
        {{-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
       
              <ul class="navbar-brand">
                <li> Car Class </li>
                <li>Payments </li>
                <li>Check out </li>
              </ul>
            </div>
          </nav> --}}
        <div class="row">
            <div class="col-md-3">
                <div class="heading">
                    <h2 class="text center">Ride Details</h2>
                </div>
                <ul class="list">
                    <li> <i class="fas fa-map-marker-alt"></i> {{ session('pickup_point') }}</li>
                    

                    @if (!is_null(session('drop_off')))
                    <li class="kk"></li>
                        <li>  <i class="fas fa-map-marker"></i>  {{ session('drop_off') }}</li>

                        {{-- <img src="{{ asset('public/img/direction.png')}}"> --}}
                    @else
                        <li><i class="far fa-clock"></i>  {{ session('hourlybooking') }} {{ Str::plural('hour',session('hourlybooking') )}} </li>
                    @endif
                    
                <li> <i class="fas fa-calendar-alt"></i>   {{ date("D, d M Y",strtotime(session('pickup_date'))) }} - {{ session('pickup_time') }}</li>
                    @if ( session('no_of_passengers') )
                        
                        <li><i class="nav-icon fas fa-user"></i> {{ session('no_of_passengers') }} {{ (session('no_of_passengers') > 1)?'Persons': 'Person' }}</li>
                    @endif

                    @if ( session('no_of_bags'))
                        <li><i class="nav-icon fas fa-briefcase"></i> {{ session('no_of_bags') }} Luggage</li>
                    @endif

                    @if ( session('user_distance'))
                        <li><i class="nav-icon fas fa-arrows-alt-h"></i> {{ session('user_distance') }} KM</li>
                    @endif                    

                    @if (Voucher::discount(session('voucher_code')))
                        
                        <li><i class="nav-icon fas fa-dollar-sign"></i> {{ Voucher::discount(session('voucher_code')) }} off</li>
                    
                    @endif                 
                                        
                </ul>
             
            </div>

            <div class="col-md-9">
                <div class="heading">
                    <h2 class="text center">Choose your vehicle</h2>
                </div>
                
                    @forelse ($vehicles as $vehicle)

                    
                    <form action="{{route('customer.info')}}" method="GET">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                @php
                                     
/*      //                       $img_url = asset('public/assets/vehicle-class')."/".$vehicle->vehicleClass->thumbnail; */                                
                                   $img_url = asset('public/assets/vehicles')."/".$vehicle->image;
                                   
                                    $headers=get_headers($img_url);  

                                    if(stripos($headers[0],"404 Not Found")){
                                        $img_url =  asset('public/images/img-placeholder.jpg');
                                    }
                                    
                                @endphp
                                
                                <img class="mb-3" src="{{ $img_url }}" width="100%">
                            </div>
                            <div class="col-md-7">
                                <h3>{{ $vehicle->manufacturer }} {{ $vehicle->car_model }} </h3><br/>
                                <p><b>Supplier:</b> {{$vehicle->supplier->company_name}}</p>
                                    <p> <b>Vehicle Class:</b> {{ $vehicle->vehicleClass->name }}</p>
                                    <p> <b>Pricing: </b>{{$vehicle->pricing['title']}} ( {{$vehicle->calculated_price['type_of_pricing']}} )</p>
                                <i class="nav-icon fas fa-user"> Seats: {{ $vehicle->seats }}</i> 
                                    <i class="ml-3 nav-icon fas fa-briefcase"> Luggage: {{ $vehicle->luggage}}</i><br/><br/>
                                    <div class="row float-right" style="padding:8px;">
                                        @php
                                            $calculated_tax = $vehicle->calculated_price['price'] * (Config('vat')/100);
                                            $grand_total = $vehicle->calculated_price['price'] + $calculated_tax;
                                            $sumdisplay = number_format((float)$grand_total, 2, '.', '');
                                        @endphp
                                        <h3>{{Config('currency-symbol')}}{{$sumdisplay}}</h3><h6>&nbsp; (all taxes included)</h6><p><br/></p>                                     
                                        <input type="hidden" name="vehicle_class_id" value="{{ encrypt($vehicle->vehicleClass->id) }}">
                                        <input type="hidden" name="calculated_price" value="{{ encrypt($grand_total) }}">
                                        <input type="hidden" name="supplier_id" value="{{ encrypt($vehicle->supplier_id) }}">
                                        <button type="submit" class="btn btn-success ml-3 mb-3">Book Now</button>
                                    </div>
                                    <br>
                            </div>
                        </div>
                    </form>
                    <hr>
                    @empty
                    <h1> No offers found</h1>
                    @endforelse                
            </div>
            
        </div>
    </div>
    {{-- {{ $vehicleClasses->appends($request->all())->links() }} --}}