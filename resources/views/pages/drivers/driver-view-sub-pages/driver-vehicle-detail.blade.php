@php
    use App\Models\VehicleClass;
    use App\Models\Vehicle;
   $vehicle = Vehicle::find($user->vehicle_id);
   
@endphp
<div class="row">
    @if (!is_null($vehicle))
    <div class="col-md-6">
        
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid" style="width:98%; padding:4px;" src="{{ asset('public/assets/vehicles/'. $vehicle->image ) }}" alt="User profile picture">
            </div>    
    
          <h3 class="profile-username text-center">{{ $vehicle->manufacturer }} {{ $vehicle->car_model }}</h3>
    
            <p class="text-muted text-center">License Plate # {{ $vehicle->license_plate }}</p>
            
            @if ($user->status!='booked')
            @if(auth()->user()->can('driver-edit'))
            <div class="text-center">
                <a class="text-success" href="{{ url('drivers/assignVehicle')}}/{{$user->user_id}}"><i
                        class="fa fa-edit"></i></a>
                <a class="text-danger" onclick="(function(e){e.preventDefault();record_delete(e)})(event)" href="{{ url('/drivers/remove-assign-vehicle/')}}/{{$user->user_id}}"><i
                        class="fa fa-trash"></i></a>
            </div>
            @endif
            @endif

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Vehicle Model</b> <a class="float-right">{{ $vehicle->car_model }}</a>
              </li>
              <li class="list-group-item">
                <b>Year</b> <a class="float-right">{{ $vehicle->car_year }}</a>
              </li>
    
              <li class="list-group-item">
                <b>Color</b> <a class="float-right">{{ $vehicle->car_color }}</a>
              </li>
    
              <li class="list-group-item">
                <b>Vehicle Class</b> <a class="float-right">{{ VehicleClass::getById($vehicle->vehicle_class_id) }}</a>
              </li>
    
              <li class="list-group-item">
                <b>Seats</b> <a class="float-right">{{ $vehicle->seats }}</a>
              </li>
              <li class="list-group-item">
                <b>Luggage</b> <a class="float-right">{{ $vehicle->luggage }}</a>
              </li>
              
            </ul>
    
            {{-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> --}}
          </div>
          
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

      </div>
       
      <!-- /.col -->

    
     
      @else
    <div class="col-md-12">

 
      <h4 class="text-center">No Vehicle assigned to the driver</h4>
      @if(auth()->user()->can('driver-edit'))
                <a class="text-center" href="{{ url('drivers/assignVehicle')}}/{{$user->user_id}}">Assign vehicle</a>
      @endif

    </div>

@endif

</div>

