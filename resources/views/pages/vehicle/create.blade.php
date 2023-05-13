@php
use App\Models\VehicleClass;
@endphp
@extends('layouts.app')

@section('title', 'Add New Vehicle')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
  <li class="breadcrumb-item"><a href="{{url('vehicles')}}">Vehicles </a></li>
<li class="breadcrumb-item active">Create</li>

</ol>

@endsection

@section('content')
<div class="container">

  <form method="POST" action="{{ route('vehicles.store')}}" enctype="multipart/form-data">
    @csrf
    {{-- ROW ONE Vehicle General Information --}}
    <div class="row ">
      <div class="col-md-12">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Vehicle General Information</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                  class="fas fa-minus"></i></button>
             
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row vehicle_row even">
              <div class="col-md-6">
                <div class="form-group">
                  <label>License Plate</label>
                  <input class="form-control @error('license_plate') is-invalid @enderror" type="text" name="license_plate" value="{{ old('license_plate') }}"
                    id="license_plate" placeholder="License Plate #">

                  @error('license_plate')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group">
                  <label>Vehicle Class</label><br/>
                  <select class="form-control vehicle_class_id" name="vehicle_class_id" id="vehicle_class_id" class="height:50px">
                    <option value="">Select Vehicle Class</option>
                    @foreach ( VehicleClass::all() as $item)
                    @if (Request::old('vehicle_class_id') == $item->id)
                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                    @else
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endif
                    @endforeach
                    </select>
                  @error('vehicle_class_id')
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
                  <label>Manufacturer</label>
                  <input class="form-control @error('car_manufacturer') is-invalid @enderror" type="text" name="car_manufacturer" value="{{ old('car_manufacturer') }}"
                    id="car_manufacturer" placeholder="Car Manufacturer" value="{{ old('car_manufacturer') }}">
                  @error('car_manufacturer')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-3">
                <div class="form-group">
                  <label>Model</label>
                  <input class="form-control @error('car_model') is-invalid @enderror" type="text" name="car_model" value="{{ old('car_model') }}" id="car_model"
                    placeholder="Car Model">
                  @error('car_model')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-3">
                <div class="form-group">
                  <label>Year</label>
                  <input class="form-control @error('car_year') is-invalid @enderror" type="text" name="car_year" value="{{ old('car_year') }}" id="car_year"
                    placeholder="Car Year">
                  @error('car_year')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-3">
                <div class="form-group">
                  <label>Color</label>
                  <input class="form-control @error('car_color') is-invalid @enderror" type="text" name="car_color" value="{{ old('car_color') }}" id="car_color"
                    placeholder="Car Color">
                  @error('car_color')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

            </div>

            <div class="row vehicle_row even">

              <div class="col-md-4">
                <div class="form-group">
                  <label>Insurance Expiry</label><br/>
                  <input class="form-control datetimepicker-input @error('insurance_expriy') is-invalid @enderror" type="text" name="insurance_expriy"
                    id="insurance_expriy" placeholder="Insurance Expriy" data-toggle="datetimepicker"
                    data-target="#insurance_expriy" autocomplete="off">
                  @error('insurance_expriy')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-4">
                <div class="form-group">
                  <label>Insurance Detail</label><br/>
                  <input type="text" class="form-control @error('insurance_detail') is-invalid @enderror" name="insurance_detail" value="{{ old('insurance_detail') }}"
                    id="insurance_detail" placeholder="Insurance Detail" />
                  @error('insurance_detail')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-4">
                <div class="form-group">
                  <label>Insurance File Upload</label><br/>
                  <input type="file" name="insurance_file" value="{{ old('insurance_file') }}" id="insurance_file"
                    placeholder="Insurance File">
                  @error('insurance_file')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

            </div>
            <!-- /.row -->

            <div class="row vehicle_row odd">

              <div class="col-md-4">
                <div class="form-group">
                  <label>Registration Expiry (Permiso de Circulaci&oacute;n)</label><br/>
                  <input class="form-control datetimepicker-input @error('registration_expriy') is-invalid @enderror" type="text" name="registration_expriy"
                    id="registration_expriy" placeholder="Registration Expriy" data-toggle="datetimepicker"
                    data-target="#registration_expriy" autocomplete="off">
                  @error('registration_expriy')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-4">
                <div class="form-group">
                  <label>Registration Detail</label><br/>
                  <input type="text" class="form-control @error('registration_detail') is-invalid @enderror" name="registration_detail"
                    value="{{ old('registration_detail') }}" id="registration_detail"
                    placeholder="Registration Detail" />
                  @error('registration_detail')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-4">
                <div class="form-group">
                  <label>Registration File Upload</label><br/>
                  <input type="file" name="registration_file" value="{{ old('registration_file') }}"
                    id="registration_file" placeholder="Registration File">
                  @error('registration_file')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

            </div>
            <!-- /.row -->

            <div class="row vehicle_row even">

              <div class="col-md-4">
                <div class="form-group">
                  <label>VTC (Vehicle-for-hire permit) Expiry</label><br/>
                  <input class="form-control datetimepicker-input @error('vtc_expriy') is-invalid @enderror" type="text" name="vtc_expriy"
                    id="vtc_expriy" placeholder="VTC Expiry" data-toggle="datetimepicker"
                    data-target="#vtc_expriy" autocomplete="off">
                  @error('vtc_expriy')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-4">
                <div class="form-group">
                  <label>VTC Detail</label><br/>
                  <input type="text" class="form-control @error('vtc_detail') is-invalid @enderror" name="vtc_detail" value="{{ old('vtc_detail') }}"
                    id="vtc_detail" placeholder="VTC Detail" />
                  @error('vtc_detail')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-4">
                <div class="form-group">
                  <label>VTC Upload</label><br/>
                  <input type="file" name="vtc_file" value="{{ old('vtc_file') }}" id="vtc_file"  placeholder="VTC File">
                  @error('vtc_file')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

            </div>
            <!-- /.row -->

            <div class="row vehicle_row odd">

              <div class="col-md-4">
                <div class="form-group">
                  <label>Vehicle Inspection</label><br/>
                  <input class="form-control datetimepicker-input @error('inspection_expriy') is-invalid @enderror" type="text" name="inspection_expriy"
                    id="inspection_expriy" placeholder="Vehicle Inspection" data-toggle="datetimepicker"
                    data-target="#inspection_expriy" autocomplete="off">
                  @error('inspection_expriy')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-4">
                <div class="form-group">
                  <label>Vehicle Inspection Detail</label><br/>
                  <input type="text" class="form-control @error('inspection_detail') is-invalid @enderror" name="inspection_detail" value="{{ old('inspection_detail') }}" id="inspection_detail" placeholder="Inspection Detail" />
                  @error('inspection_detail')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-4">
                <div class="form-group">
                  <label>Inspection File Upload</label><br/>
                  <input type="file" name="inspection_file" value="{{ old('inspection_file') }}"
                    id="inspection_file" placeholder="Inspection File">
                  @error('inspection_file')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

            </div>
            <!-- /.row -->            

            <div class="row vehicle_row even">

              <div class="col-md-4">
                <div class="form-group">
                  <label>Seats</label><br/>
                  <input class="form-control @error('seats') is-invalid @enderror" type="number" name="seats" value="{{ old('seats') }}" id="seats"
                    placeholder="Seats">
                  @error('seats')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <div class="col-md-4">
                <div class="form-group">
                  <label>Luggage</label><br/>
                  <input class="form-control @error('luggage') is-invalid @enderror" type="number" name="luggage" value="{{ old('luggage') }}" id="luggage"
                    placeholder="Luggage">
                  @error('luggage')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->

              <!-- <div class="col-md-4">
                <div class="form-group">
                  <label>Price</label>
                  <input class="form-control @error('price') is-invalid @enderror" type="text" name="price" value="{{ old('price') }}" id="vehicle_price"
                    placeholder="Price">
                  @error('price')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div> -->
              <div class="col-md-4">
                <div class="form-group">
                  <label>Price</label><br/>
                  <select class="form-control price" name="price" id="vehicle_price" class="height:50px">
                    <option value="">Select Pricing</option>
                    @foreach ( $pricings as $pricing)
                      <option value="{{$pricing->id}}">{{$pricing->title}}</option>
                    @endforeach
                  </select>
                  @error('price')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row vehicle_row odd">
              <!-- /.col
              <div class="col-md-6">
                <div class="form-group">
                  <label>Description:</label>
                 
                  <textarea class="form-control @error('description') is-invalid @enderror" style="height:180px;" name="description" id="vehicle_description"
                    placeholder="Put the detail about Vehicle" style="height:150px;">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                      <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                </div>
              </div>
              /.col -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Vehicle Image:</label><br/>
                  <div class="imgUploader" style="width:200px; height:200px">
                    <input class="form control imgUploaderinput" type='file' name="image" id="v_img" />
                  <img class="imgUploaderImg" id="v_img_preview" src="{{asset('public/images/default-thumbnail.jpg')}}" alt="" />
                  </div>
                  @error('image')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>

              </div>

            </div>
            <!-- /.row -->
            <div class="row vehicle_row even">
              <div class="col-md-12">
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
  $(document).ready(function() {
    $(".form-control").on('focus',function(){

$(this).removeClass('is-invalid')
})

    $('.vehicle_class_id').select2();
    $('#vehicle_price').select2();
    $('#insurance_expriy').datetimepicker({
      format: 'YYYY-MM-DD',
      defaultDate: '{{old("insurance_expriy")}}'
    });
    $('#registration_expriy').datetimepicker({
      format: 'YYYY-MM-DD',
      defaultDate: '{{old("registration_expriy")}}'
    });
    $('#vtc_expriy').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: '{{old("vtc_expriy")}}'
      });
      $('#inspection_expriy').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: '{{old("inspection_expriy")}}'
      });

    $("#v_img").change(function() {
      readURL(this, "#v_img_preview");
    });
  });
</script>
@endsection