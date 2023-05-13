<?php

namespace App\Http\Controllers;

use File;

use App\Models\API;
use App\Models\User;
use App\Models\Supplier;
use App\Models\BankDetail;
use App\Models\PricingScheme;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Image;

class SupplierController extends Controller
{
    public function __construct()
    {
        //create permission
        $this->middleware('permission:supplier-create')->only(['create','store']);

        //view permission
        $this->middleware('permission:supplier-view')->only('show');

        //update permission
        $this->middleware('permission:supplier-edit')->
        only(['edit','update']);


        //delete permission
        $this->middleware('permission:supplier-delete')->only('destroy');
    }

    public function index()
    {
        $users = DB::table('users')
        ->join('suppliers', 'users.id', '=', 'suppliers.user_id')->orderBy('suppliers.id', 'DESC')->paginate(10);

        return view('pages.suppliers.manage', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            // 'agreement' => 'required',
            // 'credit_limit' => 'required',
            'email' => 'required|email|unique:users',
            'contact_no' => 'required',
            'address' => 'required',
            'sales_person' => 'required',
            'payment_method' => 'required',
            'commission' => 'required|numeric',
            // 'details' => 'required',

            'payment_terms' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,pdf|max:2048',
            'password' => 'required',


            'bank_name' => 'required',
            'account_title' => 'required',
            'iban' => 'required',
            'bic_swift' => 'required',


        ]);

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        // dd($request->all());

        $supplier = new Supplier;
        $user = new User;
        $bank = new BankDetail;
        $image = $request->file('image');

        if (!empty($image)) {
            $image = $request->file('image');
            $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('storage/assets/suppliers/');
            $img = Image::make($image->getRealPath());
            $img->save($destinationPath.$input['imagename']);
            $supplier->image = $input['imagename'];
        }


//         if (!is_null($request->file('image'))) {
//             $image = $request->file('image');
//             $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
//             $path = $request->file('image')->storeAs(public_path('storage/assets/suppliers/'), $input['imagename']);
//             $supplier->image = $input['imagename'];
//         }



        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->contact_no = $request->input('contact_no');
        $user->role = 3;
        $user->password = Hash::make($request->input('password'));

        $supplier->company_name = $request->input('company_name');

        $supplier->agreement = null; //$request->input('agreement');
        $supplier->credit = null; //$request->input('credit_limit');
        $supplier->address = $request->input('address');
        $supplier->sales_person = $request->input('sales_person');
        $supplier->payment_method = $request->input('payment_method');
        $supplier->commission = $request->input('commission');
        $supplier->details = null; //$request->input('details');
        // $supplier->bank_details = $request->input('bank_details');
        $supplier->payment_terms = $request->input('payment_terms');
//         $supplier->image = $input['imagename'];


        $bank->bank_name = $request->bank_name;
        $bank->account_title = $request->account_title;
        $bank->iban = $request->iban;
        $bank->bic_swift = $request->bic_swift;

        DB::beginTransaction();

        try {
        // DB::transaction(function () use ($user, $bookingAgent, $bank) {


                $user->save();
                $supplier->user_id = $user->id;
                $bank->user_id = $user->id;
                $supplier->save();
                $bank->save();
                $user->assignRole('supplier');
                DB::commit();

                return back()
                ->with('success', 'Supplier Saved');


        // }, 1);

        // all good
        } catch (\Exception $e) {

        DB::rollback();

        throw $e;
        // something went wrong
        } catch (\Throwable $e) {
        DB::rollback();

        throw $e;
        }

        return back()->with('error', 'Supplier Not Saved! Try Again');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $user = DB::table('users')
        // ->join('suppliers', 'users.id', '=', 'suppliers.user_id')->where("suppliers.user_id", $id)->first();

        $user = User::findOrFail($id);


        $drives = DB::table('users')
        ->join('drivers', 'users.id', '=', 'drivers.user_id')
        ->where("drivers.supplier_id",$id)->get();

        $vehicles = DB::table('vehicles')
        ->where("supplier_id",$id)->get();

        $pricing_list = PricingScheme::where('supplier_id', $user->id)->paginate(20);

        $bookings = DB::table('bookings')->where("supplier_id",$id)->paginate();


        return view('pages/suppliers/view', compact('user','drives','vehicles','pricing_list','bookings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $user = DB::table('users')
        // ->join('suppliers', 'users.id', '=', 'suppliers.user_id')->where("suppliers.user_id", $id)->first();

        $user = User::findOrFail($id);

        return view('pages/suppliers/edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $supplier = $user->supplier;

        $validations_rule = [

            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            // 'agreement' => 'required',
            // 'credit_limit' => 'required',
            'email' => 'required|email|unique:users',
            'contact_no' => 'required',
            'address' => 'required',
            'sales_person' => 'required',
            'payment_method' => 'required',
            'commission' => 'required|numeric',
            // 'details' => 'required',

            'payment_terms' => 'required',
            'image' => 'mimes:jpeg,png,jpg,pdf|max:2048',
            'password' => 'required',


            'bank_name' => 'required',
            'account_title' => 'required',
            'iban' => 'required',
            'bic_swift' => 'required',

        ];


        if($request->email != $user->email){
            $validations_rule['email'] = 'required|email|unique:users';
            $user->email = $request->email;
        }else{
            $validations_rule['email'] = 'required|email';
        }

        if($request->password != ""){
            $validations_rule['password'] = 'required';
            $user->password = Hash::make($request->password);
        }else{
            $validations_rule['password'] = '';
        }

        $validator = Validator::make($request->all(), $validations_rule);


        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');

        $user->contact_no = $request->input('contact_no');
        $user->role = 3;


        $supplier->company_name = $request->input('company_name');

        // $supplier->agreement = $request->input('agreement');
        // $supplier->credit = $request->input('credit_limit');
        $supplier -> address = $request->input('address');
        $supplier -> sales_person = $request->input('sales_person');
        $supplier -> payment_method = $request->input('payment_method');
        $supplier -> commission = $request->input('commission');
        $supplier -> payment_terms = $request->input('payment_terms');
        // $supplier -> bank_details = $request->input('bank_details');
        $image = $request->file('image');

        if (!empty($image)) {
            $image = $request->file('image');
            $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('storage/assets/suppliers/');
            $img = Image::make($image->getRealPath());
            $img->save($destinationPath.$input['imagename']);
            $img_path = public_path('storage/assets/suppliers/').$supplier->image;
            File::delete($img_path);
            $supplier -> image = $input['imagename'];
        }

        $bank =  $user->bank_details;
        if($bank == null)
        {
            $bank = new BankDetail();
            $bank->user_id = $user->id;
        }
        $bank->bank_name = $request->bank_name;
        $bank->account_title = $request->account_title;
        $bank->iban = $request->iban;
        $bank->bic_swift = $request->bic_swift;

        DB::beginTransaction();

        try {
                $user->save();
                $supplier->save();
                $bank->save();

            DB::commit();

            return back()->with('success', 'Supplier Information Updated');

            // all good
        } catch (\Exception $e) {

        DB::rollback();

        throw $e;
        // something went wrong
        } catch (\Throwable $e) {
        DB::rollback();

        throw $e;
        }

        return back()->with('error', 'Supplier not saved! Try Again');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::where("user_id", $id)->first();
        $user = User::find($id);
        $api = API::where("supplier_id",$id)->first();

        if (!is_null($supplier)) {
            $img_path = public_path('storage/assets/suppliers/').$supplier->image;
            File::delete($img_path);

            $supplier->delete();

            if ($user->delete()) {

                if(!is_null($api)){
                    $api->delete();
                }
                return back()
                ->with('success', 'Supplier Deleted');
            } else {
                return back()
                ->with('error', 'Something went wrong! Please try again');
            }
        } else {
            abort(403, 'Supplier Not Found.');
        }
    }

    public function bulkDestroy(Request $request)
    {

        $count = 0;

            foreach($request->seleted_id as $id){
                 $api = API::where("supplier_id",$id)->first();

                $user = User::find($id);
                $supplier = Supplier::where('user_id',$id);
                $img_path = public_path('storage/assets/suppliers/').$supplier->image;
                File::delete($img_path);

                $user->delete();
                $supplier->delete();
                if(!is_null($api)){

                    $api->delete();

                }

                $count++;
            }

            return back()->with("success",$count. Str::plural(' record',$count).' deleted');
    }


    public function search(Request $request){


        $by_supplier_name = $request->by_supplier_name;
        $by_supplier_email = $request->by_supplier_email;
        $by_supplier_no = $request->by_supplier_no;


        $users = DB::table('users')
        ->join('suppliers', 'users.id', '=', 'suppliers.user_id');


         if(!empty($by_supplier_name)){
                //return $by_supplier_name;
                $keywords = [];
                $keywords[] = '%'.$by_supplier_name.'%';

                $users = $users->whereRaw("CONCAT_WS(' ',`users`.`first_name`,`users`.`last_name`) LIKE ?", $keywords);
        }

         if(!empty($by_supplier_email)){

            $users = $users->where("users.email",  $by_supplier_email);

         }

         if(!empty($by_supplier_no)){

            $users = $users->where("suppliers.contact_no",  $by_supplier_no);
         }

         $users = $users->orderBy('users.id', 'DESC')->paginate(10);

        session()->flashInput($request->input());
        return view('pages/suppliers/manage', compact('users'));
    }

    public function blockSupplier(Request $request){

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

        return back()->with("warning","Supplier Blocked");
    }

    public function unblockSupplier(Request $request){

        $user = User::find($request->user_id);
        $user->block_type = Null;
        $user->block_until = Null;

        $user->save();
        return back()->with("success","Supplier Unblocked");
    }

    public function saleStatus(Request $request){


       $supplier = Supplier::where("user_id",auth()->user()->id)->first();

       if(!is_null($supplier)){

        if($request->sale_status == "sale_on"){

            $supplier->sale_status = 0;
            $supplier->save();

            return response()->json([
                'status' => true,
                "message" => "Supplier sales on"
            ]);

        }else if($request->sale_status == "sale_off"){
            $supplier->sale_status = 1;
            $supplier->save();
            return response()->json([
                'status' => true,
                "message" => "Supplier sales off"
            ]);

        }else{
            return response()->json([
                'status' => false,
                "message" => "Unknown Status"
            ]);
        }

       }else{
        return response()->json([
            'status' => false,
            "message" => "Supplier not found"
        ]);
       }

       return response()->json([
        'status' => false,
        "message" => "Unknown Error"
    ]);

    }
}
