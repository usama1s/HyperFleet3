@php
use App\Models\Supplier;
$supplier = new Supplier;
@endphp

@extends('layouts.app')

@section('title', 'Manage Suppliers')

@section('breadcrumb')

<div class="page_sub_menu">
    @can('supplier-delete')
        <button type="submit" class="btn btn-sm btn-danger" id="btn" style="display:none">Delete</button>
    @endcan
    @can('supplier-create')
        <a class="btn btn-sm btn-success" href="{{ route('suppliers.create')}}">Add New</a>
    @endcan
</div>

@endsection

@section('content')

<div id="supplier_search">
    <form action="{{ route('suppliers.search') }}" method="get">
        @csrf
    
        <div class="row">
           
            <div class="col-md-3">
                <input class="form-control" type="text" name="by_supplier_name" value="{{old('by_supplier_name')}}" placeholder="Supplier Name">
            </div>


            <div class="col-md-3">
                <input class="form-control" type="text" name="by_supplier_email"  value="{{old('by_supplier_email')}}" placeholder="Supplier Email">
            </div>

            <div class="col-md-3">
                <input class="form-control" type="text" name="by_supplier_no"  value="{{old('by_supplier_no')}}" placeholder="Supplier Phone">
            </div>
    
            <div class="col-md-3">
                <input type="submit" value="Search" class="btn btn-default">
                <input type="reset" value="Reset" class=" btn btn-danger">
            </div>
        </div>
       
    </form>
    </div>
    <br>
<form action="{{ route("suppliers.bulkdestroy") }}" method="POST" id="record-form">
    @csrf
<table id="manage_suppliers" style="width:100%; background:#fff;" class="display table-hover responsive table">
    <thead>
        <tr>
            <th id="thcheck" data-orderable="false"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
            <th>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
            <th>Address</th>
            <!-- <th>Status</th> -->
        </tr>
    </thead>
    <tbody>
        @php
        $sno =1;
        @endphp

        @foreach ($users as $user)
        <tr>
            <td><label><input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $user->user_id }}"></label></td>
            <td>
                {{ $sno++}}

            </td>
            <td><img class="manage-thumbnail" src="{{ asset('public/storage/assets/suppliers') }}/{{$user->image}}" width="80"></td>
            <td>{{ $supplier->fullName($user->user_id) }}
                @if (!is_null($user->block_type))
                    <div class="text-danger"><strong>Blocked</strong></div>
                @endif
            </td>
            <td>{{ $user->email}}
                <span class="action_wapper2">
                    @can('supplier-view')
                    <a class="text-info mr-2" href="{{ url('suppliers')}}/{{$user->user_id}}" title="view" data-toggle="tooltip" data-placement="top"><i
                        class="fa fa-eye"></i></a>
                    @endcan
                    @can('supplier-edit')
                    <a class="text-success mr-2" href="{{url('suppliers')}}/{{$user->user_id}}/edit" data-toggle="tooltip" data-placement="top" title="edit"><i
                        class="fa fa-edit"></i></a>
                    @endcan
                    @can('supplier-delete')
                    <a class="text-danger mr-2" title="delete" onclick="(function(e){e.preventDefault();record_delete(e)})(event)" data-toggle="tooltip" data-placement="top" href="{{url('suppliers')}}/delete/{{$user->user_id}}"><i
                        class="fa fa-trash"></i></a>
                    @endcan
                </span>
            </td>
            <td>{{ $user->contact_no}}</td>
            <td>{{ $user->address}}</td>


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
                    <a class="btn btn-sm btn-info" href="{{ url('suppliers')}}/{{$user->user_id}}">View</a>
                    <a class="btn btn-sm btn-success" href="{{url('suppliers')}}/{{$user->user_id}}/edit">Edit</a>
                    <a class="btn btn-sm btn-danger" href="{{url('suppliers')}}/delete/{{$user->user_id}}">Delete</a>
                </td>
            </noscript>
        </tr>
        @endforeach

    </tbody>



    @if($users->hasPages())
        <tr>
            
                <td colspan="10">
                    {{ $users->links() }}
                </td>
         
        </tr>
        @endif
</table>
</form>
@endsection

@section('js')

<script>
    var sno = 1;
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip(); 
        $('#manage_suppliers').DataTable({
            paging:false,
            "searching": false
        });

        if('{{ count($users) }}' < 1){
            $("#thcheck").hide();
           
        }

    });
</script>

@endsection