@extends('layouts.app')

@section('title', 'Add New Supplier')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
        <li class="breadcrumb-item"><a href="{{ url('suppliers') }}">Suppliers </a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
@endsection

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('suppliers.store') }}" enctype="multipart/form-data">
            @csrf
            {{-- ROW ONE Vehicle General Information --}}
            <div class="row ">
                <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Supplier General Information</h3>
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
                                        <input class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name" value="{{ old('first_name') }}" id="first_name" placeholder="First Name">
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
                                        <input class="form-control @error('last_name') is-invalid @enderror" type="text" name="last_name" value="{{ old('last_name') }}" id="last_name" placeholder="Last Name">
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
                                        <input class="form-control @error('company_name') is-invalid @enderror" type="text" name="company_name" value="{{ old('company_name') }}" id="company_name" placeholder="Company Name">
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
                                        <input class="form-control @error('contact_no') is-invalid @enderror" type="text" name="contact_no" value="{{ old('contact_no') }}" id="contact_no" placeholder="Contact No.">
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
                                        <input class="form-control @error ('email') is-invalid @enderror" type="text" name="email" value="{{ old('email') }}" id="email" placeholder="Email" autocomplete="new-password" autocomplete="off">
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
                                        <label>Address</label>
                                        <input class="form-control @error('address') is-invalid @enderror" type="text" name="address" value="{{ old('address') }}" id="address" placeholder="Address">
                                        @error('address')
                                            <div class="invalid-feedback" style="display:block;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- /.row -->
                            {{-- <div class="row vehicle_row even">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Details</label>
                    <input  type="text" class="form-control @error('details') is-invalid @enderror" name="details"  value="{{ old('details') }}" id="details" placeholder="Details">
                    @error('details')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                      <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                  </div>
                </div>
              </div> --}}
                            <!-- /.row -->
                            {{-- <div class="row vehicle_row even">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Agreement</label>
                      <input  type="text" name="agreement" class="form-control @error('agreement') is-invalid @enderror" value="{{ old('agreement') }}" id="agreement" placeholder="Agreement">
                      @error('agreement')
                      <div class="invalid-feedback" style="display:block;" role="alert">
                        <strong>{{ $message }}</strong>
                      </div>
                      @enderror
                    </div>
                  </div>
    
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Credit</label>
                      <input  type="text" name="credit_limit" class="form-control @error('credit_limit') is-invalid @enderror" value="{{ old('credit_limit') }}" id="credit_limit" placeholder="Credit">
                      @error('credit_limit')
                      <div class="invalid-feedback" style="display:block;" role="alert">
                        <strong>{{ $message }}</strong>
                      </div>
                      @enderror
                    </div>
                  </div>    
                    <!-- /.col -->    
                </div> --}}

                            <div class="row vehicle_row odd">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sales Person</label>
                                        <input class="form-control @error('sales_person') is-invalid @enderror" type="text" name="sales_person" value="{{ old('sales_person') }}" id="sales_person" placeholder="Sales Person">
                                        @error('sales_person')
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
                                            <option value="cash">Cash</option>
                                            <option value="bank">Bank</option>
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
                                        <label>Commission</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control @error('commission') is-invalid @enderror" name="commission" id="commission" value="{{ old('commission') }}" placeholder="Commission">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        @error('commission')
                                            <div class="invalid-feedback" style="display:block;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- /.col -->

                            <div class="row vehicle_row even">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Terms for Services</label>
                                        {{-- <input  type="text" name="payment_terms" class="form-control @error('payment_terms') is-invalid @enderror" value="{{ old('payment_terms') }}" id="payment_terms" placeholder="Payment Terms for Services"> --}}
                                        <select name="payment_terms" id="payment_terms" class="form-control">
                                            <option value="" selected>Select Payment Frequency</option>
                                            <option value="7/1"
                                                {{ old('payment_terms') == '7/1' ? 'selected' : '' }}>Weekly&nbsp;7/1</option>
                                            <option value="15/1"
                                                {{ old('payment_terms') == '15/1' ? 'selected' : '' }}>Biweekly&nbsp;15/1
                                            </option>
                                            <option value="30/1"
                                                {{ old('payment_terms') == '30/1' ? 'selected' : '' }}>Monthly&nbsp;30/1
                                            </option>
                                        </select>
                                        @error('payment_terms')
                                            <div class="invalid-feedback" style="display:block;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" autocomplete="new-password"  autocomplete="off">
                                        @error('password')
                                            <div class="invalid-feedback" style="display:block;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col-md-6">
                <div class="form-group">
                  <label>Bank Details</label>
                  <input  type="text" name="bank_details" class="form-control @error('bank_details') is-invalid @enderror" value="{{ old('bank_details') }}" id="bank_details" placeholder="Bank Details">
                  @error('bank_details')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                    <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div> --}}

                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <div class="row vehicle_row odd">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}" id="bank_name" placeholder="Bank Name">
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
                                        <input type="text" name="account_title" class="form-control @error('account_title') is-invalid @enderror" value="{{ old('account_title') }}" id="account_title" placeholder="Account Title">
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
                                        <input type="text" name="iban" class="form-control @error('iban') is-invalid @enderror" value="{{ old('iban') }}" id="iban" placeholder="IBAN #">
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
                                        <input type="text" name="bic_swift" class="form-control @error('bic_swift') is-invalid @enderror" value="{{ old('bic_swift') }}" id="bic_swift" placeholder="BIC/SWIFT">
                                        @error('bic_swift')
                                            <div class="invalid-feedback" style="display:block;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>

                            <div class="row vehicle_row even">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Image:</label>
                                        <br>
                                        <div class="imgUploader" style="width:200px; height:200px;">
                                            <input class="form control imgUploaderinput" type='file' name="image" id="ba_img" />
                                            <img class="imgUploaderImg" id="ba_img_preview" src="{{ asset('public/images/default-user.jpg') }}" alt="" />
                                        </div>
                                        @error('image')
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
            $(".form-control").on('focus', function() {

                $(this).removeClass('is-invalid')
            });

            $("#ba_img").change(function() {
                readURL(this, "#ba_img_preview");
            });
        });
    </script>
@endsection
