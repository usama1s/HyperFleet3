
@extends('layouts.app')

@section('title', 'Settings')

@section('breadcrumb')

<div class="page_sub_menu">
   
    <a class="btn btn-sm btn-success" href="#" id="save-setting-btn">Save Settings</a>
  {{-- <input type="submit" name="submit" value="Save Settings"> --}}
  
</div>

@endsection

@section('content')




<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" style="color:#343a40" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">General Information</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" style="color:#343a40" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Mail Server Information</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab" style="color:#343a40" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Tax/Currency Information</a>
  </li>
</ul>


  

<form action="{{ route('settings.save') }}" id="save-setting-form" method="post" enctype="multipart/form-data">
  @csrf
  @method('POST')
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <div class="col-md-12">
      <div class="card">
     
        <div class="card-body">
           
          <div class="row ">
            <div class="col-md-3">
              <div class="form-group">
                <label for="">Company Logo</label>
                <div class="imgUploader" style="max-width: 200px;height:200px;margin: 0px auto;width: 100%;">
                    <input class="form control imgUploaderinput" type='file' name="image" id="company_logo_select" />
                  <img class="imgUploaderImg" id="company_logo_select_preview" src="{{ config('app.logo') }}" alt="" />
                  </div>
                  @error('image')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    {{ $message }}
                  </div>
                  @enderror
            </div>

        
            </div>
          
            <div class="col-md-9">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="">Name</label>
                <input type="text" class="form-control" name="company_name" value="{{ config('app.name') }}">
                </div>

                <div class="form-group col-md-6">
                  <label for="">Address</label>
                <input type="text" class="form-control" name="company_address" value="{{ config('app.address') }}">
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-6">
                  <label for="">Contact</label>
                <input type="text" class="form-control" name="company_contact" value="{{ config('app.contact') }}">
                </div>

                <div class="form-group col-md-6">
                  <label for="">Email</label>
                <input type="email" class="form-control" name="company_email" value="{{ config('app.email') }}">
                </div>
                @error('company_email')
                <div class="invalid-feedback" style="display:block;" role="alert">
                    {{ $message }}
                </div>
                @enderror
              </div>

              <div class="row">
                <div class="form-group col-md-6">
                  <label for="">Favicon</label>
                
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="favicon" id="favicon">
                    <label class="custom-file-label" for="favicon">Favicon</label>
                  </div>
                </div>

                <div class="form-group col-md-6">
                  <label for="">Region</label>
                
                <select name="region" id="region" class="form-control select2js">
                  <option value="">Select Region</option>
                  @foreach (config('timezones') as $region => $timezone)
                        @if (config('app.region') == $region)
                        <option value="{{ $region }}" selected>{{ $region }}</option>
                      @else
                      <option value="{{ $region }}">{{ $region }}</option>
                      @endif
                  @endforeach
                  
                </select>
                </div>
              </div>

              
               <div class="form-group">
               <label for="">Timezone</label>

               @php
               $str = 'timezones.'.config('app.region');

               
            
               $timeszones = config($str);

               if(is_null($timeszones)){
                $timeszones = config('timezones.Asia');
               }

               

            @endphp
               
               <select name="timezone" id="timezone" class="form-control select2js">
                <option value="">Select Timezone</option>

             
                 @foreach (@$timeszones as $timezone => $timezone_value)

                
                      @if (config('app.timezone') == $timezone)
                      <option value="{{ $timezone }}" selected>{{ config('app.region').' - '. $timezone_value }}</option>
                      @else
                      <option value="{{ $timezone }}">{{ config('app.region').' - '. $timezone_value }}</option>
                      @endif
                     
                   
                 @endforeach
              </select>
               </div>

            </div>

          </div>  

        </div>
        <!-- /.card-body -->
        
      </div>
      <!-- /.card -->
    </div>
</div>

<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <div class="col-md-12">
        <div class="card">
     
          <div class="card-body">
           <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Mail Server Host</label>
                    <input type="text" class="form-control" name="mail_server_host" placeholder="Mail Server Host"  value="{{ config('mail.host') }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Mail Port</label>
                    <input type="text" class="form-control"  name="mail_server_port" placeholder="Hyper Booking"  value="{{ config('mail.port') }}">
                </div>
            </div>

           </div>
           <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label for="">Mail Username</label>
                  <input type="text" class="form-control"  name="mail_username" placeholder="Hyper Booking"  value="{{ config('mail.username') }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label for="">Mail Password</label>
                  <input type="password" class="form-control" name="mail_password" placeholder="Hyper Booking"  value="{{ config('mail.password') }}">
              </div>
            </div>
          </div>
        </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- ./col -->


  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
    <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-text-width"></i>
            Tax Information
          </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="form-group">
          

              <label for="">Enter Value Added Tax (VAT) </label>
            <div class="input-group">
             
              <input type="text" name="vat" id="vat" value="{{ config('vat') }}" class="form-control" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <span class="input-group-text" id="basic-addon2">%</span>
              </div>
              @error('vat')
              <div class="invalid-feedback" style="display:block;" role="alert">
                  {{ $message }}
              </div>
              @enderror
            </div>
            
            </div>
          </div>
      </div>
    </div>
    <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-text-width"></i>
               Currency Information
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
        <div class="form-group">
          
          <label for="">Select Currency</label>
          <select name="currency" id="currency" class="form-control">
            <option value="">Select Currency</option>

              <option value="USD" {{ (Setting::getMeta("currency") == "USD") ? "selected" : '' }}>US Dollar</option>
              <option value="CAD"  {{ (Setting::getMeta("currency") == "CAD") ? "selected" : '' }}>Canadian Dollar</option>
              <option value="AUD" {{ (Setting::getMeta("currency") == "AUD") ? "selected" : '' }}>Australian Dollar</option>
              <option value="EUR" {{ (Setting::getMeta("currency") == "EUR") ? "selected" : '' }}>Euro</option>
              <option value="GBP" {{ (Setting::getMeta("currency") == "GBP") ? "selected" : '' }}>Pound</option>
              <option value="JPY" {{ (Setting::getMeta("currency") == "JPY") ? "selected" : '' }}>Japenese Yen</option>
              <option value="AED" {{ (Setting::getMeta("currency") == "AED") ? "selected" : '' }}>Emirati Dirham</option>
              <option value="SR" {{ (Setting::getMeta("currency") == "SR") ? "selected" : '' }}>Saudi Riyal</option>

          </select>
          @error('currency')
          <div class="invalid-feedback" style="display:block;" role="alert">
              {{ $message }}
          </div>
          @enderror
        </div>
      </div>
    </div>
  </div>
</div>
  </div>
</div>
</form>

@endsection

@section('js')

<script>

$(".select2js").select2();

  $("#save-setting-btn").click(function(e){
    e.preventDefault();
    $("#save-setting-form").submit();
  });
 $("#company_logo_select").change(function() {
      readURL(this, "#company_logo_select_preview");
  });

  $("#favicon").change(function() {
    input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('link[rel="icon"]').attr("href", e.target.result);
            console.log( $('link[rel="icon"]'));
          };

        reader.readAsDataURL(input.files[0]);
    }
  });

  $('#region').change(function(){
    var value = $(this).val();
    $("#timezone").empty();
    $("#timezone").append(`<option value="">Loading..</option>`);
    $.ajax({
      url: '{{url("api/timezone-list")}}/'+value,
      method:"GET",
      success:function(result) {
        
        $("#timezone").empty();

        for(i in result){
          //console.log(value+ "=>" + result[value]);

          option = `<option value='${i}'>${value} - ${ result[i] }</option>`;
          $("#timezone").append(option);
          
        }

        
      }
    });
    
  });
</script>


@endsection