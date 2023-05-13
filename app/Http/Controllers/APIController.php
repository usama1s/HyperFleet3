<?php

namespace App\Http\Controllers;

use App;
use URL;
use Redirect;
use Braintree;
use App\Driver;
use App\Voucher;
use App\Models\API;

use App\Models\User;


use PayPal\Api\Item;
use PayPal\Api\Payer;
use App\Jobs\EmailJob;



use App\Mail\TestMail;

use PayPal\Api\Amount;
use App\Models\Booking;
use App\Models\Vehicle;

/** All Paypal Details class **/

use PayPal\Api\Details;
use PayPal\Api\Payment;
use App\Models\Customer;
use App\Models\Supplier;
use PayPal\Api\ItemList;

use App\Models\BookingLogs;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use App\Models\VehicleClass;
use Illuminate\Http\Request;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use App\Models\Booking_invoice;


use PayPal\Api\PaymentExecution;
use App\Events\BookingStatusEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Config;
use App\Models\Payment as PaymentModal;
use Illuminate\Support\Facades\Session;
use App\Notifications\CustomNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class APIController extends Controller
{

    private $_api_context;
    private $brainTreeGateway;

    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret']
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

        //Brain Tree


        $this->brainTreeGateway = new Braintree\Gateway([
            'environment' => env('sandbox'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);
    }

    public function index()
    {

        //$key = DB::table('apis')->join('suppliers','suppliers.api_id','=','apis.id')->where("suppliers.user_id",Auth::user()->id)->first();
        $login_user = Auth::user();

        if ($login_user->role == 3) {

            $key = API::where("supplier_id", $login_user->id)->first();

            if (is_null($key)) {
                $key = null;
            } else {
                $key = $key->key;
            }

            return view('pages/apis/keys', compact('key'));
        } else if ($login_user->role == 1) {

            $keys = DB::table('apis')
                ->join('users', 'users.id', '=', 'apis.supplier_id')
                ->get();


            return view('pages/apis/keys', compact('keys'));
            // return abort(403);
        } else {
            return abort(403);
        }
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
        $login_user = Auth::user();

        //$supplier = Supplier::where("user_id",$login_user->id)->first();
        $api = new API;

        $api->key = uniqid();
        $api->supplier_id = $login_user->id;

        $api->save();

        //$supplier->api_id = $api->id;

        //  $supplier->save();

        return back()->with('success', "your key has been generated");
    }
    public function apiEmbed()
    {

        // $login_user = Auth::user();

        // $apikey = API::where("key",$key)->first();

        // if(!is_null($apikey)){


        // if($apikey->key == $key){

        $sessions_list = [
            'booking_type',
            'pickup_point',
            'drop_off',
            'hourlybooking',
            'pickup_date',
            'pickup_time',
            'voucher_code',
            'no_of_passengers',
            'no_of_bags',
            'image',
            'vehicle_id',
            'v_class',
            'price',
        ];

        Session::forget($sessions_list);

        //  Session::put("ref_id",$apikey->supplier_id);

        return view("pages.apis.bookingframe");
        // }
        // else{
        // abort(403,"key doesnt exist");
        // }
        // }else{
        // abort(403,"supplier doesnt exist");
        // }

    }

    public function chooseVehicle(Request $request)
    {

        $pickup = explode(",", $request->pickup_cords);
        $pickup['lat'] = $pickup[0];
        $pickup['lng'] = $pickup[1];


        if (!is_null($request->dropoff_cords)) {


            $dropoff = explode(",", $request->dropoff_cords);

            $dropoff['lat'] = $dropoff[0];
            $dropoff['lng'] = $dropoff[1];
        } else {

            $dropoff = null;
        }

        $hourlybooking = $request->hourlybooking;
        $userDistance = $request->userdistance;
        $no_of_passengers = $request->no_of_passengers;

        $vehicleClasses = [];
        $vehicleClasses = VehicleClass::orderBy('id', 'asc')->get();

        //here we want the 5 latest in each category

        //           $vehicleClasses = VehicleClass::all()->map(function ($vehicle_class, $key){
        //               return $vehicle_class;
        //           });


        // $vehicleClasses = $vehicleClasses->toArray();
        //           $cloeset = []; //$vehicleClasses->all(); //->first(); //$vehicleClasses[0]; // [];

        foreach ($vehicleClasses as $vehicle_class) {

            //                if($v->passengers == $no_of_passengers){
            //                   break;
            //             }else{
            //                 if($v->passengers >= $no_of_passengers){
            //                     $cloeset = $v;
            //                     break;
            //                 }
            //              }
            $vehicles = [];
            $vall =   Vehicle::orderBy('id', 'desc')->where('admin_approve', 1)->get();

            // $class = VehicleClass::where('id', $vall->vehicle_class_id)->first();
            // dd($class);
            foreach ($vall as $v) {

                // if(is_null($v->pricing)){
                //   continue;
                //  }

                // if($v->supplier->sale_status == 1){
                //     continue;
                //  }
                $calculated_price = $v->calculatePrice($pickup, $dropoff, $hourlybooking, $userDistance);
                $v->calculated_price = $calculated_price;
                $vehicles[] = $v;
            }

            usort($vehicles, function ($a, $b) {
                return ($a['calculated_price']['price'] < $b['calculated_price']['price']) ? -1 : 1;
            });

            // $perPage = 1;
            // $page = request()->get('page', 1);
            // $offset = ($page - 1) * $perPage;

            // $vehicles = collect($vehicles)->slice($offset, $perPage);
            // $vehicles = new LengthAwarePaginator($vehicles, count($vehicles), $perPage, $page, [
            //     'path' => request()->url(),
            //     'query' => request()->query(),
            // ]);


            //                }
            $validation_array = [

                'pickup_point' => 'required',
                'pickup_date' => 'required',
                'pickup_time' => 'required',
                'no_of_passengers' => 'required|digits_between:0,9',
                'no_of_bags' => 'digits_between:0,9'

            ];
            if ($request->booking_type == "hourly") {
                $validation_array['hourlybooking'] = 'required';
            }

            if ($request->booking_type == "point-2-point") {
                $validation_array['drop_off'] = 'required';
            }

            $validator = Validator::make($request->all(), $validation_array);

            if ($validator->fails()) { // on validator found any error
                return back()->withErrors($validator)->withInput();
            }

            session([
                'pickup_cords' => $request->pickup_cords,
                'dropoff_cords' => $request->dropoff_cords,
                'booking_type' => $request->booking_type,
                'pickup_point' => $request->pickup_point,
                'drop_off' => $request->drop_off,
                'hourlybooking' => $request->hourlybooking,
                'pickup_date' => $request->pickup_date,
                'pickup_time' => $request->pickup_time,
                'voucher_code' => $request->voucher_code,
                'no_of_passengers' => $request->no_of_passengers,
                'no_of_bags' => $request->no_of_bags,
                'user_distance' => $userDistance
            ]);

            // $vehicles = Vehicle::where("driver_id", "!=",NULL)
            // ->where("supplier_id",session('ref_id'))->paginate(10);

            // $vehicleClasses = VehicleClass::paginate(10);


            return view('pages.apis.choosevehicle', compact('vehicles', 'request', 'vehicleClasses', 'vall'));
        }
    } //new last

    public function customerInfo(Request $request)
    {

        if (empty(session('pickup_point'))) {
            return redirect(url('/'));
        }


        $vehicleClass = VehicleClass::find(decrypt($request->vehicle_class_id));

        $vehicles = Vehicle::where('id', $request->id)->first();

        $price = decrypt($request->calculated_price);

        $supplier_id = decrypt($request->supplier_id);

        // if(Voucher::isValid(session('voucher_code'))){
        //     $discounted_price = Voucher::getnewprice(session('voucher_code'),$price);
        //     $calculated_tax = $discounted_price * (Config('vat')/100);
        //     $grand_total = $discounted_price + $calculated_tax;
        // }else{
        //     $calculated_tax = $vehicleClass->price * (Config('vat')/100);
        //     $grand_total = $vehicleClass->price + $calculated_tax;
        // }

        session([
            'image' => $vehicleClass->thumbnail,
            'v_class' => $vehicleClass->id,
            'price' =>  $price,
            'grand_total' =>  $price,
            'ref_id' => $supplier_id,
        ]);

        // $class = VehicleClass::where('id', $vall->vehicle_class_id)->first();
        // dd($class);

        //        $clientToken = $this->brainTreeGateway->clientToken()->generate();  //braintree token removed for testing!!!

        return view('pages.apis.customerinfo', compact('vehicles'));
        //        return view('pages.apis.customerinfo',compact('clientToken'));

    }



    public function bookingSave(Request $request)
    {
        // dd($request->all());
        $validation_array = [

            'lead_first_name' => 'required',
            'lead_email' => 'required|email',
            'lead_lastname' => 'required',
            'lead_mobile' => 'required',
            'payment_method' => 'required',
        ];

        $validator = Validator::make($request->all(), $validation_array);
        
        if ($validator->fails()) { // on validator found any error
            return back()->withErrors($validator)->withInput();
        }
        
        // dd(session()->all());

        // $vehicle_id = session('vehicle_id');

        // $vehicle = Vehicle::find($vehicle_id);
        // $driver = Driver::where("user_id",$vehicle->driver_id)->first();

        $booking = new Booking;

        $booking->pickup_point = session('pickup_point');
        $booking->drop_off = session('drop_off');
        $booking->type = session('booking_type');
        $booking->pickup_date =  date('Y-m-d', strtotime(session('pickup_date')));
        $booking->pickup_time = date("H:i", strtotime(session('pickup_time')));
        $booking->duration = session('hourlybooking');
        $booking->no_of_adults =  session('no_of_passengers');
        $booking->no_of_bags =  session('no_of_bags');
        $booking->v_class = session('v_class');
        $booking->voucher_code = $request->discount_code;
        $booking->supplier_id = session('ref_id');
        $booking->price = session('price');
        $booking->grand_price = session('grand_total');
        // $booking->driver_id = $vehicle->driver_id;

        // create customer if not exist;

        $check_email = Customer::where('email', $request->email)->first();

        
        if (is_null($check_email)) {
            // no customer found with requested email

            $customer = new Customer();
            $customer->first_name = $request->lead_first_name;
            $customer->last_name = $request->lead_lastname;
            $customer->contact_no = $request->lead_mobile;
            $customer->email = $request->lead_email;
            $customer->password = Hash::make("password"); // $request->email;
            $customer->save();
        } else {
            //email found;

            $customer = $check_email;
        }
        // dd($request->payment_country);
        $booking->payment_first_name = $request->payment_first_name;
        $booking->payment_last_name = $request->payment_lastname;
        $booking->payment_company = $request->payment_company;
        $booking->payment_email = $request->payment_email;
        $booking->city = $request->payment_city;
        $booking->payment_postal_code = $request->payment_postal_code;
        $booking->country = $request->payment_country;
        $booking->first_name = $request->lead_first_name;
        $booking->last_name = $request->lead_lastname;
        $booking->cost_center = $request->cost_center;
        $booking->contact_no = $request->lead_mobile;
        $booking->email = $request->lead_email;
        $booking->pickup_sign = $request->lead_meet;
        $booking->special_instructions = $request->special_instruction;
        $booking->payment_method = $request->payment_method;
        $booking->customer_id = $customer->id;

        // $booking->name_on_card= $request->card_holder_name;
        // $booking->debit_card_no= $request->card_number;
        // $booking->expiry_date= date("m-d-y") ;

        // $vehicle->status = "booked";
        $booking->status = "open";
        $booking->source = "Website";
        // $driver->status = "assigned";
        
        switch ($request->payment_method) {
            case "online_payment":
                // dd($request->payment_method_nonce);
                $payment_method_nonce = $request->payment_method_nonce;
                return $this->payWithBrainTree($booking, $payment_method_nonce);

                break;

            case "cash_payment":
                return $this->paymentOnCash($booking);
                break;
            default:
                return back()->with("error", "This Payment Method not supported. Please contact to Admin");
        }
        
    }


    public function paymentOnCash($booking)
    {
        // $driver->save();
        // $vehicle->save();

        if ($booking->save()) {
            $invoice = new Booking_invoice;

            $invoice->booking_id = $booking->id;
            $invoice->status = 'unpaid';
            $invoice->save();

            $log = new BookingLogs;
            // $log->driver_id = $booking->driver_id;
            $log->booking_id = $booking->id;
            $log->log = "add-booking";
            $log->updated_by = 1;
            $log->save();


            $pdf = App::make('dompdf.wrapper');
            $invoice_name = "invoice-" . $invoice->id . '.pdf';
            $pdf->loadView('pages.accounts.invoice.view', compact('booking', 'invoice'))->save('public/assets/invoices/' . $invoice_name)->stream('download.pdf');

            // Mail::to($booking->email)->send(new TestMail($invoice));

            $emailClass = new TestMail($invoice);
            $emailDetails['to'] = $booking->email;
            dispatch(new EmailJob($emailDetails, $emailClass));
        }

        // $driver = User::find($driver->user_id);
        $supplier = User::find($booking->supplier_id);

        $notification = array(
            'subject' => "New Booking",
            'msg' => "you have one new booking from " . $booking->first_name,
            'link' => route('driver.pendings'),
            'type' => 'newbooking',
            "booking_id" => $booking->toArray()
        );

        // $driver->notify(new CustomNotification($notification));

        // $fullDriverInfo = null;
        // $fullDriverInfo = $driver->driver->toArray();

        // $fullDriverInfo['fullname'] = $driver->first_name." ".$driver->last_name;


        // $class_name = VehicleClass::find($vehicle->vehicle_class_id)->name;
        // $vehicle = $vehicle->toArray();
        // $vehicle['class_name'] = $class_name;

        event(new BookingStatusEvent($booking, $supplier));

        return redirect('public/assets/invoices/' . $invoice_name);
    }


    public function payWithBrainTree($booking, $payment_method_nonce)
    {

        $result = $this->brainTreeGateway->transaction()->sale([
            'amount' => $booking->grand_price,
            'paymentMethodNonce' => $payment_method_nonce,
            'customer' => [
                'firstName' => $booking->first_name,
                'lastName' => $booking->last_name,
                'email' => $booking->email,
            ],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        if ($result->success) {

            $transaction = $result->transaction;
            $transaction_id = $transaction->id;
            $currency_code = $transaction->currencyIsoCode;
            $amount = $transaction->amount;
            $payment_type = $transaction->paymentInstrumentType;

            if ($booking->save()) {

                // $driver->save();
                // $vehicle->save();

                $payment_log = new PaymentModal;
                $payment_log->transaction_id = $transaction_id;
                $payment_log->payment_status = "paid";
                $payment_log->payment_type =  $payment_type;
                $payment_log->currency_code = $currency_code;
                $payment_log->supplier_id = $booking->supplier_id;
                $payment_log->booking_id = $booking->id;
                $payment_log->save();

                $invoice = new Booking_invoice;

                $invoice->booking_id = $booking->id;
                $invoice->status = 'paid';
                $invoice->save();

                $log = new BookingLogs;
                // $log->driver_id = $booking->driver_id;
                $log->booking_id = $booking->id;
                $log->log = "add-booking";
                $log->save();

                $pdf = App::make('dompdf.wrapper');
                $invoice_name = "invoice-" . $invoice->id . '.pdf';
                $pdf->loadView('pages.accounts.invoice.view', compact('booking', 'invoice'))->save('public/assets/invoices/' . $invoice_name)->stream('download.pdf');

                Mail::to($booking->email)->send(new TestMail($invoice));
            }

            // $driver = User::find($driver->user_id);
            $supplier = User::find($booking->supplier_id);

            $notification = array(
                'subject' => "New Booking",
                'msg' => "you have one new booking from " . $booking->first_name,
                'link' => route('driver.pendings'),
                'type' => 'newbooking',
                "booking_id" => $booking->toArray()
            );

            // $driver->notify(new CustomNotification($notification));

            // $fullDriverInfo = null;
            // $fullDriverInfo = $driver->driver->toArray();

            // $fullDriverInfo['fullname'] = $driver->first_name." ".$driver->last_name;


            // $class_name = VehicleClass::find($vehicle->vehicle_class_id)->name;
            // $vehicle = $vehicle->toArray();
            // $vehicle['class_name'] = $class_name;

            event(new BookingStatusEvent($booking, $supplier));

            return back()->with("success", "Your Booking#" . $booking->id . " The Transaction ID is:" . $transaction->id);
        } else {

            return back()->with('error', 'An error occurred with the message: ' . $result->message);
        }
    }

    // public function vehiclebookingform(Request $request)
    // {
    //     // dd($request->all());
    //     session([
    //         'tickets' =>$request->tickets,
    //         'child_input_number' => $request->child_input_number,
    //         'booster_input_number' => $request->booster_input_number,
    //         'desciption' => $request->desc,
    //         'lead_first_name' =>$request->lead_first_name,
    //         'lead_lastname' => $request->lead_lastname,
    //         'lead_email' => $request->lead_email,
    //         'lead_mobile' => $request->lead_mobile,
    //         'lead_meet' => $request->lead_meet,
    //         'payment_first_name' => $request->payment_first_name,
    //         'payment_lastname' => $request->payment_lastname,
    //         'payment_company' => $request->payment_company,
    //         'payment_email' => $request->payment_email,
    //         'payment_city' => $request->payment_city,
    //         'payment_postal_code' => $request->payment_postal_code,
    //         'meet' => $request->meet,
    //     ]);

    //     $booking = new Booking;

    //     $booking->pickup_point = session('pickup_point');
    //     $booking->drop_off = session('drop_off');
    //     $booking->type = session('booking_type');
    //     $booking->pickup_date =  session('pickup_date');
    //     $booking->pickup_time = date("H:i", strtotime(session('pickup_time')));
    //     $booking->duration = session('hourlybooking');
    //     $booking->no_of_adults =  session('no_of_passengers');
    //     $booking->no_of_bags =  session('no_of_bags');
    //     $booking->v_class = session('v_class');
    //     $booking->voucher_code = session('voucher_code');
    //     $booking->supplier_id = session('ref_id');
    //     $booking->price = session('price');
    //     $booking->grand_price = session('grand_total');

    //     $booking->tickets = $request->tickets;
    //     $booking->child_input_number = $request->child_input_number;
    //     $booking->booster_input_number = $request->booster_input_number;
    //     $booking->description = $request->chauffer_desc;

    //     $booking->first_name = $request->lead_first_name;
    //     $booking->lastname = $request->lead_lastname;
    //     $booking->email = $request->lead_email;
    //     $booking->contact_no = $request->contact_no;
    //     $booking->lead_meet = $request->lead_meet;
    //     $booking->payment_first_name = $request->payment_first_name;
    //     $booking->payment_last_name = $request->payment_lastname;
    //     $booking->payment_company = $request->payment_company;
    //     $booking->payment_email = $request->payment_email;
    //     $booking->city = $request->payment_city;
    //     $booking->payment_postal_code = $request->payment_postal_code;
    //     $booking->country = $request->payment_country;

    //     $booking->payment_method = $request->payment_method;
    //     $booking->cost_center= $request->cost_center;
    //     $booking->contact_no= $request->contact_no;
    //     $booking->pickup_sign= $request->pickup_sign;
    //     $booking->special_instructions= $request->special_instruction;

    //     $booking->save();

    //     return back();

    // }




}
