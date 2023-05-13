@extends('layouts.app')

@section('title', 'View Driver')

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home </a></li>
<li class="breadcrumb-item"><a href="{{url('drivers')}}">Drivers </a></li>
    <li class="breadcrumb-item active">View</li>
    
  </ol>

  @endsection  

@section('content')

<div class="row">
  <div class="col-md-12">
    @can('driver-edit')
    <a class="btn btn-info float-right" href="{{url('drivers')}}/{{$user->user_id}}/edit" title="edit">Edit</a>
    @endcan
        <h3>
          <strong>Status: </strong>
       @if($user->status == null)
            <span class='text-danger'>No vehicle Assigned</span>
        
        @elseif($user->status == "available")
          <span class='text-warning'>Available For Booking</span>
        
        @elseif($user->status == "booked")
          <span class='text-success'>Booked</span>
        
        @elseif($user->status == "maintenance")
          <span class='text-info'>Driver is on leave</span>
        @else
          Unknown Status
        @endif
         
        </h3>
  </div>
</div>
<div class="row">
  <div class="col-md-3">
    <!-- Profile Image -->
    <div class="card card-primary card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <img  style="width:200px; height:auto;"class="profile-user-img img-fluid img-circle"
               src="{{ asset('public/assets/drivers/'. $user->driver_image ) }}"
               alt="User profile picture">
        </div>

      <h3 class="profile-username text-center">{{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}</h3>


        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Email</b> <a class="float-right">{{ $user->email }}</a>
          </li>
          <li class="list-group-item">
            <b>Contact</b> <a class="float-right">{{ $user->contact_no }}</a>
          </li>

          <li class="list-group-item">
            <b>Address</b> <a class="float-right">{{ ucfirst($user->address) }}</a>
          </li>

          <li class="list-group-item">
            <b>Payment Type</b> <a class="float-right">{{ ucfirst($user->payment_type) }}</a>
          </li>

          @if ($user->payment_type =="fixed")
            <li class="list-group-item">
              <b>Fixed</b> <a class="float-right">{{ config('currency-symbol')}}{{ $user->amount }}</a>
            </li>
          @else
            <li class="list-group-item">
              <b>Commission</b> <a class="float-right">{{ $user->amount }}%</a>
            </li>

          @endif          
        </ul>
        @if (is_null($user->block_type))
               
        <button type="button" class="btn btn-danger" style="width: 100%;" data-toggle="modal" data-target="#blockuser"><i class="fa fa-ban" aria-hidden="true"></i> Block </button>
       @else

          <form action="{{route("driver.unblock")}}" method="POST">
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
                 <form action="{{route("driver.block")}}" method="POST">
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
                          @error('block_till')
                          <div class="invalid-feedback" style="display:block;" role="alert">
                             <strong>{{ $message }}</strong>
                          </div>
                          @enderror
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

   
  </div>
  <!-- /.col -->
   <div class="col-md-9">
      
      <div class="card card-primary card-outline card-outline-tabs" style="border-color:transparent">
        <div class="card-header p-0 border-bottom-0">
          <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Driver Documents</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Vechicle Profile</a>
            </li>
            {{-- <li class="nav-item">
              <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Driver Activity</a>
            </li> --}}
            
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-three-tabContent">
            <div class="tab-pane fade active show" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                @include('pages.drivers.driver-view-sub-pages.driver-documents') 
            </div>
            <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
              @include('pages.drivers.driver-view-sub-pages.driver-vehicle-detail') 
            </div>
            {{-- <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
               Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna. 
            </div> --}}
           
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