<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\PricingScheme;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use Image;
use File;
use Illuminate\Support\Facades\DB;
use Auth;

class VehicleController extends Controller
{
    public function __construct()
    {
        //create permission
        $this->middleware('permission:vehicle-create')->only(['create','store']);
        
        //view permission
        $this->middleware('permission:vehicle-view')->only('show');

        //update permission
        $this->middleware('permission:vehicle-edit')->
        only(['edit','update','assignToDriver','removeAssignToDriver','assignToDriverSave','goToMaintenance','removeMaintenance']);

        //delete permission
        $this->middleware('permission:vehicle-delete')->only('destroy');
    }
   
    public function index()
    {
       
        $user = Auth()->user();
        if($user->role == 3){
            $vehicles = Vehicle::where("supplier_id",$user->id)->orderBy("id","DESC")->paginate(10);
        }else if($user->role == 2){
            $staff = Staff::where("user_id",$user->id)->first();
            $vehicles = Vehicle::where("supplier_id",$staff->supplier_id)->orderBy("id","DESC")->paginate(10);
        }else{
            $vehicles = Vehicle::orderBy("id","DESC")->paginate(10);
        }
        return view('pages/vehicle/manage', compact('vehicles'));
    }

    public function create()
    {
        $user = Auth()->user();
        if($user->role == 3){
            $pricings = PricingScheme::where("supplier_id",$user->id)->orderBy("id","DESC")->get();
        }else if($user->role == 2){
            $staff = Staff::where("user_id",$user->id)->first();
            $pricings = PricingScheme::where("supplier_id",$staff->supplier_id)->orderBy("id","DESC")->get();
        }else{
            $pricings = PricingScheme::where("supplier_id",null)->orderBy("id","DESC")->get();
        }
        return view('pages/vehicle/create',compact('pricings'));
    }

    public function showAllJSON()
    {
        $data = DB::table('vehicles')
                    ->join('vehicle_classes', 'vehicles.vehicle_class_id', '=', 'vehicle_classes.id')
                    ->select('vehicles.*', 'vehicle_classes.name')
                    ->get();

        return array("data" => $data);
    }


    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
            'license_plate' => 'required|unique:vehicles|max:255',
            'car_model' => 'required',
            'car_manufacturer' => 'required',
            'car_year' => 'required',
            'car_color' => 'required',
            'insurance_expriy' => 'required',
            'insurance_detail' => 'required',
            'registration_expriy' => 'required',
            'registration_detail' => 'required',
            'vtc_expriy' => 'required',
            'vtc_detail' => 'required',
            'inspection_expriy' => 'required',
            'inspection_detail' => 'required',
            'seats' => 'required|digits_between:0,9',
            'luggage' => 'required|digits_between:0,9',
            'vehicle_class_id' => 'required',
            'price' => 'required',
//            'description' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg|max:2048',
            'insurance_file' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
            'registration_file' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
            'vtc_file' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
            'inspection_file' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
        ]
        );

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }
       
        $image = $request->file('image');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
     
        $destinationPath = public_path('assets/vehicles');
        $insurance_path = public_path('assets/vehicles/insurance');
        $registration_path = public_path('assets/vehicles/registration');
        $vtc_path = public_path('assets/vehicles/vtc');
        $inspection_path = public_path('assets/vehicles/inspection');

        $img = Image::make($image->getRealPath());
        $img->resize(600, 400, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$input['imagename']);
  
        $insurance_file_name = time().'.'.request()->insurance_file->getClientOriginalExtension();
        $registration_file_name = time().'.'.request()->registration_file->getClientOriginalExtension();
        $vtc_file_name = time().'.'.request()->vtc_file->getClientOriginalExtension();
        $inspection_file_name = time().'.'.request()->inspection_file->getClientOriginalExtension();

        request()->insurance_file->move($insurance_path, $insurance_file_name);
        request()->registration_file->move($registration_path, $registration_file_name);
        request()->vtc_file->move($vtc_path, $vtc_file_name);
        request()->inspection_file->move($inspection_path, $inspection_file_name);

        $vehicle = new Vehicle;
        $vehicle->license_plate = $request->license_plate;
        $vehicle->vehicle_class_id = $request->vehicle_class_id;
        $vehicle->manufacturer = $request->car_manufacturer;
        $vehicle->car_model = $request->car_model;
        $vehicle->car_year = $request->car_year;
        $vehicle->car_color = $request->car_color;
        $vehicle->insurance_detail = $request->insurance_detail;
        $vehicle->insurance_expiry = $request->insurance_expriy;
        $vehicle->registration_detail = $request->registration_detail;
        $vehicle->registration_expiry = $request->registration_expriy;
        $vehicle->vtc_detail = $request->vtc_detail;
        $vehicle->vtc_expiry = $request->vtc_expriy;
        $vehicle->inspection_detail = $request->inspection_detail;
        $vehicle->inspection_expiry = $request->inspection_expriy;
        $vehicle->seats = $request->seats;
        $vehicle->luggage = $request->luggage;
    
        $vehicle->price = 0;
        $vehicle->pricing_scheme_id = $request->price;  // was pricing_scheme_id
        $vehicle->description = "default value";// $request->description;

        $loginUser = Auth()->user();
        if($loginUser->role == 3){
            //for supplier
            $vehicle->supplier_id = $loginUser->id;
        }     

        if($loginUser->role == 2){
            //for staff
            $loginStaff = Staff::where("user_id",$loginUser->id)->first();
            $vehicle->supplier_id = $loginStaff->supplier_id;
        }

        //files and images
        $vehicle->image = $input['imagename'];
        $vehicle->insurance_file = $insurance_file_name;
        $vehicle->registration_file = $registration_file_name;
        $vehicle->vtc_file = $vtc_file_name;
        $vehicle->inspection_file = $inspection_file_name;
        
        $vehicle->save();

        return back()
            ->with('success', 'Vehicle Saved');
    }

   
    public function show(Vehicle $vehicle)
    {
        return view('pages/vehicle/view', compact('vehicle'));
    }

  
    public function edit(Vehicle $vehicle)
    {
        $pricings = PricingScheme::get();
        return view('pages/vehicle/edit', compact('vehicle','pricings'));
    }

   
    public function update(Request $request, Vehicle $vehicle)
    {
        $vehicle = Vehicle::find($vehicle->id);

        if ($vehicle->license_plate == $request->license_plate) {
            // license_plate not exsit in db
            $request->validate([
                'license_plate' => 'required|max:255',
                'car_model' => 'required',
                'car_manufacturer' => 'required',
                'car_year' => 'required',
                'car_color' => 'required',
                'insurance_expriy' => 'required',
                'insurance_detail' => 'required',
                'registration_expriy' => 'required',
                'registration_detail' => 'required',
                'vtc_expriy' => 'required',
                'vtc_detail' => 'required',
                'inspection_expriy' => 'required',
                'inspection_detail' => 'required',
                'seats' => 'required',
                'luggage' => 'required',
                'vehicle_class_id' => 'required',
                'price' => 'required',
//                'description' => 'required',
                'image' => 'mimes:jpeg,png,jpg|max:2048',
                'insurance_file' => 'mimes:jpeg,png,jpg,pdf|max:2048',
                'registration_file' => 'mimes:jpeg,png,jpg,pdf|max:2048',
                'vtc_file' => 'mimes:jpeg,png,jpg,pdf|max:2048',
                'inspection_file' => 'mimes:jpeg,png,jpg,pdf|max:2048',
            ]);
        } else {
            // license_plate  exsit in db
            $request->validate([
                'license_plate' => 'required|unique:vehicles|max:255',
                'car_model' => 'required',
                'car_manufacturer' => 'required',
                'car_year' => 'required',
                'car_color' => 'required',
                'insurance_expriy' => 'required',
                'insurance_detail' => 'required',
                'registration_expriy' => 'required',
                'registration_detail' => 'required',
                'vtc_expriy' => 'required',
                'vtc_detail' => 'required',
                'inspection_expriy' => 'required',
                'inspection_detail' => 'required',
                'seats' => 'required',
                'luggage' => 'required',
                'vehicle_class_id' => 'required',
                'price' => 'required',
//                'description' => 'required',
                'image' => 'mimes:jpeg,png,jpg|max:2048',
                'insurance_file' => 'mimes:jpeg,png,jpg,pdf|max:2048',
                'registration_file' => 'mimes:jpeg,png,jpg,pdf|max:2048',
                'vtc_file' => 'mimes:jpeg,png,jpg,pdf|max:2048',
                'inspection_file' => 'mimes:jpeg,png,jpg,pdf|max:2048',
            ]);
            $vehicle->license_plate = $request->license_plate;
        }

        $vehicle->vehicle_class_id = $request->vehicle_class_id;
        $vehicle->manufacturer = $request->car_manufacturer;
        $vehicle->car_model = $request->car_model;
        $vehicle->car_year = $request->car_year;
        $vehicle->car_color = $request->car_color;
        $vehicle->insurance_detail = $request->insurance_detail;
        $vehicle->insurance_expiry = $request->insurance_expriy;
        $vehicle->registration_detail = $request->registration_detail;
        $vehicle->registration_expiry = $request->registration_expriy;        
        $vehicle->vtc_detail = $request->vtc_detail;
        $vehicle->vtc_expiry = $request->vtc_expriy;
        $vehicle->inspection_detail = $request->inspection_detail;
        $vehicle->inspection_expiry = $request->inspection_expriy;
        $vehicle->seats = $request->seats;
        $vehicle->luggage = $request->luggage;        
        // $vehicle->price = $request->price;
        $vehicle->pricing_scheme_id = $request->price;
        $vehicle->description = "default value"; // $request->description;
        $image = $request->file('image');
        $registration_file = $request->file('registration_file');
        $insurance_file = $request->file('insurance_file');
        $vtc_file = $request->file('vtc_file');
        $inspection_file = $request->file('inspection_file');
     
        
        if (!empty($inspection_file)) {
            $inspection_file_name = $vehicle->inspection_file;
            $file_path = public_path('assets/vehicles/inspection/').$inspection_file_name;
            File::delete($file_path);
            
            $inspection_file_name = time().'.'.request()->inspection_file->getClientOriginalExtension();
            $inspection_path = public_path('assets/vehicles/inspection/');
            request()->inspection_file->move($inspection_path, $inspection_file_name);
            
            $vehicle->inspection_file = $inspection_file_name;
        }
        
        if (!empty($vtc_file)) {
            $vtc_file_name = $vehicle->vtc_file;
            $file_path = public_path('assets/vehicles/vtc/').$vtc_file_name;
            File::delete($file_path);
            
            $vtc_file_name = time().'.'.request()->vtc_file->getClientOriginalExtension();
            $vtc_path = public_path('assets/vehicles/vtc/');
            request()->vtc_file->move($vtc_path, $vtc_file_name);
            
            $vehicle->vtc_file = $vtc_file_name;
        }
 
        if (!empty($registration_file)) {
            $registration_file_name = $vehicle->registration_file;
            $file_path = public_path('assets/vehicles/registration/').$registration_file_name;
            File::delete($file_path);
                                            
            $registration_file_name = time().'.'.request()->registration_file->getClientOriginalExtension();
            $registration_path = public_path('assets/vehicles/registration/');
            request()->registration_file->move($registration_path, $registration_file_name);

            $vehicle->registration_file = $registration_file_name;
        }

        if (!empty($insurance_file)) {
            //old file name
            $insurance_file_name = $vehicle->insurance_file;
            $file_path = public_path('assets/vehicles/insurance/').$insurance_file_name;
            File::delete($file_path);

            //new file name
            $insurance_file_name = time().'.'.request()->insurance_file->getClientOriginalExtension();
            $insurance_path  = public_path('assets/vehicles/insurance/');
            request()->insurance_file->move($insurance_path, $insurance_file_name);
            
            $vehicle->insurance_file = $insurance_file_name;
        }

        if (!empty($image)) {
            // image upload
            $input['imagename'] = $vehicle->image;
            $destinationPath = public_path('assets/vehicles/');
//             $destinationPath = public_path('assets\vehicles\\'); // added for localhost dev win10 - switch for live server
            $img = Image::make($image->getRealPath());
            $img->resize(600, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.$input['imagename']);
//         })->save($destinationPath.'\\'.$input['imagename']); // added for localhost dev win10 - switch for live server
            $vehicle->image = $input['imagename'];

//            $image->move($destinationPath, $input['imagename']);  // das wäre user uploaded file!!! TODO: remove
        }
        
        if ($vehicle->save()) {
            return back()
            ->with('success', 'Vehicle Updated');
        } else {
            return back()
            ->with('error', 'Something went wrong! Please try again');
        }
    }

    public function assignToDriver($vehicle_id)
    {
      
        $vehicle = Vehicle::find($vehicle_id);
        $drivers = Driver::where('vehicle_id',NULL);
        
        $login_user = Auth()->user();

        if($login_user->role == 3){

            $drivers = $drivers->where("supplier_id",$login_user->id);

        }else if($login_user->role == 2){
            $staff = Staff::where("user_id",$login_user->id)->first();
            $drivers =$drivers->where("supplier_id",$staff->supplier_id);
        }
        
            $drivers->where("supplier_id", $vehicle->supplier_id);

        
        $drivers = $drivers->get();

        return view('pages.vehicle.assign_to_driver', compact('drivers','vehicle_id'));
    }

    public function assignToDriverSave(Request $request)
    {

        $request->validate([
            'vehicle_id' => 'required|integer',
            'assigntodriver' => 'required|integer',
        ]);

        $vehicle = Vehicle::find($request->vehicle_id);
        $driver = Driver::where("user_id", $request->assigntodriver)->first();
        if (!is_null($vehicle)) {
            if (!is_null($driver)) {
                $vehicle->driver_id = $driver->user_id;
                $vehicle->status = "available";
                $preDriver = Driver::where("vehicle_id", $request->vehicle_id)->first();
                if (!is_null($preDriver)) {
                    $preDriver->vehicle_id = null;
                    $preDriver->status = null;
                    $preDriver->save();
                }
                $driver->vehicle_id = $vehicle->id;
                $driver->status = "available";
                $driver->save();
                
                if ($vehicle->save()) {
                   
                   
                    //return back()->with('success', 'Vechicle Assigned to the Driver');
                     return redirect(session('vehicle_ref_page'))->with('success', 'Vechicle Assigned to the Driver');
                }
            } else {
                abort(403, 'Driver Not Found.');
            }
        } else {
            abort(403, 'Vehicle Not Found.');
        }

    }

    public function removeAssignToDriver($vehicle_id)
    {
        $vehicle = Vehicle::find($vehicle_id);
         $driver = Driver::where("vehicle_id", $vehicle_id)->first();
        $vehicle->driver_id = null;
        $vehicle->status = null;
        $driver->vehicle_id = null;
        $driver->status = null;

        $driver->save();
        $vehicle->save();
        return back()->with('success', 'Driver removed from the Vehicle');
    }

    public function goToMaintenance($vehicle_id)
    {
         $vehicle = Vehicle::find($vehicle_id);
         $driver = Driver::where("vehicle_id", $vehicle_id)->first();

         if(!is_null($driver)){
            $driver->status = null;
            $driver->vehicle_id = null;
            $driver->save();
         }
        
        $vehicle->status = "maintenance";
        $vehicle->driver_id = null;
        $vehicle->save();
        
        return $vehicle;
        //return back();
    }

    public function removeMaintenance($vehicle_id)
    {
        $vehicle = Vehicle::find($vehicle_id);
        $driver = Driver::where("vehicle_id", $vehicle_id)->first();

        if(!is_null($driver)){
            $driver->status = null;
            $driver->vehicle_id = null;
            $driver->save();
         }
        
        $vehicle->status = null;
        $vehicle->driver_id = null;
        
        $vehicle->save();
        
        return $vehicle;
        //return back();
    }

   
    public function destroy($id)
    {
         $vehicle = Vehicle::find($id);
         $driver = Driver::where("user_id",$vehicle->driver_id)->first();

        
        if (!is_null($vehicle)) {
            $img_path = public_path('assets/vehicles/').$vehicle->image;
            File::delete($img_path);
            if ($vehicle->delete()) {

                if(!is_null($driver)){
                    $driver->status = null;
                    $driver->vehicle_id = null;
                    $driver->save();
                }

                return back()->with('success', 'Vehicle Deleted');
            } else {
                return back()->with('error', 'Something wrong went! Please try again');
            }
        } else {
            abort(403, 'Vehicle Not Found.');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $count = 0;
            foreach($request->seleted_id as $id){
                $vehicle = Vehicle::find($id);
                $driver = Driver::where("user_id",$vehicle->driver_id)->first();

                $img_path = public_path('assets/vehicles/').$vehicle->image;
                File::delete($img_path);

                if($vehicle->delete()){

                    if(!is_null($driver)){
                        $driver->status = null;
                        $driver->vehicle_id = null;
                        $driver->save();
                    }
            }
                $count++;
            }
            return back()->with("success",$count. Str::plural(' record',$count).' deleted');
    }

    public function search(Request $request){

        $by_vehicle_model = $request->by_vehicle_model;
        $by_vehicle_no = $request->by_vehicle_no;
        $by_vehicle_class = $request->by_vehicle_class;
        $by_vehicle_status = $request->by_vehicle_status;
        $by_drives = $request->by_drives;

        $vehicles = DB::table('vehicles');

        $user = Auth()->user();
       
        if($user->role == 3){

            $vehicles =  $vehicles->where("supplier_id",$user->id);

        }else if($user->role == 2){
            $staff = Staff::where("user_id",$user->id)->first();
            $vehicles = $vehicles->where("supplier_id",$staff->supplier_id);
        }else{
            
        }
         
        if(!empty($by_vehicle_model)){
    
            $vehicles = $vehicles->where('vehicles.car_model',$by_vehicle_model);
        }
        
        if(!empty($by_vehicle_no)){
    
            $vehicles = $vehicles->where('vehicles.license_plate',$by_vehicle_no);
        }

        if(!empty($by_vehicle_class)){
    
            $vehicles = $vehicles->where('vehicles.vehicle_class_id',$by_vehicle_class);
        }

        if(!empty($by_vehicle_status)){
    

            if($by_vehicle_status == "null"){
                $by_vehicle_status = null;
            }
            
            $vehicles = $vehicles->where('vehicles.status',$by_vehicle_status);
        }

        if(!empty($by_drives)){
    
            $vehicles = $vehicles->where('vehicles.driver_id',$by_drives);
        }

         $vehicles = $vehicles->paginate(10);
         

        session()->flashInput($request->input());
        return view('pages/vehicle/manage', compact('vehicles'));
    }

    public function selectSupplier(Request $request){
     
        $vehicles = Vehicle::where("supplier_id",$request->supplier_id)->paginate(10);   
       
        return view('pages/vehicle/manage', compact('vehicles'));
    }
    
    public function assignedvehicle(){

       $vehicle = Vehicle::where('driver_id', Auth::user()->id)->first();

         return view('auth.driver.pages.vehicles',compact('vehicle'));
       
    }

    public function approved_and_disapproved($id){        
        $vehicle = Vehicle::where('id', $id)->first();

        if ($vehicle) {
            if ($vehicle->admin_approve == 1) {
                Vehicle::where('id', $vehicle->id)->update([
                    'admin_approve' => 0,
                ]);

                $msg = "Vehicle status goes to pending";

            } else {
                Vehicle::where('id', $vehicle->id)->update([
                    'admin_approve' => 1,
                ]);
                $msg = "Vehicle approved";
            }
        }else{
            $msg = "Something went wrong";
        }
        
        return back()->with('success',$msg);
    }
}