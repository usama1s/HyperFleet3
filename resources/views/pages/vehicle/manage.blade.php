@php
    use App\Models\VehicleClass;
    use App\Models\Driver;
    use App\Models\Supplier;
    $supplier = new Supplier();
    $driver = new Driver();
    
    session(['vehicle_ref_page' => url()->full()]);
    
@endphp

@extends('layouts.app')

@section('title', 'Manage Vehicles')

@section('breadcrumb')
    {{-- <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home </a></li>
    <li class="breadcrumb-item"><a href="{{url('vehicles')}}">Vehicles </a></li>
    <li class="breadcrumb-item active">Manage</li>
</ol>

</ol> --}}
    <div class="page_sub_menu">
        @can('vehicle-delete')
            <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
        @endcan

        @can('vehicle-create')
            <a class="btn btn-sm btn-success" href="{{ route('vehicles.create') }}">Add New</a>
        @endcan
        @role('admin')
            <a class="btn btn-sm btn-info" href="{{ route('vehicles-classes.index') }}">Add Vehicle Class</a>
         @endrole   
    </div>
@endsection

@section('content')
    @if (auth()->user()->role == 1)
        <div>
            <form action="{{ route('categories.vehicle') }}" method="get" id="selectSupplierForm">

                <div class="row">

                    <div class="col-md-4 form-group">

                        <a href="{{ route('vehicles.index') }}" class="btn btn-lg btn-info"
                            style="width: 100%;margin-top: 26px;">All Vehicles</a>
                    </div>

                    <div class="col-md-4 form-group">

                        <a href="{{ route('categories.vehicle', 'supplier_id=') }}" class="btn btn-lg btn-info"
                            style="width: 100%;margin-top: 26px;">Own Vehicles</a>
                    </div>

                    <div class="col-md-4 form-group">
                        <label for="">Show Vehicles By</label>
                        <select name="supplier_id" class="form-control by-driver" id="selectSupplier">
                            <option value="">Select Supplier</option>

                            @forelse ($supplier::all() as $i)
                                @if (@$_GET['supplier_id'] == $i->user_id)
                                    <option value="{{ $i->user_id }}" selected>{{ $supplier->fullName($i->user_id) }}
                                    </option>
                                @else
                                    <option value="{{ $i->user_id }}">{{ $supplier->fullName($i->user_id) }}</option>
                                @endif

                            @empty
                                <option value="">No Supplier found</option>
                            @endforelse

                        </select>
                    </div>

                </div>
            </form>
        </div>
    @endif
    <div id="vehicles_search">
        <form action="{{ route('vehicles.search') }}" method="get">
            @csrf
            <div class="row">

                <div class="col-md-2">
                    <input class="form-control" type="text" name="by_vehicle_model" value="{{ old('by_vehicle') }}"
                        placeholder="Enter Vehicle Model No.">
                </div>


                <div class="col-md-2">
                    <input class="form-control" type="text" name="by_vehicle_no" value="{{ old('by_vehicle_no') }}"
                        placeholder="License #">
                </div>

                <div class="col-md-2">

                    <select name="by_vehicle_class" class="form-control by-driver">
                        <option value="">All Vehicle Classes</option>

                        @forelse (VehicleClass::all() as $vc)
                            @if (old('by_vehicle_class') == $vc->id)
                                <option value="{{ $vc->id }}" selected>{{ $vc->name }}</option>
                            @else
                                <option value="{{ $vc->id }}">{{ $vc->name }}</option>
                            @endif

                        @empty
                            <option value="">No Vehicle Class found</option>
                        @endforelse

                    </select>
                </div>

                <div class="col-md-2">

                    @php
                        if (old('by_vehicle_status')) {
                            $old_by_vehicle_status = old('by_vehicle_status');
                        } else {
                            $old_by_vehicle_status = '';
                        }
                    @endphp

                    <select name="by_vehicle_status" class="form-control by-driver">
                        <option value="" {{ $old_by_vehicle_status == '' ? 'selected' : '' }}>All Vehicles</option>
                        <option value="booked" {{ $old_by_vehicle_status == 'booked' ? 'selected' : '' }}>Booked</option>
                        <option value="available" {{ $old_by_vehicle_status == 'available' ? 'selected' : '' }}>Available
                        </option>
                        <option value="null" {{ $old_by_vehicle_status == 'null' ? 'selected' : '' }}>Not Assigned
                        </option>
                        <option value="maintenance" {{ $old_by_vehicle_status == 'maintenance' ? 'selected' : '' }}>In Maintenance</option>
                    </select>
                </div>

                <div class="col-md-2">

                    <select name="by_drives" class="form-control by-driver">
                        <option value="">All Drivers</option>

                        @forelse ($driver::all() as $i)
                            @if (old('by_drives') == $i->user_id)
                                <option value="{{ $i->user_id }}" selected>{{ $driver->fullName($i->user_id) }}</option>
                            @else
                                <option value="{{ $i->user_id }}">{{ $driver->fullName($i->user_id) }}</option>
                            @endif

                        @empty
                            <option value="">No Driver found</option>
                        @endforelse

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
    <form action="{{ route('vehicles.bulkdestroy') }}" method="POST" id="record-form">
        @csrf
        <table id="manage_vehicles" style="width:100%" class="table table-hover responsive">
            <thead>
                <tr>
                    <th data-orderable="false" id="thcheck"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
                    <th>#</th>
                    <th>Image</th>
                    <th>License #</th>
                    <th>Manufacturer</th>
                    <th>Class</th>
                    <th>Status</th>
                    <th>Driver</th>
                    <th>Approval</th>
                    <noscript>
                        <th>Actions</th>
                    </noscript>

                </tr>
            </thead>
            <tbody>

                @php
                    $sno = 1;
                @endphp

                @foreach ($vehicles as $vehicle)
                    <tr row_id="{{ $sno - 1 }}">
                        <td>
                            <input type="checkbox" class="multi-select-ids" name="seleted_id[]"
                                value="{{ $vehicle->id }}">
                        </td>
                        <td>
                            {{ $sno++ }}

                        </td>
                        <td>
                            <img class="manage-thumbnail" src="{{ asset('public/assets/vehicles') }}/{{ $vehicle->image }}" width="80">
                        </td>
                        <td>
                            {{ $vehicle->license_plate }}
                            <span class="action_wapper2">
                                @can('vehicle-view')
                                    <a class="text-info mr-2" href="{{ url('vehicles') }}/{{ $vehicle->id }}" title="view"
                                        data-toggle="tooltip" data-placement="top"><i class="fa fa-eye"></i></a>
                                @endcan
                                @can('vehicle-edit')
                                    <a class="text-success mr-2" href="{{ url('vehicles') }}/{{ $vehicle->id }}/edit"
                                        title="edit" data-toggle="tooltip" data-placement="top"><i
                                            class="fa fa-edit"></i></a>
                                @endcan
                                @can('vehicle-delete')
                                    <a class="text-danger" onclick="(function(e){e.preventDefault();record_delete(e)})(event)"
                                        href="{{ url('vehicles') }}/delete/{{ $vehicle->id }}" title="delete"
                                        data-toggle="tooltip" data-placement="top">
                                        <i class="fa fa-trash"></i></a>
                                @endcan
                            </span>
                        </td>
                        <td>{{ $vehicle->manufacturer }}</td>
                        <td>{{ VehicleClass::getById($vehicle->vehicle_class_id) }}</td>
                        <td class="status_td">
                            @php
                                $maintanceMode = true;
                                
                            @endphp

                            @if ($vehicle->status == null)
                                <span class='text-danger'>No Driver assigned</span>
                            @elseif($vehicle->status == 'available')
                                <span class='text-warning'>Available for Booking</span>
                            @elseif($vehicle->status == 'assigned')
                                <span class='text-info'>Booking assigned</span>
                            @elseif($vehicle->status == 'booked')
                                <span class='text-success'>Vehicle is Booked</span>
                            @elseif($vehicle->status == 'maintenance')
                                @can('vehicle-edit')
                                    <span class='text-info'>
                                        Under Maintenance
                                        <span class="maintenance_action_detail">
                                            <a class="text-danger remove-maintenance" onclick="removeMaintenaince(event,this)"
                                                href="{{ url('/vehicles/remove-maintenance') }}/{{ $vehicle->id }}"><i
                                                    class="fa fa-trash "></i></a>
                                        </span>

                                    </span>
                                @endcan
                            @else
                                <span>Unknown Status</span>
                            @endif

                            @php
                                if ($vehicle->status == 'maintenance') {
                                    $maintanceMode = false;
                                }
                            @endphp


                            @if ($maintanceMode)
                                <br>
                                @can('vehicle-edit')
                                    @if ($vehicle->status != 'assigned' && $vehicle->status != 'booked')
                                        <span class="maintenance_action_detail">
                                            <a class="text-success go-to-maintenance" onclick="goToMaintenaince(event,this)"
                                                href="{{ url('vehicles/go-to-maintenance') }}/{{ $vehicle->id }}">Send to Maintenance</i></a>
                                        </span>
                                    @endif
                                @endcan
                            @endif
                        </td>
                        <td class="driver_td">
                            @if ($driver->fullName($vehicle->driver_id) == null)
                                @if ($maintanceMode)
                                    @if (auth()->user()->can('vehicle-edit'))
                                        <a href="{{ url('vehicles/assign-to-driver') }}/{{ $vehicle->id }}">Assign to Driver</a>
                                    @endif
                                @else
                                    Assign to Driver
                                @endif
                            @else
                                <a target="_blank" href="{{ route('drivers.show', $vehicle->driver_id) }}"
                                    class="driver_preview" driver_id="{{ $vehicle->driver_id }}">
                                    {{ $driver->fullName($vehicle->driver_id) }}
                                </a>
                                <div class="driver_preview_{{ $vehicle->driver_id }}" style="display:none">
                                    @php
                                        $d = $driver->driverdata($vehicle->driver_id);
                                    @endphp
                                    <div class="vehicle_card">
                                        <img src="{{ asset('public/assets/drivers') }}/{{ $d['image'] }}"
                                            style="width:100%">
                                        <div class="vehicle_meta">
                                            <span class="car_info"> {{ $d['contact_no'] }}</span>
                                            <span class="car_color"> {{ $d['email'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                @if ($vehicle->status != 'assigned' && $vehicle->status != 'booked')
                                    @can('vehicle-edit')
                                        <span class="driver_action_detail">
                                            <a class="text-success"
                                                href="{{ url('vehicles/assign-to-driver') }}/{{ $vehicle->id }}"><i
                                                    class="fa fa-edit mr-2" title="edit"></i></a> |
                                            <a class="text-danger "
                                                onclick="(function(e){e.preventDefault();record_delete(e)})(event)"
                                                href="{{ url('/vehicles/remove-assign-to-driver/') }}/{{ $vehicle->id }}"><i
                                                    class="fa fa-trash ml-2" title="delete"></i></a>
                                        </span>
                                    @endcan
                                @endif
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
                                <a class="btn btn-sm btn-info" href="{{ url('vehicles') }}/{{ $vehicle->id }}">View</a>
                                <a class="btn btn-sm btn-success"
                                    href="{{ url('vehicles') }}/{{ $vehicle->id }}/edit">Edit</a>
                                <a class="btn btn-sm btn-danger"
                                    href="{{ url('vehicles') }}/delete/{{ $vehicle->id }}">Delete</a>
                            </td>
                        </noscript>
                    </form>
                        <td>
                            
                            {{-- admin show button with functionlity that can approve  --}}
                            @if (auth()->user()->role == 1)
                            <form action="{{ route('vehicles.approved_and_disapproved',['id'=> $vehicle->id]) }}" method="get">
                                <button  class="btn {!!  $vehicle->admin_approve ? 'btn-success' : 'btn-danger' !!} btn-sm">
                                    {!!  $vehicle->admin_approve ? "Approved" : "Pending" !!}
                                </button>
                            </form>
                            @endif

                            {{-- supplier can view only  --}}
                            @if (auth()->user()->role == 3)
                                <button disabled  class="btn {!!  $vehicle->admin_approve ? 'btn-success' : 'btn-danger' !!} btn-sm">
                                    {!!  $vehicle->admin_approve ? "Approved" : "Pending" !!}
                                </button>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>

            @if ($vehicles->hasPages())
                <tr>

                    <td colspan="10">
                        {{ $vehicles->links() }}
                    </td>

                </tr>
            @endif

        </table>
 
@endsection



@section('js')

    <script>
        var sno = 1;
        var table = null;
        $(document).ready(function() {

            $("#selectSupplier").on("change", function() {

                $("#selectSupplierForm").submit();
            })

            $('[data-toggle="tooltip"]').tooltip();
            table = $('#manage_vehicles').DataTable({
                "paging": false,
                "searching": false
            });

            if ('{{ count($vehicles) }}' < 1) {
                $("#thcheck").hide();

            }

            $('.by-driver').select2()



            popUPDetail(".driver_preview", 'driver_id');

            // $('.driver_preview').hover(function(e) {

            //     data_id = "#driver_data_" + $(this).attr('driver_id');
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
            //     "left": -(ele.width()/2)+20,
            //     "bottom": ($(this).parent().height())+20 
            //     });


            //     }, function() {
            //     //leaving mousing
            //     $('.popElement').remove();
            //     });

            // Show Action Buttons on row hover with mouse cursor
            /*     $("#manage_vehicles").mousemove(function(e){
                    
                        var left = e.target.getBoundingClientRect().left;
                        var x = (e.pageX - this.offsetLeft)-400; 

                        if(x>-10){
                        
                            $('.action_wapper').css({"left": x+"px", "position": "absolute"});
                        }
                  
                }); */

            // show action button without mouse cursor
            /*   $('#manage_vehicles tbody').on( 'mouseenter', 'tr', function (e) {
                  var left = e.target.getBoundingClientRect().left;
                      var x = (e.pageX - this.offsetLeft)-400; 

                      if(x>-10){
                      
                          $('.action_wapper').css({"left": x+"px", "position": "absolute"});
                      }
              }); */

            // show inline button on mouse hover
            $('#manage_vehicles tbody').on('mouseenter', 'tr', function(e) {

            });
              
            // end of document ready
        });

        function goToMaintenaince(e, elem) {
            //  console.log(this)
            e.preventDefault();
            var link = $(elem).attr("href");
            var span = $(elem).parent();
            var td = span.parent();
            var tr = td.parent();

            var row_id = tr.attr("row_id");


            $.ajax({
                url: link,
                method: "GET",
                success: function(vehicle) {
                    $(elem).addClass("disabled");
                    var row_data = table.row(row_id).data();

                    row_data[6] =
                        `
                    
                     <span class='text-info'>
                    Under Maintenance
                    <span class="maintenance_action_detail">
                        <a class="text-danger remove-maintenance" onclick="removeMaintenaince(event,this)"  href="{{ url('/vehicles/remove-maintenance') }}/${vehicle.id}"><i
                                class="fa fa-trash "></i></a>
                    </span>

                     </span>
                    `;

                    row_data[7] =
                        `
                     Assign to Driver

                     `;

                    table.row(row_id).data(row_data).draw();

                }
            });


        }


        function removeMaintenaince(e, elem) {
            e.preventDefault();
            var link = $(elem).attr("href");
            var span = $(elem).parent();
            span = span.parent();
            var td = span.parent();
            var tr = td.parent();
            var row_id = tr.attr("row_id");


            $.ajax({
                url: link,
                method: "GET",
                success: function(vehicle) {
                    $(elem).addClass("disabled");
                    var row_data = table.row(row_id).data();

                    row_data[6] =
                        `
                    <span class='text-danger'>No Driver Assigned</span>
                    <br>
                     <span class="maintenance_action_detail">
                    <a class="text-success go-to-maintenance" onclick="goToMaintenaince(event,this)" href="{{ url('vehicles/go-to-maintenance') }}/${vehicle.id}">Send to Maintenance</i></a>
                     </span> 
                    `;

                    row_data[7] =
                        `
                     <a href="{{ url('vehicles/assign-to-driver') }}/${vehicle.id}">Assign to Driver</a>

                     `;

                    table.row(row_id).data(row_data).draw();

                }
            });


        }
    </script>

@endsection