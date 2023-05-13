@php
    use App\Models\VehicleClass;
@endphp
@extends('layouts.app')

@section('title', 'Edit Driver')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
    <li class="breadcrumb-item"><a href="{{url('drivers')}}">Drivers </a></li>
    <li class="breadcrumb-item active">Edit</li>

  </ol>

  @endsection

@section('content')
<div class="container">
<form method="POST" action="{{ route('drivers.update',$user->user_id)}}" enctype="multipart/form-data">
@method('put')
@csrf
{{-- ROW ONE Vehicle General Information --}}
<div class="row ">
    <div class="col-md-12">
          <!-- SELECT2 EXAMPLE -->
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">Driver General Information</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>

              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row vehicle_row even">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>First Name</label>
                      <input  class="form-control" type="text" name="first_name" id="first_name" value="{{$user->first_name}}">

                      @error('first_name')
                      <div class="invalid-feedback" style="display:block;" role="alert">
                        {{ $message }}
                      </div>
                      @enderror
                    </div>
                  </div>
                <div class="col-md-6">
                  <div class="form-group">

                    <label>Last Name</label>
                    <input  class="form-control" type="text" name="last_name" id="last_name" value="{{$user->last_name}}">

                    @error('last_name')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                      {{ $message }}
                    </div>
                    @enderror

                  </div>

              </div>

                </div>
                <!-- /.col -->

                <div class="row vehicle_row odd">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email</label>
                      <input  class="form-control" type="text" name="email" id="email" placeholder="Email" value="{{ $user->email }}">
                      @error('email')
                        <div class="invalid-feedback" style="display:block;" role="alert">
                          {{ $message }}
                        </div>
                        @enderror
                    </div>
                  </div>
                  <!-- /.col -->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Contact</label>
                      <input  class="form-control" type="text" name="contact_no"  value="{{$user->contact_no}}" id="contact">
                        @error('contact_no')
                          <div class="invalid-feedback" style="display:block;" role="alert">
                            {{ $message }}
                          </div>
                        @enderror
                    </div>
                  </div>
                </div>
                <!-- /.col -->
              <div class="row vehicle_row odd">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Address</label>
                        <input  class="form-control" type="text" name="address"  value="{{$user->address}}" id="address">
                        @error('address')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Password</label>
                        <input  class="form-control @error('password') is-invalid @enderror" type="password" name="password"  value="{{ old('password') }}" id="password" placeholder="Password">
                        @error('password')
                            <div class="invalid-feedback" style="display:block;" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
              </div>
                <!-- /.col -->
              <div class="row vehicle_row odd">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Payment Based on</label><br/>
                     <select class="form-control" name="payment_type" id="payment_type">
                       <option value="commission" {{ $user->payment_type == "commission" ? "selected" : "" }}>Commission</option>
                       <option value="fixed" {{ $user->payment_type == "fixed" ? "selected" : "" }}>Fixed</option>
                     </select>
                      @error('payment_type')
                        <div class="invalid-feedback" style="display:block;" role="alert">
                          <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                  </div>
                  <!-- /.col -->

                  <div class="col-md-3" id="commission-field">
                    <div class="form-group">
                      <label>Commission</label><br/>
                      <input  class="form-control @error('commission') is-invalid @enderror" type="text" name="commission"  value="{{$user->amount}}" id="commission" placeholder="Commission (%)">
                        @error('commission')
                          <div class="invalid-feedback" style="display:block;" role="alert">
                            <strong>{{ $message }}</strong>
                          </div>
                        @enderror
                    </div>
                  </div>

                  <div class="col-md-3" id="fixed-field">
                    <div class="form-group">
                      <label>Fixed Amount</label><br/>
                      <input  class="form-control @error('fixed') is-invalid @enderror" type="text" name="fixed"  value="{{$user->amount}}" id="fixed" placeholder="Fixed Amount">
                        @error('fixed')
                          <div class="invalid-feedback" style="display:block;" role="alert">
                            <strong>{{ $message }}</strong>
                          </div>
                        @enderror
                    </div>
                  </div>

              <div class="col-md-3">
                  <div class="form-group">
                    <label>License Expiry</label><br/>
                    <input  class="form-control datetimepicker-input @error('license_expiry') is-invalid @enderror" type="text" name="license_expiry"  id="license_expiry" placeholder="License Expiry" data-toggle="datetimepicker" data-target="#license_expiry" autocomplete="off">
                    @error('license_expiry')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                      <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label>License File</label><br/>
                    <input  type="file" name="license_image"  value="{{ old('license_image') }}" id="license_image">
                    <a target="_blank" href="{{ asset('public/assets/drivers/license_image')}}/{{ $user->license_image }}">{{ $user->license_image }}</a>
                    @error('license_image')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                      <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                  </div>
                </div>
                </div>
                <br/>
                <!-- /.row -->

              <div class="row vehicle_row odd">

                <div class="col-md-4">
                  <div class="form-group">
                    <label>Driver Image</label>
                    <br>
                    <div class="imgUploader"  style="width:200px; height:200px;">
                      <input class="form control imgUploaderinput" type='file' name="driver_image" id="d_img_edit" />
                      <img class="imgUploaderImg" id="d_img_pre_edit" src="{{asset('public/assets/drivers')}}/{{$user->driver_image}}" alt="" />
                    </div>
                    @error('driver_image')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                      <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="d-flex justify-content-end" style="margin: 50px 20px;">
                      <input class="btn btn-lg btn-success" type="submit" value="Save" style="width:200px">
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
    </div>
  </div>

</form>
</div>
@endsection

@section('js')
<script>

var val =$("#payment_type").val();

if(val =="fixed"){
  $("#commission-field").hide();
  $("#fixed-field").show();
}else{
  $("#commission-field").show();
  $("#fixed-field").hide();
}

    $(document).ready(function(){

      $('#license_expiry').datetimepicker({
                    format: 'YYYY-MM-DD',
                    defaultDate: '{{ $user->license_expiry}}'
      });

/*
$('#rta_card_expiry').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: '{{$user->rta_card_expiry}}'
      });

      $('#emirates_expiry').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: '{{$user->emirates_expiry}}'
      });
*/

$("#payment_type").change(function(){
  var val = $(this).val();

  if(val =="fixed"){
    $("#commission-field").hide();
    $("#fixed-field").show();
  }else{
    $("#commission-field").show();
    $("#fixed-field").hide();
  }
});

$("#d_img_edit").change(function() {
        readURL(this,"#d_img_pre_edit");
      });
    });
</script>
 @endsection
