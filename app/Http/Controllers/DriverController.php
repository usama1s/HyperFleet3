<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;


use App\Models\Vehicle;
use App\Models\Shift;
use App\Models\Driver;
use App\Models\User;
use App\Models\Staff;
use App\Models\Booking;

use App\Notifications\CustomNotification;

class DriverController extends Controller
{
    public function __construct()
    {
        //create permission
        $this->middleware('permission:driver-create')->only(['create','store']);

        //view permission
        $this->middleware('permission:driver-view')->only('show');

        //update permission
        $this->middleware('permission:driver-edit')->
        only(['edit','update','assignShift','removeAssignShift','assignShiftSave','assignVehicle','removeAssignVehicle','assignVehicleSave']);


        //delete permission
        $this->middleware('permission:driver-delete')->only('destroy');
    }

    public function index()
    {


        $login_user = Auth()->user();

        if($login_user->role == 3){

            $users = DB::table('users')
            ->join('drivers', 'users.id', '=', 'drivers.user_id')
            ->where("supplier_id",$login_user->id)->orderBy('users.id', 'DESC')
            ->paginate(10);

        }else if($login_user->role == 2){
            $staff = Staff::where("user_id",$login_user->id)->first();
           $users = DB::table('users')
          ->join('drivers', 'users.id', '=', 'drivers.user_id')
          ->where("supplier_id",$staff->supplier_id)->orderBy('users.id', 'DESC')
          ->paginate(10);
        }else{
             $users = DB::table('users')
            ->join('drivers', 'users.id', '=', 'drivers.user_id')->orderBy('users.id', 'DESC')
            ->paginate(10);
        }

        return view('pages/drivers/manage', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.drivers.create');
    }

    public function showAllJSON()
    {
        $users = DB::table('users')
        ->join('drivers', 'users.id', '=', 'drivers.user_id')
        ->get();

        return array("data" => $users);
    }

    // public function showDriverJSON(User $driver){
    //     $data = [];

    //     $data["user"] = $driver;
    //     $data["driver"] = $driver->driver;
    //     $data["vehicle"] = Vehicle::find($driver->driver->vehicle_id);

    //     return $data;
    // }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $driver = new Driver;
        $user = new User;

        $validation_rules = [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email|unique:users',
            'contact_no'    => 'required',
            'address'       => 'required',
            'license_expiry'=> 'required',
//             'rta_card_expiry' => 'required',
//             'emirates_expiry' => 'required',
            'driver_image'  => 'required|mimes:jpeg,png,jpg|max:2048',
            'license_image' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
//             'rta_card_image' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
//             'emirates_id' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
            'payment_type'  => 'required',
            'password'      => 'required',

        ];

        if($request->payment_type == "commission"){
            $validation_rules['commission'] = "required";

            $driver->payment_type = $request->payment_type;
            $driver->amount = $request->commission;

        }else if($request->payment_type == "fixed"){
            $validation_rules['fixed'] = "required";

            $driver->payment_type = $request->payment_type;
            $driver->amount = $request->fixed;

        }else{
            return back()->with("Please Select Valid Payment Type");
        }

        $validator = Validator::make($request->all(), $validation_rules);

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }


        $image = $request->file('driver_image');

        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();

        $destinationPath = public_path('assets/drivers');
        $license_path = public_path('assets/drivers/license_image');
//         $rta_card_image_path = public_path('assets/drivers/rta_card_image');
//         $emirates_id_path = public_path('assets/drivers/emirates_id');

        $img = Image::make($image->getRealPath());
        $img->resize(600, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);

        $license_file_name = time().'.'.request()->license_image->getClientOriginalExtension();
//         $rta_card_file_name = time().'.'.request()->rta_card_image->getClientOriginalExtension();
//         $emirates_id_file_name = time().'.'.request()->emirates_id->getClientOriginalExtension();

        request()->license_image->move($license_path, $license_file_name);
//         request()->rta_card_image->move($rta_card_image_path, $rta_card_file_name);
//         request()->emirates_id->move($emirates_id_path, $emirates_id_file_name);
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->contact_no = $request->input('contact_no');
        $user->role = 4;
        $user->password =  Hash::make($request->password); //Hash::make($request->input('password'));
        $user->save();

        $user->assignRole('driver');

        $driver->address = $request->input('address');
        $driver->rta_card_expiry = \Carbon\Carbon::now()->toDateTimeString();
        $driver->license_expiry = $request->input('license_expiry');
        $driver->emirates_expiry = \Carbon\Carbon::now()->toDateTimeString();

        $driver->driver_image = $input['imagename'];
        $driver->license_image = $license_file_name;
//         $driver->rta_card_image = $rta_card_file_name;
//         $driver->emirates_id = $emirates_id_file_name;

        $driver->user_id = $user->id;

        $loginUser = Auth()->user();
        if($loginUser->role == 3){
            //for supplier
            $driver->supplier_id = $loginUser->id;
        }

        if($loginUser->role == 2){
            //for staff
            $loginStaff = Staff::where("user_id",$loginUser->id)->first();
            $driver->supplier_id = $loginStaff->supplier_id;
        }

        if (! $driver->save()) {
            $user->delete();
            return back()->with('error', 'Driver Not Saved! Try Again');
        }

        $driver->save();

        return back()
        ->with('success', 'Driver Saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = DB::table('users')
        ->join('drivers', 'users.id', '=', 'drivers.user_id')->where("drivers.user_id", $id)
        ->first();
        return view('pages/drivers/view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = DB::table('users')
        ->join('drivers', 'users.id', '=', 'drivers.user_id')->where("drivers.user_id", $id)
        ->get();

        $user = $user[0];
        return view('pages/drivers/edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $driver = Driver::where("user_id", $id)->first();
        $user = User::find($id);


        $validation_rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'contact_no' => 'required',
            'address' => 'required',
            'license_expiry' => 'required',
            //             'rta_card_expiry' => 'required',
        //             'emirates_expiry' => 'required',
            'driver_image' => 'mimes:jpeg,png,jpg|max:2048',
            //             'rta_card_image' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
        //             'emirates_id' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
            'payment_type' => 'required',

        ];

        if($request->payment_type == "commission"){
            $validation_rules['commission'] = "required";

            $driver->payment_type = $request->payment_type;
            $driver->amount = $request->commission;

        }else if($request->payment_type == "fixed"){
            $validation_rules['fixed'] = "required";

            $driver->payment_type = $request->payment_type;
            $driver->amount = $request->fixed;

        }else{
            return back()->with("Please Select Valid Payment Type");
        }

        $validator = Validator::make($request->all(), $validation_rules);

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }


        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->contact_no = $request->input('contact_no');
        //dd($request->password);
        if($request->password != null)
        {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();


        $driver->address = $request->input('address');
        $driver->rta_card_expiry = \Carbon\Carbon::now()->toDateTimeString();
        $driver->license_expiry = $request->input('license_expiry');
        $driver->emirates_expiry = \Carbon\Carbon::now()->toDateTimeString();

        $image = $request->file('driver_image');
        $license_image = $request->file('license_image');
//         $rta_card_image = $request->file('rta_card_image');
//         $emirates_id = $request->file('emirates_id');


        if (!empty($license_image)) {
            $license_image_name = $driver->license_image;
            $file_path = public_path('assets/drivers/license/').$license_image_name;
            File::delete($file_path);

            $license_image_name = time().'.'.request()->license_image->getClientOriginalExtension();
            $license_path = public_path('assets/drivers/license/');
            request()->license_image->move($license_path, $license_image_name);

            $driver->license_image = $license_image_name;
        }
//         if (!empty($rta_card_image)) {
//             $rta_card_image_name = $driver->rta_card_image;
//             $file_path = public_path('assets/drivers/rta_card/').$rta_card_image_name;
//             File::delete($file_path);

//             $rta_card_image_name = time().'.'.request()->rta_card_image->getClientOriginalExtension();
//             $rta_card_path = public_path('assets/drivers/rta_card/');
//             request()->rta_card_image->move($rta_card_path, $rta_card_image_name);

//             $driver->rta_card_image = $rta_card_image_name;
//         }
//         if (!empty($emirates_id)) {
//             $emirates_id_name = $driver->emirates_id;
//             $file_path = public_path('assets/drivers/emirates_id/').$emirates_id_name;
//             File::delete($file_path);

//             $emirates_id_name = time().'.'.request()->emirates_id->getClientOriginalExtension();
//             $emirates_id_path = public_path('assets/drivers/emirates_id/');
//             request()->license_image->move($emirates_id_path, $emirates_id_name);

//             $driver->emirates_id = $emirates_id_name;
//         }
        if (!empty($image)) {
            // image upload
            $input['imagename'] = $driver->driver_image;

            $destinationPath = public_path('/assets/drivers');
            $img = Image::make($image->getRealPath());
            $img->resize(600, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
            $driver->driver_image = $input['imagename'];
            $driver->save();
        } else {
            // no image upload
            $driver->save();
        }

        return redirect('drivers')
            ->with('success', 'Driver updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $driver = Driver::where("user_id", $id)->first();

        $vehicle =Vehicle::find($driver->vehicle_id);

        $user = User::find($id);

        if (!is_null($driver)) {
            $img_path = public_path('assets/drivers/').$driver->driver_image;
            File::delete($img_path);
            $driver->delete();
            if ($user->delete()) {

                if(!is_null($vehicle)){

                    $vehicle->status = null;
                    $vehicle->driver_id = null;
                    $vehicle->save();

                }

                return back()
                ->with('success', 'Driver Deleted');
            } else {
                return back()
                ->with('error', 'Something went wrong! Please try again');
            }
        } else {
            abort(403, 'Driver Not Found.');
        }
    }
    public function bulkDestroy(Request $request)
    {
        $count = 0;
            foreach($request->seleted_id as $id){

                $user = User::find($id);
                $driver = Driver::where('user_id',$id)->first();
                $vehicle = Vehicle::find($driver->vehicle_id);

                $img_path = public_path('assets/drivers/').$driver->driver_image;
                File::delete($img_path);

                $user->delete();
                $driver->delete();

                if(!is_null($vehicle)){

                    $vehicle->status = null;
                    $vehicle->driver_id = null;
                    $vehicle->save();

                }
                $count++;
            }
            return back()->with("success",$count. Str::plural(' record',$count).' deleted');
    }

    public function assignVehicle($user_id)
    {

        $pre_booking =  User::find($user_id)->booking;
        $vehicles = Vehicle::where('status', null);

        $driver = Driver::where("user_id",$user_id)->first();

        $login_user = Auth()->user();

        if($login_user->role == 3){

            $vehicles = $vehicles->where("supplier_id",$login_user->id);

        }else if($login_user->role == 2){
            $staff = Staff::where("user_id",$login_user->id)->first();
            $vehicles =$vehicles->where("supplier_id",$staff->supplier_id);
        }


      if($pre_booking->count() != 0){
           $v_class =  $pre_booking[0]->v_class;
           $vehicles = $vehicles->where("vehicle_class_id",$v_class)->
           where("supplier_id",$driver->supplier_id)->get();

      }else{

          $vehicles->where("supplier_id",$driver->supplier_id);
          $vehicles = $vehicles->get();
      }


        return view('pages/drivers/assignvehicle', compact('user_id', 'vehicles'));
    }

    public function assignShift($user_id)
    {
       $driver = Driver::where("user_id",$user_id)->first();

        $user = Auth::user();
        if($user->role==3){

            $shifts = Shift::where('supplier_id',$user->id);
        }
        else if($user->role==2){
            $staff = Staff::where("user_id",$user->id)->first();
            $shifts = Shift::where("supplier_id",$staff->supplier_id);
        }
        else if($user->role==1){
            $shifts = Shift::where('supplier_id',$driver->supplier_id);
        }

       $shifts = $shifts->get();
        return view('pages/drivers/assignshift', compact('user_id', 'shifts'));
    }

    public function assignVehicleSave(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'assignvehicle' => 'required|integer',
        ]);

        // return $request->all();
        $vehicle = Vehicle::find($request->assignvehicle);

        $driver = Driver::where("user_id", $request->user_id)->first();

        if (!is_null($driver)) {
            if (!is_null($vehicle)) {
                $driver->vehicle_id = $request->assignvehicle;

                if($driver->status != "assigned"){
                    $driver->status = "available";
                }

                $preVehicle = Vehicle::where("driver_id", $request->user_id)->first();
                if (!is_null($preVehicle)) {
                    $preVehicle->driver_id = null;
                    $preVehicle->status = null;
                    $preVehicle->save();
                }

                $vehicle->driver_id = $driver->user_id;

                if($driver->status != "assigned"){

                    $vehicle->status = "available";
                }else{
                    $vehicle->status = "booked";
                }

                $vehicle->save();
                $driver->save();

                $driver_bookings = Booking::where('driver_id',$driver->user_id)->whereNotIn('status',['no-show', 'finish'])->get();

                foreach($driver_bookings as $booking){
                    $booking->vehicle_id = $driver->vehicle_id;
                    $booking->save();
                }

                $user = User::find($driver->user_id);
                $notification = array(
                    'subject' => "Vehicle assigned",
                    'msg' => "you have one vehicle assigned ",
                    'link' => route('driver.vehicle'),
                    'type' => 'newvehicle',
                    "vehicle_id" => $vehicle
                );

                $user->notify(new CustomNotification($notification));

                return redirect(session('driver_ref_page'))->with('success','Vehicle assigned to Driver');
            } else {
                abort(403, 'Vehicle Not Found.');
            }
        } else {
            abort(403, 'Driver Not Found.');
        }
    }

    public function assignShiftSave(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'assignshift' => 'required|integer',
        ]);

        // return $request->all();
        $shift = Shift::find($request->assignshift);
        $driver = Driver::where("user_id", $request->user_id)->first();

        if (!is_null($driver)) {
            if (!is_null($shift)) {
                $driver->shift_id = $request->assignshift;

                $driver->save();

                return redirect(session('driver_ref_page'))->with('success', 'Shift assigned to Driver');
            } else {
                abort(403, 'Shift Not Found.');
            }
        } else {
            abort(403, 'Driver Not Found.');
        }
    }

    public function removeAssignVehicle($driver_id, Request $request)
    {
        $driver = Driver::where('user_id', $request->user_id)->first();
        $vehicle = Vehicle::where("driver_id", $driver_id)->first();
        $vehicle->driver_id = null;
        $vehicle->status = null;
        $driver->vehicle_id = null;
        $driver->status = null;

        $driver->save();
        $vehicle->save();

        $user = User::find($driver->user_id);
        $notification = array(
            'subject' => "Vehicle removed",
            'msg' => "your assigned vehicle has removed",
            'link' => route('driver.vehicle'),
            'type' => 'newvehicle',
            "vehicle_id" => $vehicle
        );

        $user->notify(new CustomNotification($notification));
        return back()->with('success', 'Vehicle removed from driver');
    }

    public function removeAssignShift($driver_id)
    {
        $driver = Driver::where('user_id', $driver_id)->first();

        $driver->shift_id = null;

        $driver->save();
        return back()->with('error', 'Shift removed from driver');
    }


    public function profilePage()
    {
        $user = DB::table('users')
        ->join('drivers', 'users.id', '=', 'drivers.user_id')->where("drivers.user_id", Auth::user()->id)
        ->get();
        $user = $user[0];
        return view("auth.driver.pages.profile", compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $login_user = Auth::user();
        $user  = User::find($login_user->id);
        $driver_data  = Driver::where("user_id", $login_user->id)->first();

        $validations = [
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'address' => 'required|min:3',
            'contact_no' => 'required|min:3',

        ];

        if ($request->email == $login_user->email) {
            $validations['email'] = 'email';
        } else {
            $validations['email'] = 'email|unique:users';
        }

        if (!is_null($request->password)) {
            $validations['password'] = 'required|confirmed|min:6';
            $user->password = Hash::make($request->password);
        }

        $this->validate($request, $validations);
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->contact_no = $request->contact_no;
        $image = $request->file('driver_image');
        $driver_data->address = $request->address;

        if (!empty($image)) {
            // image upload
            $input['imagename'] = $driver_data->driver_image;

            $destinationPath = public_path('drivers');
            $img = Image::make($image->getRealPath());
            $img->resize(600, 400, function ($constraint) {
            $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
            $driver_data->driver_image = $input['imagename'];
            $driver_data->save();
        } else {
            // no image upload
            $driver_data->save();
        }
        $user->save();

        return back()->with('success', 'Profile Updated');
    }

    public function activedrivers(){
        $users = User::where('active',1)->paginate(10);

        return view('pages/drivers/activedriver',compact('users'));
    }

    public function search(Request $request){
        $driver_info = $request->driver_info;
        $info_type = $request->driver_info_type;
        $driver_vehicle = $request->driver_vehicle_no;
        $driver_status = $request->driver_status;
        $login_user = Auth()->user();

        if($login_user->role == 3){

            $users = DB::table('users')
            ->join('drivers', 'users.id', '=', 'drivers.user_id')
            ->where("supplier_id",$login_user->id)->orderBy('users.id', 'DESC');

        }else if($login_user->role == 2){
           $staff = Staff::where("user_id",$login_user->id)->first();
           $users = DB::table('users')
          ->join('drivers', 'users.id', '=', 'drivers.user_id')
          ->where("supplier_id",$staff->supplier_id)->orderBy('users.id', 'DESC');
        }else{
             $users = DB::table('users')
            ->join('drivers', 'users.id', '=', 'drivers.user_id')->orderBy('users.id', 'DESC');
        }

       // $users = DB::table('users')->join('drivers', 'users.id', '=', 'drivers.user_id');

         if(!empty($driver_info)){

         switch ($info_type) {
            case 'byname':
                $keywords = [];
                $keywords[] = '%'.$driver_info.'%';

                $users = $users->whereRaw("CONCAT_WS(' ',`users`.`first_name`,`users`.`last_name`) LIKE ?", $keywords);
                 break;
            case 'byemail':
                $users = $users->where('users.email',$driver_info);
                break;

            case 'bycontact':
                $users = $users->where('drivers.contact_no',$driver_info);
            break;

         }
        }

         if(!empty($driver_status)){

            if($driver_status == 'null'){
                $driver_status = null;
             }
            $users = $users->where("drivers.status",  $driver_status);
         }

         if(!empty($driver_vehicle)){

            $v = Vehicle::where("license_plate",$driver_vehicle)->first();
            $users = $users->where("drivers.vehicle_id",  $v->id);
         }

         $users = $users->paginate(10);

        session()->flashInput($request->input());
        return view('pages/drivers/manage', compact('users'));
    }
    public function selectSupplier(Request $request){

        $users = DB::table('users')
        ->join('drivers', 'users.id', '=', 'drivers.user_id')
        ->where("supplier_id",$request->supplier_id)->orderBy('users.id', 'DESC')
        ->paginate(10);

        return view('pages/drivers/manage', compact('users'));
    }
    public function blockDriver(Request $request){

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

        return back()->with("warning","Driver Blocked");
    }

    public function unblockDriver(Request $request){


        $user = User::find($request->user_id);
        $user->block_type = Null;
        $user->block_until = Null;

        $user->save();
        return back()->with("success","Driver Unblocked");
    }

    public function approved_and_disapproved($id){
        $drivers = Driver::where('id', $id)->first();
        // dd($drivers);
        if ($drivers) {
            if ($drivers->admin_approve == 1) {
                Driver::where('id', $drivers->id)->update([
                    'admin_approve' => 0,
                ]);
                $msg = "Driver status goes to pending";
            } else {
                Driver::where('id', $drivers->id)->update([
                    'admin_approve' => 1,
                ]);
                $msg = "Driver status is approved";
            }
        }else{
            $msg = "Something went wrong";
        }
        return back()->with('success',$msg);
    }
}
