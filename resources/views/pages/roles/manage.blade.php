@php

@endphp


@extends('layouts.app')

@section('title', 'Manage Roles')

@section('breadcrumb')

<button type="submit" class="btn btn-sm btn-danger float-right" id="btn" style="display:none">Delete</button>
@endsection

@section('content')

<div class="row">
    <div class="col-sm-6">
        <form action="{{ route("role.bulkdestroy") }}" method="POST" id="record-form">
            @csrf
        <table id="manage_role" style="width:100% background:#fff;" class="display table">
            <thead>
                <tr>
                    <th data-orderable="false" id="thcheck"><input type="checkbox" id="all-checkbox-seleted-otp"></th>
                    <th>#</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @php
                $sno =1;

                @endphp

                @foreach ($roles as $role)
                <tr>
                    <td> <input type="checkbox" class="multi-select-ids" name="seleted_id[]" value="{{ $role->id }}"></td>
                    <td>
                        {{ $sno++}}

                    </td>
                    <td>{{ $role->name}}
                        <span class="action_wapper2">
                            <!-- <button type="button" class="btn btn-link text-success" data-toggle="modal"
                                data-target="#roleEditModal">
                                Edit
                            </button> -->
                            @can('role-edit')
                            <a class="text-success mr-2" data-role_name="{{ $role->name}}" href=""
                                data-role_update_url="{{ route('role.update',$role->id) }}" data-toggle="modal"
                                data-target="#roleEditModal" title="edit" data-toggle="tooltip" data-placement="top"><i
                                class="fa fa-edit"></i></a>|
                            @endcan

                            @can('role-delete')
                            <a class="text-danger" onclick="(function(e){e.preventDefault();record_delete(e)})(event)"
                                href="{{route('role.delete',$role->id) }}" title="delete" data-toggle="tooltip" data-placement="top"><i
                                class="fa fa-trash ml-2"></i></a>
                            @endcan

                        </span>
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
                            <a class="btn btn-sm btn-success" href="{{url('role')}}/{{$role->id}}/edit">Edit</a>
                            <a class="btn btn-sm btn-danger" href="role/delete/{{$role->id}}">Delete</a>
                        </td>
                    </noscript>
                </tr>
                @endforeach

            </tbody>

            </tbody>
        </table>
        </form>
    </div>
    @can('role-create')
    <div class="col-sm-6">
        <form action="{{ route('role.store') }}" method="post">
            @csrf
            <label for="role_name">Role:</label>
            <input class="form-control @error('role_name') is-invalid @enderror" type="text" name="role_name" id="role_name">
            @error('role_name')
            <div class="invalid-feedback" style="display:block;" role="alert">
                <strong>{{ $message }}</strong>
            </div>
            @enderror
            <input type="submit" value="Add Role" class="btn btn-success mt-2">
        </form>
    </div>
    @endcan
</div>


<!-- Role Edit modal -->
<!-- Modal -->
<div class="modal fade" id="roleEditModal" tabindex="-1" role="dialog" aria-labelledby="roleEditModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleEditModalLabel">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    @csrf
                    @method("PUT")
                    <label>Role Name:</label>
                    <input type="text" name="role_name" id="edit_role_name" class="form-control">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Save changes">
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')

<script>
    var sno = 1;
    $(document).ready(function() {

        $('[data-toggle="tooltip"]').tooltip();
        $('#manage_staff').DataTable();

        $('#roleEditModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var role_update_url = button.data('role_update_url')
            var role_name = button.data('role_name')
            var modal = $(this)
            modal.find('form').attr('action', role_update_url)
            modal.find('input[name="role_name"]').val(role_name);



        })

        $(".form-control").on('focus',function(){

        $(this).removeClass('is-invalid')
        });

    });



</script>

@endsection