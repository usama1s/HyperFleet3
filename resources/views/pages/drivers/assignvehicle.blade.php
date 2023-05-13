@php
use App\Models\VehicleClass;
use App\Models\User;
$user = User::find($user_id);
@endphp
@extends('layouts.app')

@section('title', 'Assign Vehicle')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
<li class="breadcrumb-item"><a href="{{ url('/drivers') }}">Drivers </a></li>
    <li class="breadcrumb-item active">Assign Vehicle </li>
    
  </ol>

  @endsection  

@section('content')

<form method="POST" action="{{ route('drivers.assignvehicle')}}">
@csrf
{{-- ROW ONE ADD NEW VEHICLE --}}
<div class="row">
    <div class="col-md-12">
          <!-- SELECT2 EXAMPLE -->
          <div class="card card-default">
            <div class="card-header">
            <h3 class="card-title">Assign Vehicle to <strong> <h3 class="mt-2">{{ $user->fullname() }} </h3></strong></h3>
  
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Vehicles</label>
                    
                  <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}">
                    <select class="form-control assign-to-vehicle" name="assignvehicle" id="assignvehicle" class="height:50px">
                      <option value="">Select Vehicle</option>
                    
                        @forelse ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} ....[{{ VehicleClass::getById($vehicle->vehicle_class_id) }}]</option>
                        @empty
                        <option value="">No Vechicle Available</option>
                        @endforelse
                   
                        
                    </select>
                    @error('assignvehicle')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                    The vehicle field is required.
                    </div>
                    @enderror
                 
                 
                  </div>
                </div>
             
              </div>
              <!-- /.row -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
             
            </div>
          </div>
          <!-- /.card -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="d-flex justify-content-end" style="margin: 50px 20px;">
          <input class="btn btn-lg btn-success" type="submit" value="Save" style="width:200px">
      </div>
    </div>
  </div>

</form>
@endsection

@section('js')
<script>
    $(document).ready(function(){
      $('.assign-to-vehicle').select2()
    });


</script>
 @endsection