@extends('layouts.app')

@section('title', 'View Supplier')

@section('breadcrumb')


<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home </a></li>
    <li class="breadcrumb-item"><a href="{{url('suppliers')}}">Suppliers </a></li>
    <li class="breadcrumb-item active">View</li> 
</ol>

  @endsection  

@section('content')

<div class="row">
   <div class="col-md-12">
      @can('supplier-edit')
      <a class="btn btn-info float-right mb-2" href="{{url('suppliers')}}/{{$user->id}}/edit" title="edit">Edit</a>
      @endcan
   </div>
</div>
<div class="row">
  <div class="col-md-3">
 
     <!-- Profile Image -->
     <div class="card card-primary card-outline">
        <div class="card-body box-profile ">
           <div class="text-center">
              <img class="profile-user-img" src="{{ asset('public/storage/assets/suppliers/'. $user->supplier->image ) }}" alt="User profile picture">
           </div>
           <h3 class="profile-username text-center">{{ $user->first_name }} {{ $user->last_name }}</h3>
           <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                 <b>Company Name</b> <a class="float-right">{{ $user->supplier->company_name }}</a>
              </li>
              <li class="list-group-item">
                 <b>Email</b> <a class="float-right">{{ $user->email }}</a>
              </li>
              <li class="list-group-item">
                 <b>Contact</b> <a class="float-right">{{ $user->contact_no }}</a>
              </li>
              <li class="list-group-item">
                 <b>Billing Address</b> <a class="float-right">{{ $user->supplier->address }}</a>
              </li>
              {{-- <li class="list-group-item">
                 <b>Agreement</b> <a class="float-right">{{ $user->supplier->agreement }}</a>
              </li> --}}
              {{-- <li class="list-group-item">
                 <b>Credit</b> <a class="float-right">{{ $user->supplier->credit }}</a>
              </li> --}}
              <li class="list-group-item">
                 <b>Sales Person</b> <a class="float-right">{{ $user->supplier->sales_person }}</a>
              </li>
              <li class="list-group-item">
                 <b>Payment Method</b> <a class="float-right">{{ $user->supplier->payment_method }}</a>
              </li>
              {{-- <li class="list-group-item">
                 <b>Details</b> <a class="float-right">{{ $user->supplier->details }}</a>
              </li> --}}
              {{-- <li class="list-group-item">
                 <b>Bank Details</b> <a class="float-right">{{ $user->bank_details }}</a>
              </li> --}}
              <li class="list-group-item">
               <b>Bank Details</b>
               <ul>
                   <li>Bank Name: <a class="float-right">{{ $user->bank_details->bank_name ?? ''}}</a></li>
                   <li>Account Title: <a class="float-right">{{ $user->bank_details->account_title ?? ''}}</a></li>
                   <li>IBAN # <a class="float-right">{{ $user->bank_details->iban ?? ''}}</a></li>
                   <li>BIC / Swift Code: <a class="float-right">{{ $user->bank_details->bic_swift ?? ''}}</a></li>
               </ul>
           </li>
              <li class="list-group-item">
                 <b>Payment Terms</b> <a class="float-right">{{ $user->supplier->payment_terms }}</a>
              </li>
              <li class="list-group-item">
                 <b>Commission</b> <a class="float-right">{{ $user->supplier->commission }}%</a>
              </li>
   
           </ul>
           @if (is_null($user->block_type))
               
            <button type="button" style="width: 100%;" class="btn btn-danger" data-toggle="modal" data-target="#blockuser"><i class="fa fa-ban" aria-hidden="true"></i> Block </button>
           @else

              <form action="{{route("supplier.unblock")}}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                <button type="submit" style="width: 100%;" class="btn btn-success"><i class="fa fa-ban" aria-hidden="true"></i> Unblock </button>

              </form>
           @endif
           @if (!is_null($user->block_until))
           <div><strong>{{ __('Block Till:')}} {{date("d M Y",strtotime($user->block_until))}} </strong></div>
          @endif

           <div class="modal fade" id="blockuser" tabindex="-1" role="dialog" aria-labelledby="blockuserLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                 <div class="modal-content">
                    <div class="modal-header">
                       <h5 class="modal-title" id="blockuserLabel">Block User</h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                       </button>
                    </div>
                    <div class="modal-body">
                       <form action="{{route("supplier.block")}}" method="POST">
                          @csrf
                          <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                          <label>Block Type</label>
                          <select class="form-control" name="block_type" id="block_type">
                             <option value="" checked>Select Type</option>
                             <option value="temp">Temporary</option>
                             <option value="permanent">Permanent</option>
                          </select>
                          @error('block_type')
                          <div class="invalid-feedback" style="display:block;" role="alert">
                             <strong>{{ $message }}</strong>
                          </div>
                          @enderror
                          <div style="display:none;" id="until">
                             <label>Block Untill</label>
                             <input class="form-control datetimepicker-input" type="text" name="block_till"
                                id="block_till" placeholder="Block Till" data-toggle="datetimepicker"
                                data-target="#block_till" autocomplete="off">
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger"><i class="fa fa-ban" aria-hidden="true"></i> Block</button>
                         </div>
                       </form>
                    </div>
                   
                 </div>
              </div>
           </div>
        </div>
        <!-- /.card-body -->
     </div>
     <!-- /.card -->
     <!-- /.col -->
  </div>
  <div class="col-md-9">
     <div class="card card-primary card-outline card-outline-tabs" style="border-color:transparent">
        <div class="card-header p-0 border-bottom-0">
           <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
              <li class="nav-item">
                 <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="false">Drivers</a>
              </li>

              <li class="nav-item">
                 <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="true">Vehicles</a>
              </li>

              <li class="nav-item">
               <a class="nav-link" id="custom-tabs-three-pricing-tab" data-toggle="pill" href="#custom-tabs-three-pricing" role="tab" aria-controls="custom-tabs-three-pricing" aria-selected="true">Pricing Schemes</a>
            </li>
            
              <li class="nav-item">
               <a class="nav-link" id="custom-tabs-three-bookings-tab" data-toggle="pill" href="#custom-tabs-three-bookings" role="tab" aria-controls="custom-tabs-three-bookings" aria-selected="true">Bookings</a>
            </li>
           </ul>
        </div>
        <div class="card-body">
           <div class="tab-content" id="custom-tabs-three-tabContent">
              <div style="overflow-x: auto;" class="tab-pane fade active show" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                 @include('pages.suppliers.drivers-list')
              </div>
              <div style="overflow-x: auto;" class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                 @include('pages.suppliers.vehicles-list')
              </div>

              <div style="overflow-x: auto;" class="tab-pane fade" id="custom-tabs-three-pricing" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
               @include('pages.suppliers.pricing-schemes')
            </div>

              <div style="overflow-x: scroll;" class="tab-pane fade" id="custom-tabs-three-bookings" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
@include('pages.suppliers.bookings-list')
            </div>
            
           </div>
        </div>
        <!-- /.card -->
     </div>
  </div>
</div>
{{-- /.row --}}

@endsection

@section('js')
<script>
    $(document).ready(function(){

      $('#manage_drivers').DataTable({ });
      $('#supplier_vehicles').DataTable({});
      $("#block_type").on("change",function(){
 
        if($(this).val()=="temp"){
       
          $("#until").show();
        }
        else{
          $('#block_till').val('');
          $("#until").hide();
        }

      });

      $('#block_till').datetimepicker({
        format: 'YYYY-MM-DD',
        
        
      });
  

    });


</script>


 @endsection