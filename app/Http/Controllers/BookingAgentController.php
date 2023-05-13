<?php

namespace App\Http\Controllers;

use App\Models\BankDetail;
use App\Models\BookingAgent;
use App\Models\PricingScheme;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class BookingAgentController extends Controller
{
    public function __construct()
    {
        //create permission
        $this->middleware('permission:booking-agent-create')->only(['create', 'store']);

        //view permission
        $this->middleware('permission:booking-agent-view')->only('show');

        //update permission
        $this->middleware('permission:booking-agent-edit')->only(['edit', 'update']);

        //delete permission
        $this->middleware('permission:booking-agent-delete')->only('destroy');
    }

    public function index()
    {
        $users = DB::table('users')
            ->join('booking_agents', 'users.id', '=', 'booking_agents.user_id')
            ->orderBy('users.id', 'DESC')
            ->paginate(10);

        return view('pages.booking-agent.manage', compact('users'));
    }

    public function create()
    {
        return view('pages.booking-agent.create');
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
            'contact_no' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'address' => 'required',
            'agreement' => 'mimes:jpeg,png,jpg,pdf|max:2048',
            'contact_person' => 'required',
            'payment_method' => 'required',
            'payment_terms' => 'required',
            'bank_name' => 'required',
            'account_title' => 'required',
            'iban' => 'required',
            'bic_swift' => 'required',
            'image' => 'mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            // on validator found any error
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $bookingAgent = new BookingAgent();
        $user = new User();
        $bank = new BankDetail();

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->contact_no = $request->input('contact_no');
        $user->role = 6;
        $user->password = Hash::make($request->input('password'));

        $bookingAgent->company_name = $request->input('company_name');
        // $supplier->agreement = $request->file('agreement');
        if (!is_null($request->file('agreement'))) {
            $agreement = $request->file('agreement');
            $input['agreement'] = time() . '.' . $agreement->getClientOriginalExtension();
            $path = $request->file('agreement')->storeAs('booking-agent-agreements', $input['agreement']);
            $bookingAgent->agreement = $input['agreement'];
        }
        // $supplier->credit = $request->input('credit_limit');
        $bookingAgent->address = $request->input('address');
        $bookingAgent->contact_person = $request->input('contact_person');
        $bookingAgent->payment_method = $request->input('payment_method');
        // $supplier->commission = $request->input('commission');
        // $supplier->details = $request->input('details');
        // $supplier->bank_details = $request->input('bank_details');
        $bookingAgent->payment_terms = $request->input('payment_terms');


        $image = $request->file('image');

        if (!empty($image)) {
            $image = $request->file('image');
            $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('storage/assets/booking-agent/');
            $img = Image::make($image->getRealPath());
            $img->resize(640, 480, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.$input['imagename']);

            $bookingAgent->profile_image = $input['imagename'];

//            $image->move($destinationPath, $input['imagename']); // uploaded data!!!
        }

        $bank->bank_name = $request->bank_name;
        $bank->account_title = $request->account_title;
        $bank->iban = $request->iban;
        $bank->bic_swift = $request->bic_swift;

        DB::beginTransaction();

        try {
        // DB::transaction(function () use ($user, $bookingAgent, $bank) {


                $user->save();
                $bookingAgent->user_id = $user->id;
                $bank->user_id = $user->id;
                $bookingAgent->save();
                $bank->save();
                $user->assignRole('booking-agent');


            DB::commit();

            return back()->with('success', 'Booking Agent Saved');
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

        return back()->with('error', 'Booking Agent Not Saved! Try Again');

        // asign supplier role
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $pricing_list = PricingScheme::where('booking_agent_id', $user->id)->paginate(20);


        return view('pages.booking-agent.view', compact('user','pricing_list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);

        if(isset($_GET['download']) && $_GET['download'] == "agreement"){

            return $user->bookingAgent->getAgreementDownload();
        }

        return view('pages.booking-agent.edit', compact('user'));
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

        $user = User::findOrFail($id);
        $validations_rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            'contact_no' => 'required',
            'address' => 'required',
            'agreement' => 'mimes:jpeg,png,jpg,pdf|max:2048',
            'contact_person' => 'required',
            'payment_method' => 'required',
            'payment_terms' => 'required',
            'bank_name' => 'required',
            'account_title' => 'required',
            'iban' => 'required',
            'bic_swift' => 'required',
            'agent_image' => 'mimes:jpeg,png,jpg,pdf|max:2048',
        ];

        if($request->email != $user->email){
            $validations_rules['email'] = 'required|email|unique:users';
            $user->email = $request->email;
        }else{
            $validations_rules['email'] = 'required|email';
        }

        if($request->password != ""){
            $validations_rules['password'] = 'required';
            $user->password = Hash::make($request->password);
        }else{
            $validations_rules['password'] = '';
        }

        $validator = Validator::make($request->all(), $validations_rules);

        if ($validator->fails()) {
            // on validator found any error
            return back()
                ->withErrors($validator)
                ->withInput();
        }


        $bookingAgent = $user->bookingAgent;
        $bank =  $user->bank_details;

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->contact_no = $request->input('contact_no');

        $bookingAgent->company_name = $request->input('company_name');
        // $supplier->agreement = $request->file('agreement');
        if (!is_null($request->file('agreement'))) {
            $agreement = $request->file('agreement');
            $input['agreement'] = time() . '.' . $agreement->getClientOriginalExtension();
            $path = $request->file('agreement')->storeAs('booking-agent-agreements', $input['agreement']);
            $bookingAgent->agreement = $input['agreement'];
        }
        // $supplier->credit = $request->input('credit_limit');
        $bookingAgent->address = $request->input('address');
        $bookingAgent->contact_person = $request->input('contact_person');
        $bookingAgent->payment_method = $request->input('payment_method');
        // $supplier->commission = $request->input('commission');
        // $supplier->details = $request->input('details');
        // $supplier->bank_details = $request->input('bank_details');
        $bookingAgent->payment_terms = $request->input('payment_terms');

        $image = $request->file('agent_image');

        if (!empty($image)) {
            $image = $request->file('agent_image');
            $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('storage/assets/booking-agent/');
            $img = Image::make($image->getRealPath());
            $img->resize(640, 480, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.$input['imagename']);

            $img_path = public_path('storage/assets/booking-agent/').$bookingAgent->profile_image;
            File::delete($img_path);

            $bookingAgent->profile_image = $input['imagename'];

//            $image->move($destinationPath, $input['imagename']); // this is user uploaded data!!! TODO: remove

        }

//         if (!is_null($request->file('agent_image'))) {
//             $image = $request->file('agent_image');
//             $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
//             $path = $request->file('agent_image')->storeAs('public/storage/assets/booking-agent', $input['imagename']);
//             $bookingAgent->profile_image = $input['imagename'];
//         }

        $bank->bank_name = $request->bank_name;
        $bank->account_title = $request->account_title;
        $bank->iban = $request->iban;
        $bank->bic_swift = $request->bic_swift;

        DB::beginTransaction();

        try {
                $user->save();
                $bookingAgent->save();
                $bank->save();

            DB::commit();

            return back()->with('success', 'Booking Agent Information Updated');

            // all good
        } catch (\Exception $e) {

        DB::rollback();

        throw $e;
        // something went wrong
        } catch (\Throwable $e) {
        DB::rollback();

        throw $e;
        }

        return back()->with('error', 'Booking Agent Not Saved! Try Again');
    }


    public function blockBookingAgent(Request $request){

        $validations = [
            'block_type' => 'required'
        ];
        if($request->block_type=="temp"){
            $validations['block_till'] = 'required';
        }
        $this->validate($request, $validations);

        $user = User::findOrFail($request->user_id);
        $user->block_type = $request->block_type;
        $user->block_until = $request->block_till;

        $user->save();

        return back()->with("warning","Booking Agent Blocked");
    }

    public function unblockBookingAgent(Request $request){

        $user = User::findOrFail($request->user_id);
        $user->block_type = Null;
        $user->block_until = Null;

        $user->save();
        return back()->with("success","Booking Agent Unblocked");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);


        $bookingAgent = $user->bookingAgent;
        $bank = $user->bank_details;

        if (!is_null($bookingAgent)) {

            $bookingAgent->delete();

            if ($user->delete()) {

                if(!is_null($bank)){
                    $bank->delete();
                }

                return back()
                ->with('success', 'Booking Agent Deleted');
            } else {
                return back()
                ->with('error', 'Something went wrong! Please try again');
            }
        } else {
            abort(403, 'Booking Agent Not Found.');
        }
    }

    public function profilePage()
    {
        $user = Auth()->user();
        return view("auth.booking-agent.pages.profile", compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        $agent_data  = $user->bookingAgent;
        $validations = [
            'first_name' => 'required|min:2|max:50',
            'last_name' => 'required|min:3|max:50',
            'address' => 'required|min:3',
            'contact_no' => 'required|min:3',
            'agent_image' => 'mimes:jpeg,png,jpg,pdf|max:2048',

        ];

        if ($request->email == $user->email) {
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
        $agent_data->address = $request->address;

        $image = $request->file('agent_image');

        if (!empty($image)) {
            $image = $request->file('agent_image');
            $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('storage/assets/booking-agent/');
            $img = Image::make($image->getRealPath());
            $img->resize(640, 480, function ($constraint) {$constraint->aspectRatio();})->save($destinationPath.$input['imagename']);

            $img_path = public_path('storage/assets/booking-agent/').$agent_data->profile_image;
            File::delete($img_path);

            $agent_data->profile_image = $input['imagename'];

            //             $image->move($destinationPath, $input['imagename']);  // this is user uploaded data!!! TODO: remove

        }

//         if (!is_null($request->file('agent_image'))) {
//             $image = $request->file('agent_image');
//             $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
//             $path = $request->file('agent_image')->storeAs('public/storage/assets/booking-agent', $input['imagename']);
//             $agent_data->profile_image = $input['imagename'];
//         }

        // dd($agent_data);
        $agent_data->save();
        $user->save();

        return back()->with('success', 'Profile Updated');
    }
}
