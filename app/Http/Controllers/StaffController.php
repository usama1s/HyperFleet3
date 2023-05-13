<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function __construct()
    {
        //create permission
        $this->middleware('permission:staff-create')->only(['create','store']);
        
        //view permission
        $this->middleware('permission:staff-view')->only('show');

        //update permission
        $this->middleware('permission:staff-edit')->
        only(['edit','update']);
        

        //delete permission
        $this->middleware('permission:staff-delete')->only('destroy');
    }

    public function index()
    {
        //$staffs = User::where('role', 2)->orderBy('id', 'desc')->paginate(10);

        $login_user = Auth()->user();

        if($login_user->role == 3){

            $staffs = DB::table('users')
            ->join('staff', 'users.id', '=', 'staff.user_id')
            ->where("supplier_id",$login_user->id)->orderBy('users.id', 'DESC')
            ->paginate(10);

        }else if($login_user->role == 2){
            $staff = Staff::where("user_id",$login_user->id)->first();
           $staffs = DB::table('users')
          ->join('staff', 'users.id', '=', 'staff.user_id')
          ->where("supplier_id",$staff->supplier_id)->orderBy('users.id', 'DESC')
          ->paginate(10);
        }else{
            $staffs = DB::table('users')
            ->join('staff', 'users.id', '=', 'staff.user_id')->orderBy('users.id', 'DESC')
            ->paginate(10);
        }
     
        return view("pages.staff.manage", compact('staffs'));
    }

    
    public function create()
    {
        return view("pages.staff.create");
    }

    
    public function store(Request $request)
    {

        $loginUser = Auth()->user();
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_no' => 'required',
            'email' => 'required|email|unique:users,email',
            'user_role' => 'required',
            'password' => 'required|min:6|confirmed',
            'image' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
           
        ]);

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        $image = $request->file('image');

        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('assets/staff');

        $img = Image::make($image->getRealPath());

        $img->resize(600, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);

        $user = new User;
        $staff = new Staff;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->contact_no = $request->contact_no;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 2; // 2 for staff
        $user->active = 0; //  not logged in
        
        $user->save();
        $user->assignRole($request->user_role);

        $staff->user_id = $user->id;
        $staff->image = $input['imagename'];

        if($loginUser->role == 3){
            //for supplier
            $staff->supplier_id = $loginUser->id;
        }      

        if($loginUser->role == 2){
            //for staff
            $loginStaff = Staff::where("user_id",$loginUser)->first();
            $staff->supplier_id = $loginStaff->supplier_id;
        }
        $staff->save();

        return redirect(route('staff.index'))->with("success", "User Created");
    }

  
    public function show($staff)
    {
        return $staff;
    }

   
    public function edit($staff)
    {
        $users = DB::table('users')
       ->join('staff', 'users.id', '=', 'staff.user_id')->where('staff.user_id',$staff)->get();
      
        $staff=$users[0];
       
        return view("pages.staff.edit", compact('staff'));
    }

    
    public function update(Request $request, User $staff)
    {
        // return $staff;
        $staffuser = Staff::where('user_id',$staff->id)->first();
        $validationPrams = [
            'contact_no' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'user_role' => 'required'           
        ];

        if (!empty($request->password)) {
            $validationPrams['password'] = 'required|min:6|confirmed';
        }

        if ($request->email == $staff->email) {
            $validationPrams['email'] = 'required';
        } else {
            $validationPrams['email'] = 'required|email|unique:users,email';
        }

        $validator = Validator::make($request->all(), $validationPrams);
    
        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        $staff->first_name = $request->first_name;
        $staff->last_name = $request->last_name;
        $staff->email = $request->email;
        $staff->contact_no = $staff->contact_no;
    
        if (!empty($request->password)) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        $image = $request->file('image');
        
        if (!empty($image)) {
            $image = $request->file('image');
            $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/staff/');
            $img = Image::make($image->getRealPath());
            $img->resize(640, 480, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.$input['imagename']);
            
            $img_path = public_path('assets/staff/').$staffuser->image;
            File::delete($img_path);
            
            $staffuser->image = $input['imagename'];
            $staffuser->save();            
//             $image->move($destinationPath, $input['imagename']);
            
        }
        
//         if (!empty($image)) {
//             // image upload
//             $input['imagename'] = $staffuser->image;
            
//             $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
//             $destinationPath = public_path('assets/staff/');
//             File::delete($destinationPath);

//            $img = Image::make($image->getRealPath());
//             $img->resize(600, 400, function ($constraint) {
//                 $constraint->aspectRatio();
//             })->save($destinationPath.$input['imagename']);
          
//             $staffuser->image = $input['imagename'];

//             if ($staffuser->save()) {
//                 $image->move($destinationPath, $input['imagename']);
//             }
//         }
        
        else {
            // no image upload
            $staffuser->save();
        }

        $pre_roles = $roles = $staff->getRoleNames();
        foreach ($pre_roles as $p_role) {
            $staff->removeRole($p_role);
        }

        $staff->assignRole($request->user_role);
        return redirect(route('staff.index'))->with("success", "User Upated");
    }

    
    public function destroy($id)
    {
        
        $staff = Staff::where("user_id", $id);
        $user = User::find($id);
        $img_path = public_path('assets/staff/').$staff->image;
        File::delete($img_path);
        
        if (!is_null($staff)) {
           
            $user->delete();
            $staff->delete();
            return back()
                ->with('success', 'Staff user deleted');
        } else {
            abort(403, 'Role Not Found.');
        }
    }
    public function bulkDestroy(Request $request)
    {
        $count = 0;
            foreach($request->seleted_id as $id){
                $user = User::find($id);
                $staff = Staff::where('user_id',$id);

                $img_path = public_path('assets/staff/').$staff->image;
                File::delete($img_path);
                
                $user->delete();
                $staff->delete();
                $count++;
            }
            return back()->with("success",$count. Str::plural(' record',$count).' deleted');
    }
    public function blockStaff(Request $request){

        $validations = [
            'block_type' => 'required'
        ];
        if($request->block_type=="temp"){
            $validations['block_till'] = 'required';
        }
        $this->validate($request, $validations);

        $user = User::find($request->user_id);
        $user->block_type = $request->block_type;
        $user->block_until = $request->block_till;

        $user->save();

        return back()->with("warning","Staff Blocked");
    }

    public function unblockStaff($id){

        $user = User::find($id);
        $user->block_type = Null;
        $user->block_until = Null;

        $user->save();
        return back()->with("success","Staff Unblocked");
    }
}
