@extends('layouts.app')

@section('title', 'User Profile')


@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home </a></li>
    <li class="breadcrumb-item active">Profile</li>    
</ol>

  @endsection  

@section('content')
<div class="container">
<form method="POST" action="{{ route('admin.profileupdate') }}" autocomplete="off" enctype="multipart/form-data">
@csrf
@method("POST")

<div class="row">
  @if (Auth::user()->role!=1)
      
    <div class="col-md-3">
    <div class="form-group">
      <label>Profile Image:</label>
      <br>
      <div style="width:90%; height:200px;">
        <div class="imgUploader">
          <input class="form-control imgUploaderinput" type='file' name="image" id="s_img_edit" alt="Click"/>
          @if ($user->role==3)
            <img class="imgUploaderImg" id="s_img_preview_edit" src="{{  asset('public/storage/assets/suppliers') }}/{{ auth()->user()->supplier->image }}" alt="" />

            @elseif($user->role==2)            
            <img class="imgUploaderImg" id="s_img_preview_edit" src="{{ asset('public/assets/staff') }}/{{$user->staff->image}}" alt="" />  

            @else <p>no image</p>                                                             
          @endif
          
          
      </div>
      </div>
    <p class="text-center">Click on image to upload.</p>
      @error('driver_image')
      <div class="invalid-feedback" style="display:block;" role="alert">
        <strong>{{ $message }}</strong> 
      </div>
      @enderror
    </div>   
   
    </div>
    @else  
    <div class="col-md-2">
    </div>
@endif
<div class="col-md-9">
  <h4 class="semi-bold no-margin">Personal Information</h4>
  <div class="user-info-box">
    <div class="row add-top">
          <div class="col-md-4">
            <label>First Name</label>
              <input type="text" class="form-control"  value="{{$user->first_name}}" name="first_name">
              @error('first_name')
              <div class="invalid-feedback" style="display:block;" role="alert">
               <strong>{{ $message }}</strong> 
              </div>
              @enderror
          </div>
          <div class="col-md-4">
            <label>Last Name</label>
              <input type="text" class="form-control"  value="{{$user->last_name}}" name="last_name">
              @error('last_name')
              <div class="invalid-feedback" style="display:block;" role="alert">
                <strong>{{ $message }}</strong> 
              </div>
              @enderror
          </div>
          <div class="col-md-4">
            <label>Email</label>
              <input type="text" class="form-control"  value="{{$user->email}}"  name="email">
              @error('email')
              <div class="invalid-feedback" style="display:block;" role="alert">
                <strong>{{ $message }}</strong> 
              </div>
              @enderror
          </div>
          
      </div>

      <br>
      <h4 class="semi-bold add-top-large">Update Login Credentials</h4>
      <p class="text-info">Leave Password Fields empty if you don't want to change password</p>
      
      <div class="row add-top">
      
        <div class="col-md-4">
          <label>Old Password</label>
          <input name="pre_password" type="password" class="form-control" id="password">
          @error('pre_password')
          <div class="invalid-feedback" style="display:block;" role="alert">
            <strong>{{ $message }}</strong> 
          </div>
          @enderror
        </div>

        <div class="col-md-4">
            <label>New Password</label>
            <input name="password" type="password" class="form-control" id="password" autocomplete="new-password" autocomplete="off">
            @error('password')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong> 
            </div>
            @enderror
        </div>
          
        <div class="col-md-4">
            <label>Confirm Password</label>
            <input name="password_confirmation" type="password" class="form-control" id="confirmpassword" autocomplete="off">
            @error('confirmpassword')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong> 
            </div>
            @enderror
        </div>
      </div>
      <br>
      <div class="row add-top">
      
        <div class="col-md-4">
            
            <input name="submit" type="submit" class="btn btn-success" value="Save Profile" autocomplete="off">
           
        </div>
       
      </div>
      </div>
      
    
  </div>
</div>


</div>
</form>
</div>
@endsection

@section('js')
<script>
   $("#s_img_edit").change(function() {
        readURL(this,"#s_img_preview_edit");
      });


</script>
 @endsection