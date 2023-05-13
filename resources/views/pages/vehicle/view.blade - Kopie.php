@php
    use App\Models\VehicleClass;
    $driver = App\Models\User::find($vehicle->driver_id);
@endphp
@extends('layouts.app')

@section('title', 'View Vehicle')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home </a></li>
<li class="breadcrumb-item"><a href="{{url('vehicles')}}">Vehicles </a></li>
    <li class="breadcrumb-item active">View</li>

  </ol>

  @endsection

@section('content')

<div class="row">
  <div class="col-md-12">

    @can('vehicle-edit')
    <a class="btn btn-info float-right" href="{{url('vehicles')}}/{{$vehicle->id}}/edit" title="edit" id="edit-btn">Edit</a>
    @endcan

        <h3>
          <strong>Status: </strong>
       @if($vehicle->status == null)
            <span class='text-danger'>No Driver Assigned</span>

        @elseif($vehicle->status == "available")
          <span class='text-warning'>Available For Booking</span>

        @elseif($vehicle->status == "assigned")
        <span class='text-success'>Booking Assigned</span>

        @elseif($vehicle->status == "booked")
          <span class='text-success'>Booked</span>

        @elseif($vehicle->status == "maintenance")
          <span class='text-info'>Vehicle is under maintenance</span>
        @else
          Unknown Status
        @endif

        </h3>
  </div>
</div>
<div class="row">
  <div class="col-md-3">

    <!-- Profile Image -->
    <div class="card card-primary card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <img class="profile-user-img img-fluid" style="width: unset;" src="{{ asset('public/assets/vehicles/'. $vehicle->image ) }}" alt="User profile picture">
        </div>

      <h3 class="profile-username text-center">{{ $vehicle->manufacturer }} {{ $vehicle->car_model }}</h3>

        <p class="text-muted text-center">License Plate # {{ $vehicle->license_plate }}</p>

        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Vehicle Manufacturer</b> <a class="float-right">{{ $vehicle->manufacturer }}</a>
          </li>
          <li class="list-group-item">
            <b>Model</b> <a class="float-right">{{ $vehicle->car_model }}</a>
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
            <!-- <b>Price</b> <a class="float-right">{{$vehicle->price }}</a> -->
            <b>Price</b> <a class="float-right">{{@$vehicle->pricing->title}}</a>
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

  <div class="col-md-3">
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

  <div class="col-md-3">
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

 <div class="col-md-3">
  <!-- About Me Box -->
  <div class="card card-primary">
   <div class="card-header">
     <h3 class="card-title">Driver Detail</h3>
   </div>
   <!-- /.card-header -->
   <div class="card-body">
     @if (!is_null($vehicle->driver_id))

     <strong><i class="far fa-calendar-minus mr-1"></i> Name: </strong>

     <span class="text-muted">
      {{ $driver->fullName() }}
     </span>

     <hr>

     <strong><i class="far fa-calendar-minus mr-1"></i> Contact No.: </strong>

     <span class="text-muted">
      {{ $driver->contact_no }}
     </span>

     <hr>
     <embed src="{{ asset('public/assets/drivers/'. $driver->driver->driver_image ) }} " style="width:100%">
      <a target="_blank" href="{{ asset('public/assets/drivers/'. $driver->driver->driver_image ) }}">View Driver Profile</a>
      @else
      No Driver assigned.
@canany(['vehicle-edit', 'vehicle-create'])
      <a href="{{ url('vehicles/assign-to-driver')}}/{{$vehicle->id}}">Assign to Driver</a>
@endcanany
      @endif
   </div>
   <!-- /.card-body -->
 </div>
 <!-- /.card -->
</div>
</div>
{{-- /.row --}}

@endsection

@section('js')
<script>
    $(document).ready(function(){
      $('.vehicle_class_id').select2();

      // $(window).dblclick(function(){
      //   var href = $("#edit-btn").attr("href");
      //   window.location = href;
      // })
    });


</script>
 @endsection
