@php
    use App\Models\Driver;
    use App\Models\VehicleClass;
    $vehicle = App\Models\Vehicle::find($vehicle_id);
@endphp
@extends('layouts.app')

@section('title', 'Assign Driver')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
<li class="breadcrumb-item"><a href="{{ url('/vehicles') }}">Vehicles </a></li>
    <li class="breadcrumb-item active">Assign Driver</li>
    
  </ol>

  @endsection  

@section('content')

<form method="POST" action="{{ route('vehicles.assigntodriver')}}">
@csrf
{{-- ROW ONE ADD NEW VEHICLE --}}
<div class="row">
    <div class="col-md-12">
          <!-- SELECT2 EXAMPLE -->
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">Assign Driver to Vehicle 
                {{-- <h3 class="mt-2">{{$vehicle->getProperty($vehicle_id,'manufacturer')}} ( {{$vehicle->getProperty($vehicle_id,'license_plate')}} )</h3></strong> <br>ds --}}
             </h3> <br>

           
             {{-- <ul class="card-title p-0" style="list-style: none"> --}}
               <p style="font-size:20px">Manufacturer:<span class="text-muted"> {{$vehicle->getProperty($vehicle_id,'manufacturer')}}</span> </p>
               <p style="font-size:20px">License Plate:<span class="text-muted"> {{$vehicle->getProperty($vehicle_id,'license_plate')}}</span> </p>
               <p style="font-size:20px">Vehicle Class:<span class="text-muted"> {{ VehicleClass::getById($vehicle->vehicle_class_id) }}</span> </p>
             {{-- </ul> --}}
  
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Drivers</label>
                  <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{ $vehicle_id }}">
                    <select class="form-control assign-to-driver" name="assigntodriver" id="assign-to-driver" class="height:50px">
                      <option value="">Select Driver</option>
                      @php
                          $d = new Driver;
                      @endphp
                        @forelse ($drivers as $item)
                        <option value="{{ $item->user_id }}">{{ $d->fullName($item->user_id) }}</option>
                        @empty
                        <option value="">No Driver Available</option>
                        @endforelse
                   
                        
                    </select>
                    @error('assigntodriver')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                    The driver field is required.
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
      $('.assign-to-driver').select2()
    });


</script>
 @endsection