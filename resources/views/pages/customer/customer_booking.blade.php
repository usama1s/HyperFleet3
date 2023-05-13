@php
use Illuminate\Support\Str;
use App\Models\Driver;
$d = new Driver;
use App\Models\Supplier;
use App\Models\Voucher;
use App\Models\Booking;

$supplier = new Supplier;
use App\Models\VehicleClass;
$user = Auth::user();
use App\Models\Vehicle;
$vehicle = new Vehicle;


$title= "{$name} Bookings";

session(['booking_ref_page' =>  url()->full() ]);
@endphp
@extends('layouts.app')

@section('title', $title)

@section('breadcrumb')

<div class="page_sub_menu">
    @can('booking-create')
    <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    <a href="{{url('customers')}}" class="btn btn-sm btn-success">Back</a>
    @endcan
</div>
@endsection

@section('content')

<br>

<form action="{{ route("bookings.bulkdestroy") }}" method="POST" id="record-form">
    @csrf
<table id="manage_bookings" style="width:100% background:#fff; display:none;" class="table table-hover display responsive">
    <thead>
        <tr>
     
            <th id="thcheck" data-orderable="false" data-priority="1"><input type="checkbox" id="all-checkbox-seleted-otp"></th>                
            <th>REF #</th> 
            <th data-priority="2">Pickup Date</th>
            <th style="width: 250px;" data-priority="3">Pickup Time</th>
            <th style="width: 250px;" data-priority="4">Pickup Address</th>
            <th data-priority="5">Drop-off/Duration</th>
            <th data-priority="7">Customer Name</th>
            <th data-priority="8">Customer Contact</th>
            <th>No. of Adults</th>
            <th>Bags</th>
            <th>Vehicle Class</th>
            <th>Price</th>
            <th>Customer email</th>
            <th data-priority="10">Vehicle</th>
            <th data-priority="9">Driver</th>
            
            @if ($user->role==1)
                <th data-priority="11">source</th>
            @endif
            
            <th data-priority="6">Status</th>

            <noscript>
                <th>Actions</th>
            </noscript>

        </tr>
    </thead>
    <tbody>

        @php
        $sno =1;
        @endphp

        @foreach ($bookings as $booking)

        @php

            if(@$_GET['new_booking_id'] == $booking->id){
                $highlight = "active";
            }else{
                $highlight = "";
            }

            if ($booking->pickup_date < date("Y-m-d") && ($booking->status == "ready" || $booking->status == "open" ||$booking->status == "expired" )){
                $danger = 'bg-danger';
                $is_active = false;
                
                if($booking->status != 'expired'){
                    $booking->status = 'expired';
                    $booking->save();
                }
                   
            }
            else{
                $is_active= true;
                $danger = "";
            }
        @endphp
            <tr id="booking_id_{{$booking->id}}" class="{{ $highlight }}" >
             
                <td class="details-control"> 
                    <input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $booking->id }}">
                    <i class="fas fa-arrow-down"></i>

                </td>
                <td>{{$booking->id}}</td>
                <td>{{ date("d-m-Y", strtotime($booking->pickup_date)) }}</td>
                <td>{{ date("g:i a", strtotime($booking->pickup_time)) }}
                    <span class="action_wapper2">

                        @can('booking-view')
                        <a class="text-info view_booking mr-2" id="{{$booking->id}}" href="#aboutModal"  data-placement="top"><i
                            title="view" data-toggle="tooltip" class="fa fa-eye"></i></a> 
                        @endcan 
                        
                        @can('booking-edit')
                        <a class="text-success mr-2" href="{{route('bookings.edit',$booking->id)}}" ><i
                            class="fa fa-edit" title="edit" data-toggle="tooltip" data-placement="top"></i></a> 
                        @endcan

                        @can('booking-delete')
                     
                            <a class="text-danger"  onclick="(function(e){e.preventDefault();record_delete(e)})(event)" href="{{route('destroy',$booking->id)}}" ><i
                                class="fa fa-trash" title="delete" data-toggle="tooltip" data-placement="top"></i></a>
                                
                         
                        @endcan
                    </span>
                </td>
                <td> 
                    <span  class="my-popover"  data-container="body" data-toggle="popover" data-placement="top" data-content="{{ $booking->pickup_point }}">
                    {!! Str::limit(strip_tags(html_entity_decode($booking->pickup_point)),30 , '...') !!}
                    </span>
                </td>
                <td>
                <span class="my-popover"  data-container="body" data-toggle="popover" data-placement="top" data-content="{{ $booking->drop_off }}">
                    
                        {!! Str::limit(strip_tags(html_entity_decode($booking->drop_off)),30 , '...') !!}
                </span>
                    @if(!is_null($booking->duration))
                    {{ $booking->duration}} {{ Str::plural('hour',$booking->duration )}}
                    @endif
                </td>

                <td>{{ $booking->first_name}} {{ $booking->last_name}} </td>
                <td>{{ $booking->contact_no}}</td>
                <td> 
                    @if (!is_null($booking->no_of_adults))
                    {{ $booking->no_of_adults}}
                    @else 
                        NA
                    @endif
                </td>
                <td>
                    @if (!is_null($booking->no_of_bags))
                    {{ $booking->no_of_bags}}
                    @else 
                        NA
                    @endif
        
                </td>

                <td>{{ VehicleClass::getById($booking->v_class) }} </td>

            
                                            
            <td>
              
                {{Config('currency-symbol')}} {{ $booking->grand_price }}
      
            </td> 
              
            <td>{{ $booking->email}}</td>

            <td>
            
                @php    
                    if(!is_null($booking->vehicle_id))     {
                        $vehicle_id = $booking->vehicle_id;
                        $v = $vehicle->vdata($vehicle_id);
                        $vehicle_preview_class = "vehicle_preview";
                    }else{
                        $vehicle_id = null;
                        $vehicle_preview_class = "";
                    
                    }          

                @endphp
                                                
                                   

                @if (!is_null($vehicle_id))
                <span class="text-info {{ $vehicle_preview_class }}" car_id="{{ $vehicle_id }}"> {{ $vehicle->getProperty($vehicle_id,'manufacturer')}} </span>
                
                <div class="vehicle_preview_{{$vehicle_id}}" style="display:none">
                
                    <div class="vehicle_card">
                        <img src="{{ asset('public/assets/vehicles') }}/{{$v['image']}}" style="width:100%">
                        <div class="vehicle_meta">
                            <span class="car_info"> {{$v['car_info']}}</span>
                            <span class="car_color" style="background:{{$v['color']}}"> {{$v['color']}} </span>
                            <span class="car_info"> {{$v['vclass']}}</span>
                        </div>
                    </div>
                </div>
                    @else
                   <p class="text-info">NA</p> 
                @endif

                
            </td>

                @if ($booking->driver_id==NULL)
                    <td>
                        @if(auth()->user()->can('booking-edit'))
                        <a href="{{ url('bookings/assignDriverToBooking') }}/{{$booking->id}}">assign to driver</a> 
                        
                        @else
                        <span class='text-danger'>No Driver</span>
                        @endif
        
                    </td>
                    @else
                    <td class="driver_td">
                        <span class="text-info driver_preview" driver_id="{{ $booking->driver_id }}">{{ $d->fullName($booking->driver_id) }}</span>
                        <br>
                        <span class="driver_action_detail">
                            @if ($booking->status == "ready")
                                @if(auth()->user()->can('booking-edit'))
                                    <a class="text-success" href="{{ url('bookings/assignDriverToBooking') }}/{{$booking->id}}" title="change driver"><i
                                        class="fa fa-edit mr-2"></i></a> |

                                        <a class="text-danger" href="{{ url('bookings/removeBookingToDriver') }}/{{$booking->id}}" title="delete"><i
                                            class="fa fa-trash ml-2" title="delete"></i></a>
                                        
                                @endif
                            @endif
                        </span>
                    
                        <div class="driver_preview_{{$booking->driver_id}}" style="display:none">
                            @php
                                $d1 = $d->driverdata($booking->driver_id);   
                            @endphp
                            <div class="vehicle_card">
                                <img src="{{ asset('public/assets/drivers') }}/{{$d1['image']}}" style="width:100%">
                                <div class="vehicle_meta">
                                    <span class="car_info"> {{$d1['contact_no']}}</span>
                                    <span class="car_color"> {{$d1['email']}}</span>
                                </div>
                            </div>
                        </div>

                    </td>
                @endif

                @if ($user->role==1)
                <td class="driver_td">        
                    {{-- @if ($booking->status == "open") --}}        
                                    
                    @if (is_null($booking->supplier_id))
                        @if ($booking->status == "open")
                            @can('booking-edit')
                                <a href="{{ url('bookings/assignBookingToSupplier') }}/{{$booking->id}}">Move to Supplier</a>
                            @endcan
                        @else
                            {{$user->first_name}} {{$user->last_name}}
                        @endif
                    @else

                    <span class="text-info supplier_preview" supplier_id="{{ $booking->supplier_id }}">{{$supplier->fullName($booking->supplier_id)}}</span>

                    <br>
                    <span class="driver_action_detail">
                        @if ($booking->status == "open")
                            @if(auth()->user()->can('booking-edit'))
                                <a class="text-success" href="{{ url('bookings/assignBookingToSupplier') }}/{{$booking->id}}"><i
                                    class="fa fa-edit mr-2" title="edit"></i></a> |

                                <a class="text-danger" href="{{ url('bookings/removeBookingToSupplier') }}/{{$booking->id}}" title="delete"><i
                                    class="fa fa-trash ml-2" title="delete"></i></a>
                            @endif
                        @endif
                    </span>
                    <div class="supplier_preview_{{$booking->supplier_id}}" style="display:none">

                        @php
                            $s = $supplier->supplierdata($booking->supplier_id);    
                        @endphp
                        
                        <div class="vehicle_card">
                            <img src="{{ asset('public/storage/assets/suppliers') }}/{{$s['image']}}" style="width:100%">
                            <div class="vehicle_meta">
                                <span class="car_info"> {{$s['company_name']}}</span>
                                <span class="car_color"> {{$s['contact_no']}}</span>
                            </div>
                        </div>
                    </div>
                    
                    @endif
                        {{-- @else {{$user->first_name}} {{$user->last_name}} --}}
                    {{-- @endif --}}
                    
                    
                </td>
                @endif 
                <td class="{{$danger}}">

                    @if($booking->status == "open")
                        <span class='text-danger'>Not Assigned</span>
                    @elseif($booking->status == "ready")
                        <span class='text-info'>Pending Approval</span>
                    @elseif($booking->status == "accepted")
                        <span class='text-info'>accepted</span>
                    @elseif($booking->status == "active")
                        <span class='text-info'>Started</span>
                    @elseif($booking->status == "noshow")
                        <span class='text-danger'>Client no show</span>
                    @elseif($booking->status == "client")
                        <span class='text-info'>Client Picked Up</span>
                    @elseif($booking->status == "finish")
                        <span class='text-success'>Completed</span>
                    @elseif($booking->status == "expired")
                        <span class='text-white'>Expired</span>
                    @endif 
                 
                </td>    
            
            </tr>
        @endforeach

    </tbody>
    
        @if($bookings->hasPages())
        <tr>
            
                <td colspan="16">
                    {{ $bookings->links() }}
                </td>
         
        </tr>
        @endif
</table>
</form>

@include('pages.bookings.view-booking-modal')

@endsection

@section('js')

<script>

    var sno = 1;
    $(document).ready(function() {

        // $(".booking_status").select2();


    var currency = "{{Config('currency-symbol')}}";

        $('[data-toggle="tooltip"]').tooltip();

        $(function () {
                $('#datetimepicker14').datetimepicker({
                    allowMultidate: true,
                    format: 'YYYY-MM-DD',
                    multidateSeparator: ','
                });
            });


        $(".view_booking").click(function(){
           
            $("#booking_detail_modal").hide();
            
            var bookingId = $(this).attr("id");
            
            $.ajax({ 
                        url: "{{route('booking.data')}}/"+bookingId,
                        type: 'POST',                
                        success: function(data){
                            
                            $("#myModal").modal('show');
                            $("#booking_detail_modal").show();
                            $(".vehicle_data").show();
                            $(".driver_data").show();
                         

                          driver_id = data.driver_id;
                          $("#pickup-point").text(data.booking.pickup_point);
                          $("#pickup-date").text(data.booking.pickup_date);
                          
                          $("#duration").empty();
                          $("#dropoff").empty();
                          if(data.booking.duration){
                            if(data.booking.duration > 1){
                                hr = "hours";
                            }else{
                                hr = "hour";
                            }
                          $("#duration").text(data.booking.duration+" "+hr);
                          }else{
                            
                          $("#dropoff").text(data.booking.drop_off);
                          }

                          $("#pickup-time").text(data.booking.pickup_time);
                          $("#price").text(currency+data.booking.grand_price);
                          $("#pickup_sign").text(data.booking.pickup_sign);

                          (data.booking.no_of_bags)? $("#bags").text(data.booking.no_of_bags):$("#bags").text("NA");
                          
                          (data.booking.no_of_adults)?$("#adults").text(data.booking.no_of_adults):$("#adults").text("NA");
                            
                          $("#c-name").text(data.booking.first_name+' '+data.booking.last_name);
                          $("#c-email").text(data.booking.email);
                          $("#c-contact").text(data.booking.contact_no);
                          $("#v-class").text(data.vehicleClass);
                            var logs = data.booking_log;
                            $("#booking_log").empty();
                            for(i in logs){
                           
                                var log = logs[i].log;
                                var updated_by_first = logs[i].updated_by.first_name;
                                var updated_by_last = logs[i].updated_by.last_name;
                        
                                var log_time = logs[i].created_at;

                                
                                $("#booking_log").append(`
                                    <tr>
                                        <td>${log}</td>
                                        <td>${log_time}</td>
                                        <td>${updated_by_first} ${updated_by_last}</td>
                                    </tr>
                                `);
                            }
                            
                            
                          if(data.booking.driver_id != null){
      
                            $(".no-vehicle").hide();
                            $('#vehicle-img').attr("src","{{ asset('public/assets/vehicles') }}/"+data.vehicle.image);
                            $('#driver-img').attr("src","{{ asset('public/assets/drivers') }}/"+data.driver.driver_image);
                            $("#vehicle-make").text(data.vehicle.manufacturer);
                            $("#v-color").text(data.vehicle.car_color);
                            $("#d-name").text(data.user.first_name+' '+data.user.last_name);
                            $("#d-email").text(data.user.email);
                            $("#d-contact").text(data.driver.contact_no);

                            
                          }else{
                            $(".vehicle_data").hide();
                            $(".driver_data").hide();
                            $(".no-vehicle").show();

                          }
                  
                        }

                   
                    })
            
        })
        
        $(".my-popover, .popover").hover(function(){

             $(this).popover('show');
           
          
        },function(){
            $(this).popover('hide');
           //console.log("l");
        });


        $("#manage_bookings").slideDown();
        $('.select').select2();

        $('#by_date').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: '{{old("by_date")}}'
      }); 

        var table = $('#manage_bookings').DataTable({
            
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.childRowImmediate,
                    type: 'none',
                    target: ''
                }
            },
            "paging":false,
            "searching": false,
            "autoWidth": false,
            "colReorder": {
                realtime: true
            }      
        });

        if('{{ count($bookings) }}' < 1){
            $("#thcheck").hide();
           
        }

       

        // show extra information on mouse hover


        table.rows().every(function() {
            this.child.hide();
        });


        $('.details-control').mouseover(function() {
            
            table.rows().every(function() {
                this.child.hide();
            });



            var row = table.row($(this).parent('tr'));
            var next = $(this).next();

            $(next).mouseover(function() {
                row.child.hide();
                row.child.show();
                $(this).parent('tr').addClass('shown');
                
            }, function() {
                row.child.hide();
            });

            row.child.show();
          
                

        });


        $('#manage_bookings').mouseleave(function() {
            table.rows().every(function() {
                this.child.hide();
            });

        });
        // show extra information on mouse hover


        //real time event



        // end of document ready
    });

    popUPDetail(".driver_preview",'driver_id');
    popUPDetail(".supplier_preview",'supplier_id');
    popUPDetail(".vehicle_preview",'car_id');


    

   
   
   
</script>

@endsection