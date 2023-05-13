@php
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Shift;
use App\Models\Supplier;
$supplier = new Supplier;

$driver = new Driver;
$shift = new Shift;
$vehicle = new Vehicle;

session(['driver_ref_page' =>  url()->full() ]);
@endphp


@extends('layouts.app')

@section('title', 'Manage Drivers')

@section('breadcrumb')

<div class="page_sub_menu">
    @can('driver-delete')
        <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    @endcan
    
    @can('driver-create')
        <a class="btn btn-sm btn-success" href="{{ route('drivers.create')}}">Add New</a>
    @endcan
</div>

@endsection

@section('content')
@if (auth()->user()->role==1)
    
<div>
    <form action="{{route('categories.driver')}}" method="get" id="selectSupplierForm">
   
        <div class="row">

            <div class="col-md-4 form-group">
                
            <a href="{{route('drivers.index')}}" class="btn btn-lg btn-info" style="width: 100%;margin-top: 26px;">All Drivers</a>
            </div>

            <div class="col-md-4 form-group">
            
                <a href="{{route('categories.driver',"supplier_id=")}}" class="btn btn-lg btn-info" style="width: 100%;margin-top: 26px;">Own Drivers</a>
            </div>

            <div class="col-md-4">
                <label for="">Show Drivers By</label>
                <select name="supplier_id" class="form-control by-driver" id="selectSupplier">  
                    <option value="">Select Supplier</option>

                    @forelse ($supplier::all() as $i)
                        @if (@$_GET['supplier_id'] == $i->user_id)
                            <option value="{{$i->user_id}}" selected>{{$supplier->fullName($i->user_id)}}</option>
                        @else
                            <option value="{{$i->user_id}}" >{{$supplier->fullName($i->user_id)}}</option>
                        @endif
               
                    @empty
                        <option value="">No supplier Found</option>
                    @endforelse
               
                    
                </select>
            </div>
            
        </div>
    </form>
</div>
@endif
<div id="driver_search">
<form action="{{ route('drivers.search') }}" method="get">
    @csrf
    <div class="row">
        <div class="col-md-2">
            
            @php
                if (old('driver_info_type')){
                    $old_driver_info_type = old('driver_info_type');
                }else{
                    $old_driver_info_type = '';
                }
            @endphp

            <select name="driver_info_type" class="form-control">   
                <option value="byname"  {{$old_driver_info_type == 'byname' ? 'selected' : ''}}>By Name</option>    
                <option value="byemail" {{$old_driver_info_type == 'byemail' ? 'selected' : ''}}>By Email</option>
                <option value="bycontact" {{$old_driver_info_type == 'bycontact' ? 'selected' : ''}}>By Phone</option>
                
            </select>
        </div>
        <div class="col-md-3">
        <input class="form-control" type="text" name="driver_info" value="{{old('driver_info')}}" placeholder="Enter Search">
        </div>

        <div class="col-md-3">
            <input class="form-control" type="text" name="driver_vehicle_no"  value="{{old('driver_vehicle_no')}}" placeholder="License #">
        </div>

        <div class="col-md-2">
            
            @php
                if (old('driver_status')){
                    $old_status = old('driver_status');
                }else{
                    $old_status = '';
                }
            @endphp

            <select name="driver_status" class="form-control by-driver">
                <option value=""  {{$old_status == '' ? 'selected' : ''}}>All Drivers</option>    
                <option value="booked" {{$old_status == 'booked' ? 'selected' : ''}}>Booked</option>
                <option value="available" {{$old_status == 'available' ? 'selected' : ''}}>Available</option>
                <option value="null" {{$old_status == 'null' ? 'selected' : ''}} >Not Assigned</option>
                <option value="off" {{$old_status == 'off' ? 'selected' : ''}}>Driver On Leave</option>
            </select>
        </div>

        <div class="col-md-2">
            <input type="submit" value="Search" class="btn btn-default">
            <input type="reset" value="Reset" class=" btn btn-danger">
        </div>
    </div>
   
</form>
</div>
<br>

<form action="{{ route("drivers.bulkdestroy") }}" method="POST" id="record-form">
    @csrf
<table id="manage_drivers" style="width:100%" class="table table-hover responsive">
    <thead>
        <tr>
            <th id="thcheck" data-orderable="false"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
            <th>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
            <th>Status</th>
            <th>Vehicle</th>
            <th>Shift</th>
            <th>Approval</th>
            <!-- <th>Status</th> -->
        </tr>
    </thead>
    <tbody>
        @php
        $sno =1;
        @endphp

        @foreach ($users as $user)
        <tr>
            
            <td class="multi-select-ids-td">
                <input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $user->user_id }}">
            
            </td>
            
            <td>
                {{ $sno++}}

            </td>
            <td><img class="manage-thumbnail" src="{{ asset('public/assets/drivers') }}/{{$user->driver_image}}" width="80"></td>
            <td>{{ $driver->fullName($user->user_id) }}
                @if (!is_null($user->block_type))
                    <div class="text-danger"><strong>Blocked</strong></div>               
                @endif
            </td>
            <td>{{ $user->email}}
               
                <span class="action_wapper2">
                    @can('driver-view')
                    <a class="text-info mr-2" href="{{ url('drivers')}}/{{$user->user_id}}" title="view" data-toggle="tooltip" data-placement="top"><i
                        class="fa fa-eye"></i></a>
                    @endcan

                    @can('driver-edit')
                    <a class="text-success mr-2" href="{{url('drivers')}}/{{$user->user_id}}/edit" title="edit" data-toggle="tooltip" data-placement="top"><i
                        class="fa fa-edit"></i></a>
                    @endcan

                    @can('driver-delete')
                    <a class="text-danger mr-2" onclick="(function(e){e.preventDefault();record_delete(e)})(event)" href="{{url('drivers')}}/delete/{{$user->user_id}}" title="delete" data-toggle="tooltip" data-placement="top"><i
                        class="fa fa-trash"></i></a>
                    @endcan
                </span>
            </td>
            <td>{{ $user->contact_no}}</td>
            <td>
                @php

                if($user->status == null){
                $msg ="<span class='text-danger'>No Vehicle assigned</span>";
                }
                else if($user->status == "available"){
                $msg ="<span class='text-warning'>Available for Booking</span>";
                }
                else if($user->status == "assigned"){
                $msg ="<span class='text-info'>Booking assigned</span>";
                }
                else if($user->status == "booked"){
                $msg ="<span class='text-success'>Driver is Booked</span>";
                }
                else if($user->status == "off"){
                $msg ="<span class='text-danger'>Driver is On Leave</span>";
                }else{
                $msg ="Unknown Status";
                }
                echo $msg;

                @endphp
            </td>
            @if ($user->vehicle_id == null)
                <td>
                    @if(auth()->user()->can('driver-edit'))
                    <a href="{{ url('drivers/assignVehicle')}}/{{$user->user_id}}">Assign Vehicle</a>
                    @else
                    No Vehicle assigned
                    @endif
                </td>
            @else
                <td class="driver_td">
                    <a target="_blank" class="vehicle_preview" car_id="{{$user->vehicle_id}}"
                        href="{{ route('vehicles.show',$user->vehicle_id) }}" }>
                        {{$vehicle->getPlate($user->vehicle_id)}}</a>

                    <div class="vehicle_preview_{{$user->vehicle_id}}" style="display:none">
                        @php
                        $v = $vehicle->vdata($user->vehicle_id);   
                        @endphp
                        <div class="vehicle_card">
                            <img src="{{ asset('public/assets/vehicles') }}/{{$v['image']}}" style="width:100%">
                            <div class="vehicle_meta">
                                <span class="car_info">{{$v['car_info']}}</span>
                                <span class="car_color" style="background:{{$v['color']}}"> {{$v['color']}}</span>
                                <span class="car_color">{{$v['vclass']}} </span>
                            </div>
                        </div>
                    </div>

                    <br>
                        @switch($user->status)
                            @case('assigned')
                                @if(auth()->user()->can('driver-edit'))
                                <span class="driver_action_detail">
                                    <a class="text-success" href="{{ url('drivers/assignVehicle')}}/{{$user->user_id}}" data-toggle="tooltip" data-placement="top" title="vehicles with same vehicle class will show which are requested for booking"><i
                                            class="fa fa-edit"></i>re-assign</a>
                                
                                </span>
                                @endif
                                @break

                           
                            @default
                                @if(auth()->user()->can('driver-edit'))
                                <span class="driver_action_detail">
                                    <a class="text-success" href="{{ url('drivers/assignVehicle')}}/{{$user->user_id}}"><i
                                            class="fa fa-edit mr-2" title="edit"></i></a> |
                                    <a class="text-danger" onclick="(function(e){e.preventDefault();record_delete(e)})(event)" href="{{ url('/drivers/remove-assign-vehicle/')}}/{{$user->user_id}}"><i
                                            class="fa fa-trash ml-2" title="delete"></i></a>
                                </span>
                                @endif
                        @endswitch
                        
                    @endif
                </td>

            <td class="driver_td">
                @if (is_null($user->shift_id))

                    @if(auth()->user()->can('driver-edit'))
                        <a href="{{ url('drivers/assignShift')}}/{{$user->user_id}}">Assign Shift</a>
                    @else
                        No Shift assigned
                    @endif

                @else
                {{ $shift->getName($user->shift_id) }}

                @can('driver-edit')
                

                <span class="driver_action_detail">
                    <a class="text-success" href="{{ url('drivers/assignShift')}}/{{$user->user_id}}"><i
                            class="fa fa-edit mr-2"></i></a> |
                    <a class="text-danger" href="{{ url('/drivers/remove-assign-shift/')}}/{{$user->user_id}}"><i
                            class="fa fa-trash ml-2"></i></a>
                </span>

                @endcan
                <br>
                {{ $shift->getStart($user->shift_id) }} - {{ $shift->getEnd($user->shift_id) }}

                @endif

            </td>

            <noscript>
                <style>
                    .action_wapper2 {
                        display: none;
                    }

                    .action_wapper {
                        display: none;
                    }
                </style>
                <td>
                    <a class="btn btn-sm btn-info" href="{{ url('drivers')}}/{{$user->user_id}}">View</a>
                    <a class="btn btn-sm btn-success" href="{{url('drivers')}}/{{$user->user_id}}/edit">Edit</a>
                    <a class="btn btn-sm btn-danger" href="{{url('drivers')}}/delete/{{$user->user_id}}">Delete</a>
                </td>
            </noscript>

        </form>
        {{-- form end --}}
            <td>                            
                {{-- admin show button with functionlity that can approve  --}}
                @if (auth()->user()->role == 1)
                <form action="{{ route('drivers.approved_and_disapproved',['id'=> $user->id]) }}" method="get">
                    <button  class="btn {!!  $user->admin_approve ? 'btn-success' : 'btn-danger' !!} btn-sm">
                        {!!  $user->admin_approve ? "Approved" : "Pending" !!}
                    </button>
                </form>
                @endif

                {{-- supplier can view only  --}}
                @if (auth()->user()->role == 3)
                    <button disabled  class="btn {!!  $user->admin_approve ? 'btn-success' : 'btn-danger' !!} btn-sm">
                        {!!  $user->admin_approve ? "Approved" : "Pending" !!}
                    </button>
                @endif

            </td>
        </tr>
        @endforeach

    </tbody>

    @if($users->hasPages())
    <tr>
        
            <td colspan="10">
                {{ $users->links() }}
            </td>
     
    </tr>
    @endif
</table>

@endsection

@section('js')

<script>
    var sno = 1;
    $(document).ready(function() {
        $("#selectSupplier").on("change",function(){
            $("#selectSupplierForm").submit();
        })

        $('[data-toggle="tooltip"]').tooltip();   
        $('#manage_drivers').DataTable({
            paging:false,
            "searching": false
        });

        if('{{ count($users) }}' < 1){
            $("#thcheck").hide();
        }
        popUPDetail(".vehicle_preview",'car_id');

        $('.by-driver').select2()
        // $('.vehicle_preview').hover(function(e) {

        //     data_id = "#vehicle_data_" + $(this).attr('car_id');
        //     data = $(data_id).html();
        //     $('.popElement').remove();

        //     mousePosX = e.originalEvent.x + 20 + "px";
        //     mousePosY = e.originalEvent.y + 50 + "px";

        //     //$(this).parent().first().css("position", "relative");
        //     ele = $("<div></div>");

        //     ele.addClass('popElement');


        //     ele.append(data)

        //     $(this).parent().first().css('position','relative').append(ele);

        //     ele.css({
        //         "left": -(ele.width()/2)+20,
        //         "bottom": ($(this).parent().height())+20 
        //     });
           

        // }, function() {
        //     //leaving mousing
        //     $('.popElement').remove();
        // });


    });
</script>

@endsection