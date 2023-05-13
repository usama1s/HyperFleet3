<?php

namespace App\Http\Controllers;

use App\Models\VehicleClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

use File;
use DB;
use Auth;


class VehicleClassController extends Controller
{
    public function __construct()
    {
        //create permission
        // $this->middleware('permission:vehicle-create')->only(['create','store']);
        
        //view permission
        // $this->middleware('permission:vehicle-view')->only('show');

        //update permission
        // $this->middleware('permission:vehicle-edit')-> only(['edit','update']);

        //delete permission
        // $this->middleware('permission:vehicle-delete')->only('destroy');
    }

    public function index()
    {
        $vehicle_classes = VehicleClass::all();
        return view('pages.vehicle.vehicle_class',compact('vehicle_classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(auth()->user()->role == 1){

            session(["vehicle_class_prev_form_type" => "add-new-form"]);
    
            $request->validate([
                'name' => 'required|unique:vehicle_classes|max:255',
//                'vehicle_class_price' => 'required',
                 'vehicle_class_thumbnail' => 'required|mimes:jpeg,png,jpg|max:2048',
//                'vehicle_class_price_descp' => 'required',
//                'vehicle_class_price_passangers' => 'required',
//                'vehicle_class_price_bags' => 'required',
            ],[
//                'vehicle_class_price_bags.required' => "The bags field is required.",
//                'vehicle_class_price_passangers.required' => "The passengers field is required.",
                'vehicle_class_price_descp.required' => "The description field is required.",
            ]);
    
            $class = new VehicleClass;
            $class->name = $request->name;
            $class->price = '0';
            $class->desc = $request->vehicle_class_price_descp;
                        $class->passengers = '1';
                        $class->bags = '1';
    
            $image = $request->file('vehicle_class_thumbnail');
            $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
    
            $destinationPath = public_path('assets/vehicle-class');
    
            $img = Image::make($image->getRealPath());
            $img->resize(600, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
    
            $class->thumbnail = $input['imagename'];
    
            $class->save();
    
            session()->forget('vehicle_class_prev_form_type');
    
            return back()
            ->with('success', 'Vehicle Class Saved');
        }else{
            return back()
            ->with('error', "Your don't have permission to Vehicle Class ");
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VehicleClass  $vehicleClass
     * @return \Illuminate\Http\Response
     */
    public function show(VehicleClass $vehicleClass)
    {
        //
    }

    public function showAllJSON()
    {
        return array("data" => VehicleClass::all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VehicleClass  $vehicleClass
     * @return \Illuminate\Http\Response
     */
    public function edit(VehicleClass $vehicleClass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VehicleClass  $vehicleClass
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleClass $vehicles_class)
    {

        session(["vehicle_class_prev_form_type" => "update-form"]);

        if($request->name == $vehicles_class->name){

                $validations_rules =  [
                    'name' => 'required',
                    //                    'vehicle_class_price' => 'required',
                    'vehicle_class_price_descp' => 'required',
                    //                    'vehicle_class_price_passangers' => 'required',
                //                    'vehicle_class_price_bags' => 'required',
                ];
        }
        else{
            $validations_rules =  [
                'name' => 'required|unique:vehicle_classes|max:255',
            ];
        }
        
        $err_mesgs = [
        //            'vehicle_class_price_bags.required' => "Bags Field is required.",
        //            'vehicle_class_price_passangers.required' => "Passengers Field is required.",
            'vehicle_class_price_descp.required' => "Description Field is required.",
        ];
        $validator = Validator::make(
            $request->all(),
            $validations_rules,
            $err_mesgs
        );

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        $image = $request->file('vehicle_class_thumbnail');

        if (!empty($image)) {
            $image_name = $vehicles_class->thumbnail;
            $file_path = public_path('assets/vehicle-class/').$image_name;
            File::delete($file_path);
                                            
            $image_file_name = time().'.'.request()->vehicle_class_thumbnail->getClientOriginalExtension();
            $image_file_name_path = public_path('assets/vehicle-class/');
            request()->vehicle_class_thumbnail->move($image_file_name_path, $image_file_name);

            $vehicles_class->thumbnail = $image_file_name;
        }

        $vehicles_class->name = $request->name;
//         $vehicles_class->price = $request->vehicle_class_price;
        $vehicles_class->desc = $request->vehicle_class_price_descp;
//         $vehicles_class->passengers = $request->vehicle_class_price_passangers;
//         $vehicles_class->bags = $request->vehicle_class_price_bags;
        $vehicles_class->save();


        session()->forget('vehicle_class_prev_form_type');

        return back()
        ->with('success', 'Vehicle Class updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VehicleClass  $vehicleClass
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleClass $vehicleClass)
    {
        if(!is_null($vehicleClass)){
            $img_path = public_path('assets/vehicle-class/').$vehicleClass->thumbnail;
            File::delete($img_path);
            $vehicleClass->delete();
            return back()
            ->with('success', 'VehicleClass Deleted');
        }else{
            abort(403, 'VehicleClass Not Found.');
        }
    }

    public function bulkDestroy(Request $request){
        
        $count = 0;
        foreach($request->seleted_id as $id){
            $vlcass = VehicleClass::find($id);

            $img_path = public_path('assets/vehicle-class/').$vlcass->thumbnail;
            File::delete($img_path);

            $vlcass->delete();
            $count++;
        }
        return back()->with("success",$count. Str::plural(' record',$count).' deleted');

    }
}
