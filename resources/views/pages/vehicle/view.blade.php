@php
    use App\Models\VehicleClass;
    use App\Models\Supplier;
    $driver = App\Models\User::find($vehicle->driver_id);
    $sup_user = App\Models\User::find($vehicle->supplier_id);
    $sup_id = json_decode($sup_user)->id;
    $supplier_all = Supplier::where('user_id',$sup_id)->first();
    $supplier = json_decode($supplier_all)->company_name;    
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
<style>
.tab-content>.active {
    display: flex; flex-wrap: wrap;
}</style>
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
          <span class='text-info'>Under Maintenance</span>
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
            <b>Supplier</b> <a class="float-right">{{ $supplier }}</a>
          </li>
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

<!--   <div class="col-md-3"> -->
  <div class="col-md-9">

     <div class="card card-primary card-outline card-outline-tabs" style="border-color:transparent">
        <div class="card-header p-0 border-bottom-0">
           <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
              <li class="nav-item">
                 <a class="nav-link active" id="custom-tabs-three-documents-tab" data-toggle="pill" href="#custom-tabs-three-documents" role="tab" aria-controls="custom-tabs-three-documents" aria-selected="false">Documents</a>
              </li>            
              <li class="nav-item">
               <a class="nav-link" id="custom-tabs-three-bookings-tab" data-toggle="pill" href="#custom-tabs-three-bookings" role="tab" aria-controls="custom-tabs-three-bookings" aria-selected="true">Bookings</a>
            </li>
           </ul>
        </div>
        <div class="card-body">
           <div class="tab-content" id="custom-tabs-three-tabContent">
              <div style="overflow-x: auto;" class="tab-pane fade active show" id="custom-tabs-three-documents" role="tabpanel" aria-labelledby="custom-tabs-three-documents-tab">

     <!-- About Me Box -->
     <div class="col-md-3">
     <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Registration Details</h3>
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
       <h3 class="card-title">Insurance Details</h3>
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
     <!-- About Me Box -->
     <div class="col-md-3">
     <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">VTC Permit Details</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <strong><i class="far fa-calendar-minus mr-1"></i> Expiry</strong>

        <span class="text-muted">
         {{ $vehicle->vtc_expiry}}
        </span>

        <hr>

        <strong><i class="far fa-calendar-minus mr-1"></i> Company</strong>

        <span class="text-muted">
         {{ $vehicle->vtc_detail}}
        </span>
        
        <hr>
        <embed src="{{ asset('public/assets/vehicles/vtc/'. $vehicle->vtc_file ) }} " style="width:100%">
          <a target="_blank" href="{{ asset('public/assets/vehicles/vtc/'. $vehicle->vtc_file ) }}">View VTC Document</a>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>

  <div class="col-md-3">
    <!-- About Me Box -->
    <div class="card card-primary">
     <div class="card-header">
       <h3 class="card-title">Vehicle Inspection</h3>
     </div>
     <!-- /.card-header -->
     <div class="card-body">
       <strong><i class="far fa-calendar-minus mr-1"></i> Expiry</strong>

       <span class="text-muted">
        {{ $vehicle->inspection_expiry}}
       </span>

       <hr>

       <strong><i class="far fa-calendar-minus mr-1"></i> Company</strong>

       <span class="text-muted">
        {{ $vehicle->inspection_detail}}
       </span>
       
       <hr>
       <embed src="{{ asset('public/assets/vehicles/inspection/'. $vehicle->inspection_file ) }} " style="max-height: 150px;width:auto;"><br/>
        <a target="_blank" href="{{ asset('public/assets/vehicles/inspection/'. $vehicle->inspection_file ) }}">View Inspection Document</a>
     </div>
     <!-- /.card-body -->
   </div>
   <!-- /.card -->
 </div>
 <div class="col-md-6">
  <!-- About Me Box -->
  <div class="card card-primary">
   <div class="card-header">
     <h3 class="card-title">Driver Detail</h3>
   </div>
   <!-- /.card-header -->
   <div class="card-body">
   <div style="width:50%;float:left;">
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
     <span class="text-muted">
      <a target="_blank" href="{{ url('drivers/'. $vehicle->driver_id ) }}">View Driver Profile</a>
     </span>
     </div>
   <div style="width:50%;float:left;text-align: center;">
<embed src="{{ asset('public/assets/drivers/'. $driver->driver->driver_image ) }}" style="max-width: 100%;max-height:200px;width:auto;"><br/>
      @else
      No Driver assigned.
@canany(['vehicle-edit', 'vehicle-create'])
      <a href="{{ url('vehicles/assign-to-driver')}}/{{$vehicle->id}}">Assign to Driver</a>
@endcanany
      @endif
      </span>
      
   </div>
   <!-- /.card-body -->
 </div>
 <!-- /.card -->
</div>
</div>
</div>
            
<div style="overflow-x: auto;" class="tab-pane fade" id="custom-tabs-three-bookings" role="tabpanel" aria-labelledby="custom-tabs-three-bookings-tab">

Bookings

</div>
        </div>
        
        
        
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