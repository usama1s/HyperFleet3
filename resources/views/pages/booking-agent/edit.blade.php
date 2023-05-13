@extends('layouts.app')

@section('title', 'Edit Booking Agent')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
        <li class="breadcrumb-item"><a href="{{ route('booking-agent.index') }}">Booking Agent </a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

@endsection

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('booking-agent.update', $user->id) }}" enctype="multipart/form-data">
            @method('put')
            @csrf
            {{-- ROW ONE Vehicle General Information --}}

            <div class="row ">
              <div class="col-md-12">
                  <!-- SELECT2 EXAMPLE -->
                  <div class="card card-default">
                      <div class="card-header">
                          <h3 class="card-title">General Information</h3>

                          <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                          </div>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                          <div class="row vehicle_row even">
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label>First Name</label>
                                      <input class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name" value="{{ old('first_name') ?? $user->first_name }}" id="first_name" placeholder="First Name">
                                      @error('first_name')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label>Last Name</label>
                                      <input class="form-control @error('last_name') is-invalid @enderror" type="text" name="last_name" value="{{ old('last_name') ?? $user->last_name }}" id="last_name" placeholder="Last Name">
                                      @error('last_name')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label>Company Name</label>
                                      <input class="form-control @error('company_name') is-invalid @enderror" type="text" name="company_name" value="{{ old('company_name') ?? $user->bookingAgent->company_name  }}" id="company_name" placeholder="Company Name">
                                      @error('company_name')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label>Contact No.</label>
                                      <input class="form-control @error('contact_no') is-invalid @enderror" type="text" name="contact_no" value="{{ old('contact_no') ?? $user->contact_no  }}" id="contact_no" placeholder="Contact No.">
                                      @error('contact_no')
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
                                      <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" value="{{ old('email') ?? $user->email }}" id="email" placeholder="Email">
                                      @error('email')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>
                              <!-- /.col -->
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Password</label>
                                      <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" id="password" placeholder="Password" autocomplete="new-password"  autocomplete="off">
                                      @error('password')
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
                                  <div class="form-group">
                                      <label>Address</label>
                                      <input class="form-control @error('address') is-invalid @enderror" type="text" name="address" value="{{ old('address') ?? $user->bookingAgent->address  }}" id="address" placeholder="Address">
                                      @error('address')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>
                          </div>
                          <!-- /.row -->
                          <div class="row vehicle_row even">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Agreement</label>
                                      <input type="file" name="agreement"
                                          class="form-control @error('agreement') is-invalid @enderror" value="{{ old('agreement') }}" id="agreement" placeholder="Agreement">                                         
                                      @error('agreement')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>

                              <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <div class="form-group">
                                  {{-- <label>Credit</label> --}}
                                  <a href="?download=agreement" class="btn btn-info">Click to download Agreement</a>                                  
                                </div>
                              </div>
                              <!-- /.col -->
                          </div>

                          <div class="row vehicle_row odd">
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label>Contact Person</label>
                                      <input class="form-control @error('contact_person') is-invalid @enderror" type="text" name="contact_person" value="{{ old('contact_person') ?? $user->bookingAgent->contact_person  }}" id="contact_person" placeholder="Contact Person">
                                      @error('contact_person')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>

                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label>Payment Method</label>
                                      <select name="payment_method" id="" class="form-control">
                                          <option value="" selected>Select Payment Method</option>
                                          <option value="cash"  {{ ($user->bookingAgent->payment_method == 'cash') ? 'selected' : ''}}>Cash</option>
                                          <option value="bank" {{ ($user->bookingAgent->payment_method == 'bank') ? 'selected' : ''}}>Bank</option>
                                      </select>
                                      @error('payment_method')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>

                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label>Payment Terms</label>
                                          <select name="payment_terms" id="payment_terms" class="form-control">
                                            <option value="" selected>Select Payment Method</option>
                                            <option value="7/1"  {{ ($user->bookingAgent->payment_terms == '7/1') ? 'selected' : ''}}>Weekly&nbsp;7/1</option>
                                            <option value="15/1" {{ ($user->bookingAgent->payment_terms == '15/1') ? 'selected' : ''}}>Biweekly&nbsp;15/1</option>
                                            <option value="30/1" {{ ($user->bookingAgent->payment_terms == '30/1') ? 'selected' : ''}}>Monthly&nbsp;30/1</option>  
                                        </select>
                                      @error('payment_terms')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>
                              <!-- /.col -->
                          </div>
                         <!-- /.col -->
                          <div class="row vehicle_row even">
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label>Bank Name</label>
                                      <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') ?? $user->bank_details->bank_name  }}" id="bank_name" placeholder="Bank Name">
                                      @error('bank_name')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>
                              <!-- /.col -->
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label>Account Title</label>
                                      <input type="text" name="account_title" class="form-control @error('account_title') is-invalid @enderror" value="{{ old('account_title') ?? $user->bank_details->account_title  }}" id="account_title" placeholder="Account Title">
                                      @error('account_title')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>
                              <!-- /.col -->
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label>IBAN</label>
                                      <input type="text" name="iban" class="form-control @error('iban') is-invalid @enderror" value="{{ old('iban') ?? $user->bank_details->iban  }}" id="iban" placeholder="IBAN #">
                                      @error('iban')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>
                              <!-- /.col -->
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label>BIC/SWIFT</label>
                                      <input type="text" name="bic_swift" class="form-control @error('bic_swift') is-invalid @enderror" value="{{ old('bic_swift') ?? $user->bank_details->bic_swift  }}" id="bic_swift" placeholder="BIC/SWIFT">
                                      @error('bic_swift')
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
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Image:</label>
                                      <br>
                                      <div class="imgUploader" style="width:200px; height:200px;">
                                          <input class="form control imgUploaderinput" type='file' name="agent_image" id="d_img" />
                                          <img class="imgUploaderImg" id="d_img_preview" src="{{ asset('public/storage/assets/booking-agent') }}/{{ $user->bookingAgent->profile_image }}" alt="" />
                                      </div>
                                      @error('agent_image')
                                          <div class="invalid-feedback" style="display:block;" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </div>
                                      @enderror
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="d-flex justify-content-end" style="margin-top: 200px;">
                                      <input class="btn btn-lg btn-success" type="submit" value="Save" style="width:200px;">
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

            $("#d_img").change(function() {
                readURL(this, "#d_img_preview");
            });
        });
    </script>
@endsection
