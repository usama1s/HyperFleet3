@php
    use App\Models\VehicleClass;


    
@endphp
@extends('auth.driver.layouts.app')

@section('title', 'My Vehicle')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/driver')}}">Home </a></li>
    <li class="breadcrumb-item active">Vehicle</li>
    
  </ol>

  @endsection  

@section('content')

@if (!is_null($vehicle))
<div class="row">
  <div class="col-md-4">

    <!-- Profile Image -->
    <div class="card card-primary card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <img class="profile-user-img img-fluid img-circle"
               src="{{ asset('public/assets/vehicles/'. $vehicle->image ) }}"
               alt="User profile picture">
        </div>

      <h3 class="profile-username text-center">{{ $vehicle->manufacturer }}</h3>

        <p class="text-muted text-center">License Plate # {{ $vehicle->license_plate }}</p>

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

  <div class="col-md-4">
     <!-- About Me Box -->
     <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Registration Detail</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <strong><i class="far fa-calendar-minus mr-1"></i> Expiry</strong>

        <span class="text-muted">
         {{ $vehicle->registration_expiry}}
        </span>

        <hr>

        <strong><i class="far fa-calendar-minus mr-1"></i> Company</strong>

        <span class="text-muted">
         {{ $vehicle->registration_detail}}
        </span>
        
        <hr>
        <embed src="{{ asset('public/assets/vehicles/registration/'. $vehicle->registration_file ) }} " style="width:100%">
          <a target="_blank" href="{{ asset('public/assets/vehicles/registration/'. $vehicle->registration_file ) }}">View Registration Document</a>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>

  <div class="col-md-4">
    <!-- About Me Box -->
    <div class="card card-primary">
     <div class="card-header">
       <h3 class="card-title">Insurance Detail</h3>
     </div>
     <!-- /.card-header -->
     <div class="card-body">
       <strong><i class="far fa-calendar-minus mr-1"></i> Expiry</strong>

       <span class="text-muted">
        {{ $vehicle->insurance_expiry}}
       </span>

       <hr>

       <strong><i class="far fa-calendar-minus mr-1"></i> Company</strong>

       <span class="text-muted">
        {{ $vehicle->insurance_detail}}
       </span>
       
       <hr>
       <embed src="{{ asset('public/assets/vehicles/insurance/'. $vehicle->insurance_file ) }} " style="width:100%">
        <a target="_blank" href="{{ asset('public/assets/vehicles/insurance/'. $vehicle->insurance_file ) }}">View Insurance Document</a>
     </div>
     <!-- /.card-body -->
   </div>
   <!-- /.card -->
 </div>

</div>
@else 

    <h1 class="text-center text-danger">You have no assigned Vehicle</h1>

@endif


{{-- /.row --}}

@endsection

@section('js')
<script>
    $(document).ready(function(){
      $('.vehicle_class_id').select2()
    });


</script>
 @endsection