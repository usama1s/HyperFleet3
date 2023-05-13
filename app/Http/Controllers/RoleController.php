<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        //create permission
        $this->middleware('permission:role-create')->only(['create','store']);
        
        //view permission
        $this->middleware('permission:role-view')->only('show');

        //update permission
        $this->middleware('permission:role-edit')->
        only(['edit','update']);
        

        //delete permission
        $this->middleware('permission:role-delete')->only('destroy');
    }

    public function index()
    {
        $roles = Role::orderBy("id", "desc")->get();
        return view('pages.roles.manage', compact('roles'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|unique:roles,name',
        ]);

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        Role::create(['name' => $request->role_name]);
        return back()
        ->with('success', 'Role Saved');
    }


    public function show(Role $role)
    {
        //
    }

    
    public function edit(Role $role)
    {
        //
    }

    
    public function update(Request $request, Role $role)
    {
        if ($role->name == $request->role_name) {
            return back()
            ->with('success', 'Role Name Updated');
        } else {
            $validator = Validator::make($request->all(), [
            'role_name' => 'required|unique:roles,name',
            ]);
        }

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        $role->name = $request->role_name;
        $role->save();
        return back()
        ->with('success', 'Role Name Updated');
    }

    public function destroy(Role $role)
    {
        if (!is_null($role)) {
            $role->delete();
            return back()
                ->with('success', 'Role Deleted');
        } else {
            abort(403, 'Role Not Found.');
        }
    }

    public function bulkDestroy(Request $request){


        $count = 0;
        foreach($request->seleted_id as $id){
            $role = Role::find($id);
            $role->delete();
            $count++;
        }
        return back()->with("success",$count. Str::plural(' record',$count).' deleted');
    }
}
