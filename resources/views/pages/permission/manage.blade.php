@php
use App\Models\User;
use App\Models\Staff;

$users= [];
 if(Auth()->user()->role == 1){
     //admin login
     $users =  DB::table('users')->whereIn('role',[1,2,3])->get();

 }else if(Auth()->user()->role == 3){
     //supplier login
           $supplier = Auth()->user();
           $users = DB::table('users')
          ->join('staff', 'users.id', '=', 'staff.user_id')
          ->where("supplier_id",$supplier->id)
          ->get();

          //dd($users);
    // $users = User::whereIn('role',[2])->get();
 }
 else if(Auth()->user()->role == 2){
     //staff login

           $login_staff = Auth()->user();
           $staff = Staff::where("user_id",$login_staff->id)->first();

           $users = DB::table('users')
          ->join('staff', 'users.id', '=', 'staff.user_id')
          ->where("supplier_id",$staff->supplier_id)
          ->where("users.id",'!=',$login_staff->id)
          ->get();
          //$users = User::where("id","!=",Auth()->user()->id)->whereIn('role',[2])->get();
 }
@endphp

@extends('layouts.app')

@section('title', 'Manage Permissions')

@section('breadcrumb')
{{-- <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home </a></li>
    <li class="breadcrumb-item"><a href="{{url('vehicles')}}">Vehicles </a></li>
    <li class="breadcrumb-item active">Manage</li>
</ol>

</ol> --}}

@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Select Staff</label>
            
            <select class="form-control user_permission_id" name="user_permission_id" id="user_permission_id"
                class="height:50px">
                <option value="">Select Staff</option>

               
                @foreach ( $users as $user)

                @php
                    if(Auth()->user()->role != 1){
                        $user->id = $user->user_id;
                    }
                     
                @endphp
               
                <option class="l1" value="{{ $user->id }}">{{ $user->email }}</option>


                @endforeach


            </select>
            <div class="invalid-feedback" style="display:block;" role="alert" id="premission_error">

            </div>
            <p id="user_select_email"></p>


        </div>
        <h1 class="text-center all-permission-loading">No Staff Selected for permissions</h1>
        <div class="row permission-container" style="display:none;">
            <!-- INCLUDE PERMISSION MODULES -->
            @include('pages.permission.modules.driver')
            @include('pages.permission.modules.vehicle')
            @include('pages.permission.modules.booking')
            @include('pages.permission.modules.accounts')
            @include('pages.permission.modules.role')
            @include('pages.permission.modules.shift')
            @include('pages.permission.modules.staff')
            @include('pages.permission.modules.supplier')
            @include('pages.permission.modules.permission')


        </div>

    </div>
</div>








@endsection

@section('js')

<script>
    $(document).ready(function() {

       
        checkPermission();

        $("#user_permission_id").select2();
        $('#user_permission_id').on('select2:select', function(e) {
            var data = e.params.data;

            if (data.text != "Select User") {
                $('.custom-control-input').prop("checked", false);
                $('#user_select_email').html();
                $('#user_select_email').html("<input type='hidden' value='" + data.id +
                    "' id='user_id'>Privileges of User: <span id='user_email'>" + data.text +
                    '</span>');
                $("#premission_error").text("");
                checkPermission();
            }

        });




    });



    function checkPermission() {
        $(".all-permission-loading").text("Loading...");
        $(".all-permission-loading").show();
        $(".permission-container").hide();
        user_id = $("#user_id").val();
        if (user_id == "") {
            $("#premission_error").text("Please Select User");
        }
        $.ajax({
            url: "{{ url('/api/get_permission')}}",
            method: "POST",
            data: {
                user_id: user_id
            },
            success: function(result) {
                $(".all-permission-loading").hide();
                $(".permission-container").slideDown();
                for (i in result) {     

                    var id = "#" + result[i].name;
                    $(id).prop('checked', true);

                }

            },
            error:function(){
                $(".all-permission-loading").text("No Staff Selected for Permissions");
            }
        });
    }

    function updatePermission(elem) {
        status = $(elem).prop('checked');
        type = $(elem).attr('p-type');
        slug = $(elem).attr('slug');
        user_email = $("#user_email").text();

        $(elem).parent().append("<div class='loading'></div>");

        if (user_email == "") {
            $("#premission_error").text("Please Select User");
            $(document).Toasts('create', {
                title: "Error",
                body: "Please Select User for Permissions",
                autohide: true,
                delay: 3000,
                class: "bg-danger",
                icon: 'fas fa-exclamation-circle fa-lg'
            });
            $(elem).prop('checked', false);
            return false;
        }

        if (status == "true") {
            status = 1;
        }

        if (status == "false") {
            status = 0;
        }

        // console.log(status + user_email +" - "+id);

        $.ajax({
            url: "{{ url('/api/change_permission') }}",
            method: "POST",
            data: {
                user_email: user_email,
                slug: slug,
                type: type,
                permission: status
            },
            success: function(result) {

                if(result.id){      
                    console.log("working");
                    $(".loading").remove();
                }
            },
            error: function(error) {
                $(document).Toasts('create', {
                    title: "Error",
                    body: "sorry, something went wrong",
                    autohide: true,
                    delay: 3000,
                    class: "bg-danger",
                    icon: 'fas fa-exclamation-circle fa-lg'
                });
                $(elem).prop('checked', false);
                $(".loading").remove();
            }

        });
    } 
</script>

@endsection