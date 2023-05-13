
@extends('pages.customer.layouts.app')

@section('content')


<div class="container">
<form method="POST" action="{{ route('customer.profileupdate') }}" autocomplete="off">
@csrf
@method("POST")

<div class="row">
<div class="col-md-12">
  <h4 class="semi-bold no-margin" style="padding-top: 80px;">Personal Information</h4>
  <div class="user-info-box">
    <div class="row add-top">
          <div class="col-md-6 form-group">
            <label>First Name</label>
              <input type="text" class="form-control"  value="{{$user->first_name}}" name="first_name">
              @error('first_name')
              <div class="invalid-feedback" style="display:block;" role="alert">
               <strong>{{ $message }}</strong> 
              </div>
              @enderror
          </div>
          <div class="col-md-6 form-group">
            <label>Last Name</label>
              <input type="text" class="form-control" value="{{$user->last_name}}" name="last_name">
              @error('last_name')
              <div class="invalid-feedback" style="display:block;" role="alert">
                <strong>{{ $message }}</strong> 
              </div>
              @enderror
          </div>
              
      </div>

      <div class="row">
        <div class="col-md-6 form-group">
          <label>Email</label>
            <input type="text" class="form-control"  value="{{$user->email}}" name="email">
            @error('email')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong> 
            </div>
            @enderror
        </div>

        <div class="col-md-6 form-group">
          <label>Contact Number</label>
            <input type="text" class="form-control"  value="{{$user->contact_no}}"  name="contact_no">
            @error('contact_no')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong> 
            </div>
            @enderror
        </div>
      </div>

      <br>
      <h4 class="semi-bold add-top-large">Updated Login Credentials</h4>
      <p class="text-info">Leave Password Fields empty if you don't want to change password</p>
      
      <div class="row add-top">

        <div class="col-md-4">
            <label>Old Password</label>
            <input name="pre_password" type="password" class="form-control" autocomplete="off">
            @error('pre_password')
            <div class="invalid-feedback" style="display:block;" role="alert">
              <strong>{{ $message }}</strong> 
            </div>
            @enderror
        </div>
      
        <div class="col-md-4">
            <label>New Password</label>
            <input name="password" type="password" class="form-control" id="password" autocomplete="new-password">
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
