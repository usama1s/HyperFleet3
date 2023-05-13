<?php

namespace App\Http\Controllers;

use App\Models\Voucher;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Session;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth()->user();
        if($user->role == 3){
            $vouchers = Voucher::where('creater_id',$user->id)->latest()->paginate(10);

        }
        else if($user->role == 2){
            $staff = Staff::where('user_id',$user->id)->first();
            $vouchers = Voucher::where('creater_id',$staff->supplier_id)->latest()->paginate(10);
        }
        else if($user->role == 1){
            $vouchers = Voucher::latest()->paginate(10);
         
                    
         
        }
        else{
            return abort(403, 'Voucher Not Found.');
        }

       
        return view('pages.bookings.vouchers.manage',compact('vouchers'));
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


        session(["voucher_prev_form_type" => "add-new-form"]);

        $user = Auth::user();
        $validator = Validator::make(
            $request->all(),
            [
            'code' => 'unique:vouchers|required',
            'voucher_type' => 'required',
            'discount' => 'required|numeric'
            ]

        );

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        $voucher = new Voucher;
        $voucher->creater_id = $user->id;
        $voucher->code = $request->code;
        $voucher->type = $request->voucher_type;
        $voucher->description = $request->description;
        $voucher->discount = $request->discount;

        // $voucher->fill([
        //     'description' => Crypt::encryptString($request->description)            
        // ])->save();


        $voucher->save();
        session()->forget('voucher_prev_form_type');
        
        return back()
      ->with('success', 'Voucher Saved');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit(Voucher $voucher)
    {
       
    }

    public function validateVoucher(Request $request){


       $code = $request->voucher_code;
       $supplier_id = $request->supplier_id;

    

       $voucher = Voucher::where('code',$code)->where("creater_id",$supplier_id)->first();
       
        if(is_null($voucher)){

            $respose['error'] = "Voucher not found";
            $respose['success'] = "false";

        }
            else{
                    $respose['success'] = "true";

                if($voucher->type == "flat"){
                    
                    $respose['discout'] = "flat ".$voucher->discount;
                    $respose['type'] = "flat";
                    
                }else{
                    $respose['discout'] = $voucher->discount."%";
                    $respose['type'] = "percentage";
                }

                $respose['value'] =$voucher->discount;
            }


        return $respose;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Voucher $voucher)
    {

        session(["voucher_prev_form_type" => "update-new-form"]);
     
        $validations_rules =  [
            'code' => 'required',
            'voucher_type' => 'required',
            'discount' => 'required|numeric|max:255'
        ];

        if($request->code != $voucher->code){
           
            $validations_rules['code'] = "unique:vouchers|required";
            $voucher->code = $request->code;
        }

        $validator = Validator::make(
            $request->all(),
            $validations_rules
        );

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

       
        $voucher->type = $request->voucher_type;
        $voucher->description = $request->description;
        $voucher->discount = $request->discount;

        $voucher->save();

        session()->forget('voucher_prev_form_type');
        return back()
      ->with('success', 'Voucher updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voucher $voucher)
    {
        if (!is_null($voucher)) {
            if ($voucher->delete()) {
                return redirect('vouchers')
                ->with('success', 'Voucher Deleted');
            } else {
                return back()
                ->with('error', 'Something went wrong! Please try again');
            }
        } else {
            abort(403, 'Voucher Not Found.');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $count = 0;
            foreach($request->seleted_id as $id){
                $voucher = Voucher::find($id);
                $voucher->delete();
                $count++;
            }
            return back()->with("success",$count. Str::plural(' record',$count).' deleted');
    }
}
