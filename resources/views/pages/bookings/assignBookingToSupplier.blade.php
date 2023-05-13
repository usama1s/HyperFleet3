@php
    use App\Models\Driver;
    $d = new Driver;

@endphp

@extends('layouts.app')

@section('title', 'Assign to Supplier')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
<li class="breadcrumb-item"><a href="{{ url('/bookings') }}">Booking </a></li>
    <li class="breadcrumb-item active">Assign Supplier</li>
    
  </ol>

  @endsection  

@section('content')

<form method="POST" action="{{ route('bookings.assignBookingToSupplier')}}">
@csrf
{{-- ROW ONE ADD NEW VEHICLE --}}
<div class="row">
    <div class="col-md-12">
          <!-- SELECT2 EXAMPLE -->
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">Assign Booking to Supplier</h3>
  
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Suppliers</label>
                   
                  <input type="hidden" name="booking_id" id="booking_id" value="{{$booking_id}}">
                    <select class="form-control assign-to-vehicle" name="supplier_id" id="booking_driver_id" class="height:50px">
                      <option value="">Select Supplier</option>
                      
                        @forelse ($users as $supplier)
                         
                    <option value="{{ $supplier->id }}">{{ $supplier->first_name }} {{ $supplier->last_name }} ({{ $supplier->supplier->company_name }}) </option>
                    
                        @empty
                          <option value="">No supplier Available</option>
                        @endforelse
                                           
                    </select>
                    @error('driver_id')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                    The supplier field is required.
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
      $('#booking_driver_id').select2()
    });


</script>
 @endsection