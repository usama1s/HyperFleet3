@extends('layouts.app')

@section('title', 'Create Staff User')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
    <li class="breadcrumb-item"><a href="{{url('staff')}}">Staff </a></li>
    <li class="breadcrumb-item active">Create</li>

</ol>

@endsection

@section('content')
<div class="container">
    <form method="POST" action="{{ route('staff.store')}}" enctype="multipart/form-data">
        @csrf
        {{-- ROW ONE Vehicle General Information --}}
        <div class="row ">
            <div class="col-md-12">
                <!-- SELECT2 EXAMPLE -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">General Information</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                    class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                    class="fas fa-remove"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row vehicle_row even">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name"
                                        value="{{ old('first_name') }}" id="first_name" placeholder="First Name">

                                    @error('first_name')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label>Last Name</label>
                                    <input class="form-control @error('last_name') is-invalid @enderror" type="text" name="last_name"
                                        value="{{ old('last_name') }}" id="last_name" placeholder="Last Name">

                                    @error('last_name')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
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
                                    <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" value="{{ old('email') }}"
                                        id="email" placeholder="Email" value="{{ old('email') }}">
                                    @error('email')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact No.</label>
                                    <input class="form-control @error('contact_no') is-invalid @enderror" type="text" name="contact_no" value="{{ old('contact_no') }}"
                                        id="contact_no" placeholder="Contact No.">
                                    @error('contact_no')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row vehicle_row even">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-control user_role" name="user_role" id="user_role"
                                        class="height:50px">
                                        <option value="">Select Role</option>
                                        @foreach ( Spatie\Permission\Models\Role::all() as $item)
                                        @if (Request::old('user_role') == $item->name)
                                        <option value="{{ $item->name }}" selected>{{ $item->name }}</option>
                                        @else
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endif

                                        @endforeach


                                    </select>


                                    @error('user_role')
                                    <div class="invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.col -->
                  
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" value="{{ old('password') }}" autocomplete="new-password">
                                    @error('password')
                                    <div class=" invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Password Confirm</label>
                                    <input class="form-control @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                                    @error('password_confirmation')
                                    <div class=" invalid-feedback" style="display:block;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <!-- /.row -->
                        <div class="row vehicle_row even">
                            <div class="col-md-3">
                                <div class="form-group">
                                <label>Image:</label>
                                <br>
                                <div class="imgUploader"  style="width:200px; height:200px;">
                                    <input class="form control imgUploaderinput" type='file' name="image" id="d_img" />
                                    <img class="imgUploaderImg" id="d_img_preview" src="{{asset('public/images/default-user.jpg')}}" alt="" />
                                </div>
                                @error('image')
                                <div class="invalid-feedback" style="display:block;" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                                </div>                  
                            </div>
                        </div>
                        <div class="row vehicle_row even">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end" style="margin: 50px 20px;">
                                    <input class="btn btn-lg btn-success" type="submit" value="Save"
                                        style="width:200px">
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
            });

        $('#license_expiry').datetimepicker({
            format: 'YYYY-MM-DD',
            defaultDate: '{{old("license_expiry")}}'
        });

        $('#rta_card_expiry').datetimepicker({
            format: 'YYYY-MM-DD',
            defaultDate: '{{old("rta_card_expiry")}}'
        });

        $('#emirates_expiry').datetimepicker({
            format: 'YYYY-MM-DD',
            defaultDate: '{{old("emirates_expiry")}}'
        });

        $("#d_img").change(function() {
            readURL(this, "#d_img_preview");
        });
    });
</script>
@endsection