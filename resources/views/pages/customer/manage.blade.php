@extends('layouts.app')

@section('title', 'Manage Customers')

@section('breadcrumb')

<div class="page_sub_menu">
    @can('booking-create')
    <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    @endcan
</div>
@endsection

@section('content')

<form action="{{ route("customers.destroy") }}" method="POST" id="record-form">
    @csrf
<table id="manage_customers" style="width:100%" class="table responsive table-hover">
    <thead>
        <tr>
            <th id="thcheck" data-orderable="false" data-priority="1"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
            {{-- <th>Action</th> --}}
        </tr>
    </thead>
    <tbody>
        @php
        $sno =1;
        @endphp

        @foreach ($customers as $customer)
        <tr>
            
            <td class="multi-select-ids-td">
                <input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{$customer->id}}">
            
            </td>
            
            <td>
                {{ $sno++}}

            </td>
            <td>{{$customer->first_name}} {{$customer->last_name}}</td>
            <td>{{$customer->email}} <br>
                <a class="text-success action_wapper2" href="{{route('customer.booking',$customer->id)}}"> <i
                    class="fa fa-eye"></i> View Bookings</a>
            </td>
            
            <td>{{$customer->contact_no}}</td>
        {{-- <td> 
            
            @if (is_null($customer->block_type))
            <a type="button" id="{{$customer->id}}" class="btn btn-danger text-white blockCustomer" data-toggle="modal" data-target="#blockuser"><i class="fa fa-ban" aria-hidden="true"></i> Block </a>
            @else

              <a href="{{route('customer.unblock',$customer->id)}}" class="btn btn-success unblockCustomer"><i class="fa fa-ban" aria-hidden="true"></i> Unblock </a>
           
        </td>
         @endif --}}
        </tr>
       
        @endforeach
    </tbody>
</table>

</form>

{{-- <div class="modal fade" id="blockuser" tabindex="-1" role="dialog" aria-labelledby="blockuserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title" id="blockuserLabel">Block User</h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
             </button>
          </div>
          <div class="modal-body">
            <form action="{{route("customer.block")}}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="">
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
 </div> --}}

@endsection

@section('js')
    <script>
        $(document).ready(function() {

        $('#manage_customers').DataTable({ }); 
        // $("#block_type").on("change",function(){
 
        //     if($(this).val()=="temp"){

        //         $("#until").show();
        //     }
        //     else{
        //         $('#block_till').val('');
        //         $("#until").hide();
        //     }

        //     });

        //     $('#block_till').datetimepicker({
        //     format: 'YYYY-MM-DD',
            
        //     });

        //  $(".blockCustomer").on("click",function(){
        //    var id = $(this).attr('id');
        //    $("input[name='user_id']").val(id);
            
        // })

     
    });
        
    </script>
@endsection