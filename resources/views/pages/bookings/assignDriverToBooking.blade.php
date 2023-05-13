@php
    use App\Models\Driver;
    $d = new Driver;

    $design = config('design.booking.assigndriver');

@endphp

@extends('layouts.app')

@section('title', 'Assign Driver')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
<li class="breadcrumb-item"><a href="{{ url('/bookings') }}">Bookings </a></li>
    <li class="breadcrumb-item active">Assign Driver</li>
    
  </ol>

  @endsection  

@section('content')

<div class="row">
 
  <div class="col-md-2">
    <select id="design-selector" class="form-control">
      <option value="design1" {{ ($design == 'design1') ? 'selected' : '' }}> Design One</option>
      {{-- <option value="design2" {{ ($design == 'design2') ? 'selected' : '' }}> Design Two</option> --}}
    </select>
  </div>
</div>
<br>

@switch($design)
    @case('design1')
    @include("pages.bookings.templates.drivers.design1")
        @break
    @case('design2')
    @include("pages.bookings.templates.drivers.design2")
        @break
    @default
        <h1>No Design Found</h1>
@endswitch






@endsection

@section('js')
<script>
    $(document).ready(function(){
      $('#booking_driver_id').select2();
      $('#design_2_table').DataTable();

      $("#design-selector").change(function(){
        var selected = $(this).val();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
          url : '{{url("api/bookings/assign-driver/design") }}',
          method:"POST",
          data:{
            design:selected
          },
          success:function(result){
            //console.log(result);
            if(result == "true"){
              window.location.reload();
            }
          }

        });                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             ;       
      });

      
                
               
      
    });




</script>


 @endsection