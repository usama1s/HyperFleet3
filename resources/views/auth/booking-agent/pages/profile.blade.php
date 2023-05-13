@extends('auth.booking-agent.layouts.app')

@section('title', 'Booking Agent Profile')


@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/driver')}}">Home </a></li>
    <li class="breadcrumb-item active">Profile</li>
    
  </ol>

  @endsection  

@section('content')
<div class="container">
<form method="POST" action="{{ route('my-booking-agent.profileupdate') }}" autocomplete="off" enctype="multipart/form-data">
@csrf
@method("POST")

<div class="row">
<div class="col-md-3">
<div class="form-group">
  <label>Profile Image:</label>
  <br>
  <div style="width:240px;"><center>
    <div class="imgUploader">
      <input class="form control imgUploaderinput" type='file' name="agent_image" id="d_img_edit" alt=""/>
      <img style="width:auto; height:200px;" class="imgUploaderImg" id="d_img_preview_edit" src="{{ $user->bookingAgent->getProfileImage() }}" alt="" />
   </div></center>
  </div>
 <p class="text-center">Click on image to upload. &nbsp; &nbsp; &nbsp; </p>
  @error('agent_image')
  <div class="invalid-feedback" style="display:block;" role="alert">
    {{ $message }}
  </div>
  @enderror
</div>   
   
</div>
<div class="col-md-9">
  <h4 class="semi-bold no-margin">Personal Information</h4>
  <div class="user-info-box">
    <div class="row add-top">
          <div class="col-md-4">
            <label>First Name</label>
              <input type="text" class="form-control"  value="{{$user->first_name}}" name="first_name">
              @error('first_name')
              <div class="invalid-feedback" style="display:block;" role="alert">
                {{ $message }}
              </div>
              @enderror
          </div>
          <div class="col-md-4">
            <label>Last Name</label>
              <input type="text" class="form-control"  value="{{$user->last_name}}" name="last_name">
              @error('last_name')
              <div class="invalid-feedback" style="display:block;" role="alert">
                {{ $message }}
              </div>
              @enderror
          </div>
          <div class="col-md-4">
            <label>Email</label>
              <input type="text" class="form-control"  value="{{$user->email}}"  name="email">
              @error('email')
              <div class="invalid-feedback" style="display:block;" role="alert">
                {{ $message }}
              </div>
              @enderror
          </div>          
          
      </div><br/>
      
      <div class="row add-top">
      <div class="col-md-4">
            <label>Address</label>
              <input type="text" class="form-control"  value="{{$user->bookingAgent->address}}" name="address">
              @error('address')
              <div class="invalid-feedback" style="display:block;" role="alert">
                {{ $message }}
              </div>
              @enderror
          </div>
      <div class="col-md-4">
            <label>Contact Number</label>
              <input  type="text" class="form-control"  value="{{$user->contact_no}}" name="contact_no">
              @error('contact_no')
              <div class="invalid-feedback" style="display:block;" role="alert">
                {{ $message }}
              </div>
              @enderror
          </div>         
          
      </div>
      
      <br>
      <h4 class="semi-bold add-top-large">Update Login Credentials:</h4>
      <p class="text-info">Leave Password Fields empty if you don't want to change password</p>
      
      <div class="row add-top">
      
        <div class="col-md-4">
            <label>Password</label>
            <input name="password" type="password" class="form-control" id="password" autocomplete="new-password" value="">
            @error('password')
            <div class="invalid-feedback" style="display:block;" role="alert">
              {{ $message }}
            </div>
            @enderror
        </div>
          
        <div class="col-md-4">
            <label>Confirm Password</label>
            <input name="password_confirmation" type="password" class="form-control" id="confirmpassword" autocomplete="new-password">
            @error('confirmpassword')
            <div class="invalid-feedback" style="display:block;" role="alert">
              {{ $message }}
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
   $("#d_img_edit").change(function() {
        readURL(this,"#d_img_preview_edit");
      });


</script>
 @endsection