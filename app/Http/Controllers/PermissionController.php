<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        // //create permission
        $this->middleware('permission:permission-create|permission-edit|permission-delete')->only('index');
    }

    public function index()
    {
        return view('pages.permission.manage');
    }

    public function getAllPermissions(Request $request)
    {
        $user_id = $request->user_id;
        return $user  = User::find($user_id)->getAllPermissions();
    }

    public function changePermission(Request $request)
    {
        $request->all();
        $user_email = $request->user_email;
        $slug = $request->slug;
        $type = $request->type;
        $permission = $slug."-".$type;
        $status = $request->permission;

        $user = User::where("email", $user_email)->first();
        if ($status == 1) {
            return $user->givePermissionTo($permission);
        } else {
            return $user->revokePermissionTo($permission);
        }
    }
}
