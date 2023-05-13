
@php
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Shift;

$driver = new Driver;
$shift = new Shift;

@endphp


<table id="manage_drivers" style="width:100%" class="table responsive">
    <thead>
        <tr>
          
            <th width="10px" >#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Shift</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sno = 1;
        @endphp
        @foreach ($drives as $driver)
            <tr>
                
            <td width="10px" >{{$sno++}}</td>
                <td><img class="manage-thumbnail" src="{{ asset('public/assets/drivers') }}/{{$driver->driver_image}}" width="80"></td>
                <td>{{ $driver->first_name}}</td>
                <td>{{ $driver->email}}
                    <span class="action_wapper2">
                        @can('driver-view')
                        <a class="text-info mr-2" href="{{ url('drivers')}}/{{$driver->user_id}}" title="view"><i
                            class="fa fa-eye"></i></a>
                        @endcan
    
                        @can('driver-edit')
                        <a class="text-success mr-2" href="{{url('drivers')}}/{{$driver->user_id}}/edit" title="edit"><i
                            class="fa fa-edit"></i></a>
                        @endcan
    
                        @can('driver-delete')
                        <a class="text-danger mr-2" onclick="(function(e){e.preventDefault();record_delete(e)})(event)" href="{{url('drivers')}}/delete/{{$driver->user_id}}" title="delete"><i
                            class="fa fa-trash"></i></a>
                        @endcan
                    </span>
                </td>
                <td>{{ $driver->contact_no}}</td>
                <td>
                    @if (is_null($driver->shift_id))

                    @if(auth()->user()->can('driver-edit'))
                    <a href="{{ url('drivers/assignShift')}}/{{$driver->user_id}}">Assign Shift</a>
                    @else
                    No Shift Assign
                    @endif
    
                    @else
                    {{ $shift->getName($driver->shift_id) }}
    
                    @can('driver-edit')
                    <span class="driver_action_detail">
                        <a class="text-success" href="{{ url('drivers/assignShift')}}/{{$user->user_id}}"><i
                                class="fa fa-edit"></i></a>
                        <a class="text-danger" href="{{ url('/drivers/remove-assign-shift/')}}/{{$user->user_id}}"><i
                                class="fa fa-trash"></i></a>
                    </span>
                    @endcan
                    <br>
                    {{ $shift->getStart($driver->shift_id) }} - {{ $shift->getEnd($driver->shift_id) }}
    
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>


</table>


