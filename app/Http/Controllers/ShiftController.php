<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Driver;
use App\Models\Staff;

use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    
    public function __construct()
    {
        //create permission
        $this->middleware('permission:shift-create')->only(['create','store']);
        
        //view permission
        $this->middleware('permission:shift-view')->only('show');

        //update permission
        $this->middleware('permission:shift-edit')->
        only(['edit','update']);
        

        //delete permission
        $this->middleware('permission:shift-delete')->only('destroy');
    }

    public function index()
    {
        $user = Auth::user();

        if($user->role==3){
    
            $shifts = Shift::where("supplier_id",$user->id)->latest()->paginate(10);
        }
        else if($user->role==2){
            $staff = Staff::where("user_id",$user->id)->first();
            $shifts = Shift::where("supplier_id",$staff->supplier_id)->latest()->paginate(10);
        }else{

            $shifts = Shift::latest()->paginate(10);
        }

        return view("pages.shift.manage", compact('shifts'));
    }

   
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        
        session(["shift_prev_form_type" => "add-new-form"]);


        $user = Auth::user();

        $validation_array =  [
            'name' => 'required|max:255',
            'start' => 'required|max:255',
            'end' => 'required|max:255',
        ];

        $custom_msgs=
            [
                'start.required' => "Shift start time is required",
                'end.required' => "Shift end time is required"
            ];
         
         $validator = Validator::make($request->all(), $validation_array,$custom_msgs);

         
        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        $start = date("H:i", strtotime($request->start));
         $end = date("H:i", strtotime($request->end));

        

        $shift = new Shift;
        $shift->name = $request->name;
        $shift->start = $start;
        $shift->end = $end;

        if($user->role == 3){
            //for supplier
            $shift->supplier_id = $user->id;
        }
        $shift->save();

        return back()
      ->with('success', 'Shift Saved');
    }

   
    public function show(Shift $shift)
    {
        //
    }

 
    public function edit(Shift $shift)
    {
        return view("pages.shift.edit", compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {

        session(["shift_prev_form_type" => "update-form"]);

        $validation_array =  [
            'name' => 'required|max:255',
            'start' => 'required|max:255',
            'end' => 'required|max:255',
        ];

        $custom_msgs=
            [
                'start.required' => "Shift start time is required",
                'end.required' => "Shift end time is required"
            ];
         
         $validator = Validator::make($request->all(), $validation_array,$custom_msgs);
         $start = date("H:i", strtotime($request->start));
         $end = date("H:i", strtotime($request->end));

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        if($end <= $start){
            return back()->withErrors(["invaild" => "End time must be greater than Start time"])->withInput();
        }
       
       
        $shift->name = $request->name;
        $shift->start = $start;
        $shift->end = $end;

        $shift->save();

        return  redirect('shift')
      ->with('success', 'Shift Saved');
    }

    public function destroy(Shift $shift)
    {

        if (!is_null($shift)) {
            if ($shift->delete()) {


                 $drivers = Driver::where('shift_id',$shift->id)->get();

                 if(!is_null($drivers)){
                     foreach($drivers as $driver){
                        $driver->shift_id = NULL;
                        $driver->save();
                     }
                }
                return back()
                ->with('success', 'Shift Deleted');
            } else {
                return back()
                ->with('error', 'Something went wrong! Please try again');
            }
        } else {
            abort(403, 'Shift Not Found.');
        }
    }
    public function bulkDestroy(Request $request)
    {
        $count = 0;
            foreach($request->seleted_id as $id){
                $shift = Shift::find($id);
                $shift->delete();

                $drivers = Driver::where('shift_id',$shift->id)->get();

                if(!is_null($drivers)){
                    foreach($drivers as $driver){
                       $driver->shift_id = NULL;
                       $driver->save();
                    }
               }

                $count++;
            }
            return back()->with("success",$count. Str::plural(' record',$count).' deleted');
    }
    public function selectSupplier(Request $request){
     
        $shifts = Shift::where("supplier_id",$request->supplier_id)->paginate(10);   
     
        return view('pages/shift/manage', compact('shifts'));
    }
}
