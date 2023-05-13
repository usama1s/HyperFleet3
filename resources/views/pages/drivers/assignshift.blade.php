@php
    $user = App\Models\User::find($user_id);
@endphp

@extends('layouts.app')

@section('title', 'Assign Shift')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
<li class="breadcrumb-item"><a href="{{ url('/drivers') }}">Drivers </a></li>
    <li class="breadcrumb-item active">Assign Shift</li>
    
  </ol>

  @endsection  

@section('content')

<form method="POST" action="{{ route('drivers.assignshift')}}">
@csrf
{{-- ROW ONE ADD NEW VEHICLE --}}
<div class="row">
    <div class="col-md-12">
          <!-- SELECT2 EXAMPLE -->
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">Assign Shift to <strong> <h3 class="mt-2">{{ $user->fullname() }} </h3></strong></h3>
  
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Shifts</label>
                    
                  <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}">
                    <select class="form-control assign-to-vehicle" name="assignshift" id="assignshift" class="height:50px">
                      <option value="">Select Shift</option>
                    
                        @forelse ($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }} ( {{ date("g:i a", strtotime($shift->start)) }} - {{  date("g:i a", strtotime($shift->end)) }}) </option>
                        @empty
                        <option value="">No Shift Available</option>
                        @endforelse
                   
                        
                    </select>
                    @error('assignshift')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                    The Shift field is required.
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