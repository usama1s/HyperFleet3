@extends('layouts.app')

@section('title', 'Supplier')

@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home </a></li>
        <li class="breadcrumb-item"><a href="{{ url('suppliers') }}">Suppliers </a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

@endsection

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('suppliers.update', $user->id) }}" enctype="multipart/form-data">
            @method('put')
            @csrf
            {{-- ROW ONE Vehicle General Information --}}
            <div class="row ">
                <div class="col-md-12">
                    <!-- SELECT2 EXAMPLE -->
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Supplier General Information</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row vehicle_row even">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input class="form-control" type="text" name="first_name"
                                            value="{{ $user->first_name }}" id="first_name">

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
                                        <input class="form-control" type="text" name="last_name"
                                            value="{{ $user->last_name }}" id="last_name">

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
                                        <input class="form-control" type="text" name="company_name"
                                            value="{{ $user->supplier->company_name }}" id="company_name">

                                        @error('company_name')
                                            <div class="invalid-feedback" style="display:block;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input class="form-control" type="text" name="contact_no"
                                            value="{{ $user->contact_no }}" id="contact_no">
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
                                        <input class="form-control" type="text" name="email"
                                            value="{{ $user->email }}" id="email">
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
                                        <input class="form-control" type="text" name="address"
                                            value="{{ $user->supplier->address }}" id="address">
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
                  <input  type="text" class="form-control" name="details"  value="{{ $user->details }}" id="details">
                  @error('details')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                     <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>
            </div> --}}

                            {{-- <div class="row vehicle_row even">

              <div class="col-md-6">
                <div class="form-group">
                  <label>Agreement</label>
                  <input  type="text" name="agreement" class="form-control"  value="{{ $user->agreement }}" id="agreement">
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
                  <input  type="text" name="credit_limit" class="form-control"  value="{{ $user->credit}}" id="credit_limit">
                  @error('credit_limit')
                  <div class="invalid-feedback" style="display:block;" role="alert">
                     <strong>{{ $message }}</strong>
                  </div>
                  @enderror
                </div>
              </div>

                <!-- /.col -->

            </div> --}}
                            <!-- /.col -->
                            <div class="row vehicle_row odd">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sales Person</label>
                                        <input class="form-control" type="text" name="sales_person" id="sales_person"
                                            value="{{ $user->supplier->sales_person }}">
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
                                        <select name="payment_method" id="" class="form-control"
                                            value="{{ $user->supplier->payment_method }}">
                                            <option value="">Select Payment Method</option>
                                            <option value="cash"
                                                {{ $user->supplier->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="bank"
                                                {{ $user->supplier->payment_method == 'bank' ? 'selected' : '' }}>Bank</option>
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
                                        <input type="number" class="form-control" name="commission" id="commission"
                                            value="{{ $user->supplier->commission }}">
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
                                        {{-- <input type="text" name="payment_terms" class="form-control"
                                            value="{{ $user->supplier->payment_terms }}" id="payment_terms"> --}}

                                            <select name="payment_terms" id="payment_terms" class="form-control">

                                              <option value="" selected>Select Payment Method</option>
                                              <option value="7/1"
                                                  {{ $user->supplier->payment_terms == '7/1' ? 'selected' : '' }}>Weekly&nbsp;7/1</option>
                                              <option value="15/1"
                                                  {{ $user->supplier->payment_terms == '15/1' ? 'selected' : '' }}>Biweekly&nbsp;15/1
                                              </option>
                                              <option value="30/1"
                                                  {{ $user->supplier->payment_terms == '30/1' ? 'selected' : '' }}>Monthly&nbsp;30/1
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
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" id="password" placeholder="Password" autocomplete="new-password" autocomplete="off">
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
                    <input  type="text" name="bank_details" class="form-control" value="{{ $user->bank_details }}" id="bank_details">
                    @error('bank_details')
                    <div class="invalid-feedback" style="display:block;" role="alert">
                       <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                  </div>
                </div> --}}
                            </div>
                            <!-- /.col -->
                            @php
                                $user_bank_name     = "";
                                $user_account_title = "";
                                $user_iban          = "";
                                $user_bic_swift     = "";
                                if($user->bank_details != null)
                                {
                                    $user_bank_name     = $user->bank_details->bank_name;
                                    $user_account_title = $user->bank_details->account_title;
                                    $user_iban          = $user->bank_details->iban;
                                    $user_bic_swift     = $user->bank_details->bic_swift;
                                }
                            @endphp
                            <div class="row vehicle_row odd">

                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label>Bank Name</label>
                                      <input type="text" name="bank_name"
                                          class="form-control @error('bank_name') is-invalid @enderror"
                                          value="{{ old('bank_name') ?? $user_bank_name}}" id="bank_name" placeholder="Bank Name">
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
                                      <input type="text" name="account_title"
                                          class="form-control @error('account_title') is-invalid @enderror"
                                          value="{{ old('account_title') ?? $user_account_title}}" id="account_title"
                                          placeholder="Account Title">
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
                                      <input type="text" name="iban"
                                          class="form-control @error('iban') is-invalid @enderror"
                                          value="{{ old('iban') ?? $user_iban }}" id="iban" placeholder="IBAN #">
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
                                      <input type="text" name="bic_swift"
                                          class="form-control @error('bic_swift') is-invalid @enderror"
                                          value="{{ old('bic_swift') ?? $user_bic_swift }}" id="bic_swift" placeholder="BIC/SWIFT">
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
                                            <input class="form control imgUploaderinput" type='file' name="image"
                                                id="d_img" />
                                            <img style="width:80%" class="imgUploaderImg" id="d_img_preview_edit" src="{{ asset('public/storage/assets/suppliers') }}/{{ $user->supplier->image }}" alt="" />
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

            $("#d_img").change(function() {
                readURL(this, "#d_img_preview_edit");
            });
        });
    </script>
@endsection
