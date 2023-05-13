<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Customer;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();

        return view("pages.customer.manage",compact("customers"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("pages.customer.register");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $customer = new Customer();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:customers,email',
            'contact_no' => 'required|numeric',
            'password' => 'required|min:6|confirmed',

        ]);

        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }

        // dd($request->all());
                
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->contact_no = $request->contact_no;
        $customer->password = Hash::make($request->password);
        $customer->save();

        
        $loginref = session('login_ref_url');

        Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password]);

        if(!is_null($loginref)){
            session()->forget('login_ref_url');
            return redirect($loginref);    
        }
        else{
            return redirect('/me');
        }
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }


    public function trips()
    {
        $time = date("H:i:s"); 

        $today_date = date("Y-m-d");

         $upcoming_bookings = Booking::where("pickup_date", '>=' ,$today_date)->where("pickup_time", '>' ,$time)->get();

         $past_bookings = Booking::where("pickup_date", '<' ,$today_date)->get();

    //   $bookings = Booking::all();
    //   $past = []; 

    //   foreach($bookings as $b){
    //     foreach($upcoming_bookings as $up){
           
    //         if($up->id != $b->id)
    //         {
    //             $past[]= $b;
    //         }
    //     }
    //   }

    //   return $past;

        return view("pages.customer.customer_trips",compact("upcoming_bookings","past_bookings"));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        return view("pages.customer.customer_profile",compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validations = [
            'first_name' => 'required|min:3|max:50',
            'last_name' => 'required|min:3|max:50',
            'contact_no' => 'required',
        ];

        if ($request->email == $user->email) {
            $validations['email'] = 'email';
        } else {
            $validations['email'] = 'email|unique:users';
        }

        if (!is_null($request->pre_password)) {

            if(!Hash::check($request->pre_password, $user->password)) {

                return back()->withInput()->withErrors(
                    [
                        'pre_password' => 'Wrong Password',
                    ],
                );

            }
            
        }
       
        if (!is_null($request->pre_password)) {
            [
                $validations['password'] = 'required|confirmed|min:6',
                $validations['pre_password']     = 'required'
            ];
        }
        
        $messages = [
            'password.required' => 'New password field is required.',
            'pre_password.required' => 'Old password field is required.'
        ];

        $this->validate($request, $validations,$messages);
        
        if(!is_null($request->password)){
            $user->password = Hash::make($request->password);
        }

        $user->email = $request->email;
        
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;

        $user->save();

        return back()->with("success","profile updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $count = 0;
        
            foreach($request->seleted_id as $id){
               
                $user = User::find($id);

                $user->delete();

                $count++;
            }

            return back()->with("success",$count. Str::plural(' record',$count).' deleted');
    }

    public function customerBooking(Customer $customer){

         $bookings = Booking::where('customer_id',$customer->id)->paginate(10);

         $name = $customer->fullName();
         return view('pages.customer.customer_booking',compact('bookings','name'));
        
    
    }

    public function blockCustomer(Request $request){

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

        return back()->with("warning","Customer Blocked");
    }

    public function unblockCustomer($id){

        
        $user = User::find($id);
        $user->block_type = Null;
        $user->block_until = Null;

        $user->save();
        return back()->with("success","Customer Unblocked");
    }
}
