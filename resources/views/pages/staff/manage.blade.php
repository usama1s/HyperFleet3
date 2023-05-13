@php
use App\Models\User;
@endphp


@extends('layouts.app')

@section('title', 'Manage Staff')

@section('breadcrumb')

<div class="page_sub_menu">
    @can('staff-create')
    <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    <a class="btn btn-sm btn-success" href="{{ route('staff.create')}}">Add New</a>
    @endcan
</div>

@endsection

@section('content')
<form action="{{ route("suppliers.bulkdestroy") }}" method="POST" id="record-form">
    @csrf
<table id="manage_staff" style="width:100% background:#fff;" class="display table-hover table responsive">
    <thead>
        <tr>
            <th id="thcheck" data-orderable="false"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
            <th width='30px'>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $sno =1;
        @endphp

        @foreach ($staffs as $user)
        <tr>
            <td><input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $user->user_id }}"></td>
            <td>
                {{ $sno++}}

            </td>
            <td><img class="manage-thumbnail" src="{{ asset('public/assets/staff') }}/{{$user->image}}" width="80"></td>
            <td>{{ $user->first_name. " ". $user->last_name }}</td>
            <td>{{ $user->email}}
                <span class="action_wapper2">
                    <!-- <a class="text-info" href="{{ url('staff')}}/{{$user->id}}">View</a> -->
                    @can('staff-edit')
                    <a class="text-success mr-2" href="{{route('staff.edit',$user->user_id)}}"  title="edit" data-toggle="tooltip" data-placement="top"><i
                        class="fa fa-edit"></i></a>
                    @endcan

                    @can('staff-delete')
                    <a class="text-danger" onclick="(function(e){e.preventDefault();record_delete(e)})(event)"
                        href="{{route('staff.delete',$user->user_id) }}" title="delete" data-toggle="tooltip" data-placement="top"><i
                        class="fa fa-trash"></i></a>
                    @endcan
                </span>
            </td>
            <td>{{$user->contact_no}}</td>
            <td>
                {{ User::find($user->user_id)->getRoleNames()[0] }}
            </td>
            <td> 
                @if (is_null($user->block_type))
                <a type="button" id="{{$user->user_id}}" class="btn btn-danger text-white blockStaff" data-toggle="modal" data-target="#blockuser"><i class="fa fa-ban" aria-hidden="true"></i> Block </a>
                @else
    
                  <a href="{{route('staff.unblock',$user->user_id)}}" class="btn btn-success"><i class="fa fa-ban" aria-hidden="true"></i> Unblock </a>
                @endif
            </td>
            <noscript>
                <style>
                    .action_wapper2 {
                        display: none;
                    }

                    .action_wapper {
                        display: none;
                    }
                </style>
                <td>
                    <a class="btn btn-sm btn-info" href="{{ url('staff')}}/{{$user->user_id}}">View</a>
                    <a class="btn btn-sm btn-success" href="{{url('staff')}}/{{$user->user_id}}/edit">Edit</a>
                    <a class="btn btn-sm btn-danger" href="{{url('staff')}}/delete/{{$user->user_id}}">Delete</a>
                </td>
            </noscript>
        </tr>
        @endforeach

    </tbody>

    @if($staffs->hasPages())
    <tr>
        
            <td colspan="10">
                {{ $staffs->links() }}
            </td>
     
    </tr>
    @endif
</table>
</form>
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
             <form action="{{route("staff.block")}}" method="POST">
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
 </div>

@endsection

@section('js')

<script>
    var sno = 1;
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip(); 
        $('#manage_staff').DataTable({
            paging:false
        });

        if('{{ count($staffs) }}' < 1){
            $("#thcheck").hide();
           
        }

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

                $(".blockStaff").on("click",function(){
                var id = $(this).attr('id');
                $("input[name='user_id']").val(id);
                    
                })


    });
</script>

@endsection