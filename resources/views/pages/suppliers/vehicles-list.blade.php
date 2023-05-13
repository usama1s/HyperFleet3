
@php

use App\Models\VehicleClass;
use App\Models\Driver;
$driver = new Driver;

@endphp


<table id="supplier_vehicles" style="width:100%" class="table responsive">
    <thead>
        <tr>
        
            <th>#</th>
            <th>Image</th>
            <th>License #</th>
            <th>Manufacturer</th>
            <th>Class</th>
            <th>Approval</th>
        </tr>
    </thead>
    <tbody>
        @php
        $sno =1;
        @endphp

        @foreach ($vehicles as $vehicle)
        <tr row_id="{{$sno - 1}}">
       
            <td>
                {{ $sno++}}

            </td>
            <td><img class="manage-thumbnail" src="{{ asset('public/assets/vehicles') }}/{{$vehicle->image}}" width="80"></td>
            <td>
                {{ $vehicle->license_plate}}
                <span class="action_wapper2">
                    @can('vehicle-view')
                    <a class="text-info mr-2" href="{{ url('vehicles')}}/{{$vehicle->id}}"><i
                        class="fa fa-eye"></i></a>
                    @endcan
                    @can('vehicle-edit')
                    <a class="text-success mr-2" href="{{url('vehicles')}}/{{$vehicle->id}}/edit"><i
                        class="fa fa-edit"></i></a>
                    @endcan
                    @can('vehicle-delete')
                    <a class="text-danger"  onclick="(function(e){e.preventDefault();record_delete(e)})(event)"  href="{{url('vehicles')}}/delete/{{$vehicle->id}}"><i
                        class="fa fa-trash"></i></a>
                    @endcan
                </span>
            </td>
            <td>{{ $vehicle->manufacturer}}</td>
            <td>{{ VehicleClass::getById($vehicle->vehicle_class_id) }}</td>

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
</table>


