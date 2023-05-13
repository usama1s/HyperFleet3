<?php

namespace App\Http\Controllers;

use Braintree;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Staff;
use App\Jobs\EmailJob;
use App\Mail\TestMail;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Models\Customer;
use App\Models\Payables;
use App\Models\Supplier;
use App\Models\BookingLogs;

use Illuminate\Support\Str;
use App\Models\VehicleClass;
use Illuminate\Http\Request;
use App\Models\PricingScheme;

use App\Models\Booking_invoice;
use App\Events\BookingStatusEvent;
use Illuminate\Support\Facades\DB;
use App\Models\BookingBalanceSheet;
use Illuminate\Support\Facades\App;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Models\Payment as PaymentModal;
use App\Notifications\CustomNotification;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    private $brainTreeGateway;

    public function __construct()
    {
        //create permission
        $this->middleware('permission:booking-create')->only(['create', 'store']);

        //view permission
        $this->middleware('permission:booking-view')->only('show');

        //update permission
        $this->middleware('permission:booking-edit')->only(['edit', 'update']);

        //delete permission
        $this->middleware('permission:booking-delete')->only('destroy');

        // Brain tree config

        if (empty(env('BRAINTREE_ENV'))) {
            return 0;
        }

        $this->brainTreeGateway = new Braintree\Gateway([
            'environment' => 'sandbox',
            'merchantId' => 'your_merchant_id',
            'publicKey' => 'your_public_key',
            'privateKey' => 'your_private_key'
        ]);
    }

    public function index()
    {
        $login_user = Auth()->user();

        if ($login_user->role == 3) {
            $bookings = DB::table('users')
                ->join('bookings', 'users.id', '=', 'bookings.supplier_id')
                ->where('bookings.supplier_id', $login_user->id);
                
        } elseif ($login_user->role == 2) {
            $staff = Staff::where('user_id', $login_user->id)->first();
            $bookings = DB::table('users')
                ->join('bookings', 'users.id', '=', 'bookings.supplier_id')
                ->where('bookings.supplier_id', $staff->supplier_id);
               
        } else {
        $bookings = Booking::query();
        }

        $bookings = $bookings->where('status','!=','market-offer')
        ->orderBy('bookings.id', 'DESC')->paginate(100);

        // $bookings = Booking::latest()->paginate(10);

        if($login_user->role == 1){

            return view('pages.bookings.manage-for-admin', compact('bookings'));
        }else{
            return view('pages.bookings.manage-for-supplier', compact('bookings'));
        }
    }

    public function marketOffers()
    {
        $login_user = Auth()->user();

       
        $bookings = Booking::query();
        $bookings = $bookings->where('status','market-offer')
        ->orderBy('bookings.pickup_date', 'ASC')->paginate(10);

        // $bookings = Booking::latest()->paginate(10);
        return view('pages.bookings.market-offer.manage', compact('bookings'));
    }

    public function marketOffersCapture(Request $request){

        $login_user = Auth()->user();

        $supplier_id = null;
        $data['status'] = false;
        $data['message'] = "No request Found";

        if(is_null($request->booking_id)){
            $data['status'] = false;
            $data['message'] = "Booking ID not found";
           
        }

        if ($login_user->role == 3) {
           
            $supplier_id = $login_user->id;
                
        } elseif ($login_user->role == 2) {
            $staff = Staff::where('user_id', $login_user->id)->first();
            $supplier_id =  $staff->supplier_id;
               
        } 

        if(is_null($supplier_id)){
            $data['status'] = false;
            $data['message'] = "Supplier not found";
           
        }else{

            $booking = Booking::where('id',$request->booking_id)->first();

            if(is_null($booking)){

                $data['status'] = false;
                $data['message'] = "No Booking found against your booking ID";

            }

            // return $booking->first();

            if(is_null($booking->supplier_id)){
                $booking->supplier_id =  $supplier_id;
                $booking->status =  'open';
                $booking->save();

                $data['status'] = true;
                $data['message'] = "Your have Capture this booking";

            }else{

                $data['status'] = false;
                $data['message'] = "Already Capture this booking";

            }

        }

        return response()->json($data);
    }

    public function todayBooking()
    {
        $login_user = Auth()->user();

        if ($login_user->role == 3) {
            $bookings = DB::table('users')
                ->join('bookings', 'users.id', '=', 'bookings.supplier_id')
                ->where('bookings.supplier_id', $login_user->id)
                ->where('pickup_date', Carbon::now()->format('Y-m-d'))
                ->orderBy('pickup_time', 'ASC')
                ->paginate(10);
        } elseif ($login_user->role == 2) {
            $staff = Staff::where('user_id', $login_user->id)->first();
            $bookings = DB::table('users')
                ->join('bookings', 'users.id', '=', 'bookings.supplier_id')
                ->where('bookings.supplier_id', $staff->supplier_id)
                ->where('pickup_date', Carbon::now()->format('Y-m-d'))
                ->orderBy('pickup_time', 'ASC')
                ->paginate(10);
        } else {
            $bookings = Booking::orderby('pickup_time', 'ASC')
//             ->where('pickup_date', Carbon::now()->format('Y-m-d'))// date('Y-m-d'))
->paginate(100);
        }

        // $bookings = Booking::latest()->paginate(10);
        return view('pages.todayBookings', compact('bookings'));
    }
    
    
    public function tomorrowBooking()
    {
        $login_user = Auth()->user();
        
        if ($login_user->role == 3) {
            $bookings = DB::table('users')
            ->join('bookings', 'users.id', '=', 'bookings.supplier_id')
            ->where('bookings.supplier_id', $login_user->id)
            ->where('pickup_date', Carbon::tomorrow()->format('Y-m-d'))
            ->orderBy('pickup_time', 'ASC')
            ->paginate(10);
        } elseif ($login_user->role == 2) {
            $staff = Staff::where('user_id', $login_user->id)->first();
            $bookings = DB::table('users')
            ->join('bookings', 'users.id', '=', 'bookings.supplier_id')
            ->where('bookings.supplier_id', $staff->supplier_id)
            ->where('pickup_date', Carbon::tomorrow()->format('Y-m-d'))
            ->orderBy('pickup_time', 'ASC')
            ->paginate(10);
        } else {
            $bookings = Booking::orderby('pickup_time', 'ASC')
            ->where('pickup_date', Carbon::tomorrow()->format('Y-m-d'))// date('Y-m-d'))
            ->paginate(100);
        }
        
        // $bookings = Booking::latest()->paginate(10);
        return view('pages.bookings.bookings-list', compact('bookings'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientToken = '';
        if ($this->brainTreeGateway) {
            $clientToken = $this->brainTreeGateway->clientToken()->generate();
        }

        $user = Auth()->user();
        if ($user->role == 3) {
            $pricings = PricingScheme::where('supplier_id', $user->id)
                ->orderBy('id', 'DESC')
                ->get();
        } elseif ($user->role == 2) {
            $staff = Staff::where('user_id', $user->id)->first();
            $pricings = PricingScheme::where('supplier_id', $staff->supplier_id)
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            $pricings = PricingScheme::where('supplier_id', null)
                ->orderBy('id', 'DESC')
                ->get();
        }

        return view('pages.bookings.create', compact('clientToken', 'pricings'));
    }

    public function bookingAgentCreateBooking()
    {
        $clientToken = '';
        if ($this->brainTreeGateway) {
            $clientToken = $this->brainTreeGateway->clientToken()->generate();
        }

        $user = Auth()->user();
      
            $pricings = PricingScheme::where('booking_agent_id',  $user->id)
                ->orderBy('id', 'DESC')
                ->get();
        

        return view('auth.booking-agent.pages.booking.create',compact('clientToken','pricings'));
    }

    public function bookingAgentStoreBooking(Request $request)
    {
        
        $driver = null;
        $vehicle = null;

        $validation_array = [
            'pickup_point' => 'required',
            'pickup_date' => 'required',
            'pickup_time' => 'required',
            'no_of_adults' => 'digits_between:0,9',
            'no_of_bags' => 'digits_between:0,9',
            'vehicle_class' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_no' => 'required',
            'email' => 'required|email',
            'price' => 'required|numeric',
            'payment_method' => 'required|in:cash',
        ];

        if ($request->booking_type == 'hourly') {
            $validation_array['hourlybooking'] = 'required';
        }

        if ($request->booking_type == 'point-2-point') {
            $validation_array['drop_off'] = 'required';
        }

        $validator = Validator::make($request->all(), $validation_array);

        if ($validator->fails()) {
            // on validator found any error
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // create customer if not exist;

        $check_email = Customer::where('email', $request->email)->first();
        if (is_null($check_email)) {
            // no customer found with requested email

            $customer = new Customer();
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->contact_no = $request->contact_no;
            $customer->email = $request->email;
            $customer->password = Hash::make("password");// $request->email;
            $customer->save();


        } else {
            //email found;

            $customer = $check_email;
        }

        // create booking

        $booking = new Booking();
        $booking->pickup_point = $request->pickup_point;
        $booking->drop_off = $request->drop_off;
        $booking->type = $request->booking_type;
        $booking->pickup_date = $request->pickup_date;
        $booking->pickup_time = date('H:i', strtotime($request->pickup_time));
        $booking->duration = $request->hourlybooking;
        $booking->no_of_adults = $request->no_of_adults;
        $booking->no_of_bags = $request->no_of_bags;
        $booking->flightnumber = $request->flightnumber;
        $booking->voucher_code = $request->voucher_code;
        $booking->price = $request->price;
        $booking->grand_price = $request->grand_total;
        $booking->service_type = $request->service_type;
        $booking->v_class = $request->vehicle_class;

        $booking->first_name = $request->first_name;
        $booking->last_name = $request->last_name;
        $booking->email = $request->email;
        $booking->contact_no = $request->contact_no;
        $booking->pickup_sign = $request->pickup_sign;
        $booking->special_instructions = $request->special_instructions;
        $booking->payment_method = $request->payment_method;

        $loginUser = Auth()->user();
        

      
            $booking->supplier_id = null;
            $booking->driver_id = null;
            $booking->vehicle_id = null;
            $booking->agent_id = $loginUser->id;
            $booking->status = 'market-offer';
            $booking->source = "Booking Agent";

                

        if (!is_null($customer)) {
            $booking->customer_id = $customer->id;
        } else {
            $booking->customer_id = null;
        }

        switch ($booking->payment_method) {
            // case 'credit_card':
            //     $payment_method_nonce = $request->payment_method_nonce;
            //     return $this->paywithcreditcard($booking, $payment_method_nonce, $driver, $vehicle);

            //     break;

            case 'cash':
                return $this->paymentOnCash($booking, $driver, $vehicle);
                break;
            default:
                return back()->with('error', 'This Payment Method is not supported. Please contact support.');
        }
    }


    public function bookingAgentIndex(){
        $login_user = Auth()->user();

        
            $bookings = DB::table('users')
                ->join('bookings', 'users.id', '=', 'bookings.agent_id')
                ->where('bookings.agent_id', $login_user->id)
                ->orderBy('bookings.pickup_date', 'DESC')
                ->paginate(10);
       

        // $bookings = Booking::latest()->paginate(10);
        return view('auth.booking-agent.pages.booking.manage', compact('bookings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $driver = null;
        $vehicle = null;

        $validation_array = [
            'pickup_point' => 'required',
            'pickup_date' => 'required',
            'pickup_time' => 'required',
            'no_of_adults' => 'digits_between:0,9',
            'no_of_bags' => 'digits_between:0,9',
            'vehicle_class' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_no' => 'required',
            'email' => 'required|email',
            'price' => 'required|numeric',
            'payment_method' => 'required',
        ];

        if ($request->booking_type == 'hourly') {
            $validation_array['hourlybooking'] = 'required';
        }

        if ($request->booking_type == 'point-2-point') {
            $validation_array['drop_off'] = 'required';
        }

        $validator = Validator::make($request->all(), $validation_array);

        if ($validator->fails()) {
            // on validator found any error
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // create customer if not exist;

        $check_email = Customer::where('email', $request->email)->first();

        if (is_null($check_email)) {
            // no customer found with requested email

            $customer = new Customer();
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->contact_no = $request->contact_no;
            $customer->email = $request->email;
            $customer->password = Hash::make("password");// $request->email;

            $customer->save();
        } else {
            //email found;

            $customer = $check_email;
        }

        // create booking

        $booking = new Booking();
        $booking->pickup_point = $request->pickup_point;
        $booking->drop_off = $request->drop_off;
        $booking->type = $request->booking_type;
        $booking->pickup_date = $request->pickup_date;
        $booking->pickup_time = date('H:i', strtotime($request->pickup_time));
        $booking->duration = $request->hourlybooking;
        $booking->no_of_adults = $request->no_of_adults;
        $booking->no_of_bags = $request->no_of_bags;
        $booking->flightnumber = $request->flightnumber;
        $booking->voucher_code = $request->voucher_code;
        $booking->price = $request->price;
        $booking->grand_price = $request->grand_total;
        $booking->service_type = $request->service_type;
        $booking->v_class = $request->vehicle_class;
       
        $booking->first_name = $request->first_name;
        $booking->last_name = $request->last_name;
        $booking->email = $request->email;
        $booking->contact_no = $request->contact_no;
        $booking->pickup_sign = $request->pickup_sign;
        $booking->special_instructions = $request->special_instructions;
        $booking->payment_method = $request->payment_method;

        $loginUser = Auth()->user();
        if ($loginUser->role == 3) {
            //for supplier
            $booking->supplier_id = $loginUser->id;
            $booking->source = "Supplier";
        }
        if ($loginUser->role == 2) {
            //for staff
            $loginStaff = Staff::where('user_id', $loginUser->id)->first();
            $booking->supplier_id = $loginStaff->supplier_id;
            $booking->source = "Supplier";
        }

        if ($loginUser->role == 1) {
            //for admin          
            $booking->source = "Admin";
            if (is_null($request->supplier_name)) {
                $booking->status = 'market-offer';
        }
        }

        if (!is_null($request->supplier_name)) {
            $booking->supplier_id = $request->supplier_name;
            $booking->driver_id = null;
            $booking->vehicle_id = null;
        } else {
            if (!is_null($request->driver) && $request->driver != 'null') {
                $booking->status = 'ready';
                $booking->driver_id = $request->driver;

                $driver = Driver::where('user_id', $booking->driver_id)->first();
                $vehicle = Vehicle::find($driver->vehicle_id);

                if (!is_null($vehicle)) {
                    $booking->vehicle_id = $vehicle->id;
                }

                $driver->status = 'assigned';
                $vehicle->status = 'booked';
            }
        }

        if (!is_null($customer)) {
            $booking->customer_id = $customer->id;
        } else {
            $booking->customer_id = null;
        }

        switch ($booking->payment_method) {
            case 'credit_card':
                $payment_method_nonce = $request->payment_method_nonce;
                return $this->paywithcreditcard($booking, $payment_method_nonce, $driver, $vehicle);

                break;

            case 'cash':
                return $this->paymentOnCash($booking, $driver, $vehicle);
                break;
            default:
                return back()->with('error', 'This Payment Method is not supported. Please contact support.');
        }
    }

    public function paywithcreditcard($booking, $payment_method_nonce, $driver, $vehicle)
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
                'submitForSettlement' => true,
            ],
        ]);

        if ($result->success) {
            $transaction = $result->transaction;
            $transaction_id = $transaction->id;
            $currency_code = $transaction->currencyIsoCode;
            $amount = $transaction->amount;
            $payment_type = $transaction->paymentInstrumentType;

            if ($booking->save()) {
                if (!is_null($driver)) {
                    $driver->save();
                }

                if (!is_null($vehicle)) {
                    $vehicle->save();
                }

                $payment_log = new PaymentModal();
                $payment_log->transaction_id = $transaction_id;
                $payment_log->payment_status = 'paid';
                $payment_log->payment_type = $payment_type;
                $payment_log->currency_code = $currency_code;
                $payment_log->supplier_id = $booking->supplier_id;
                $payment_log->booking_id = $booking->id;
                $payment_log->save();

                $invoice = new Booking_invoice();

                $invoice->booking_id = $booking->id;
                $invoice->status = 'paid';
                $invoice->save();

                $log = new BookingLogs();
                $log->driver_id = $booking->driver_id;
                $log->booking_id = $booking->id;
                $log->log = 'add-booking';
                $log->updated_by = Auth::user()->id;
                $log->save();

                $pdf = App::make('dompdf.wrapper');
                $invoice_name = 'invoice-' . $invoice->id . '.pdf';
                $pdf->loadView('pages.accounts.invoice.view', compact('booking', 'invoice'))
                    ->save('public/assets/invoices/' . $invoice_name)
                    ->stream('download.pdf');

                Mail::to($booking->email)->send(new TestMail($invoice));
            }
            $driver = User::find($driver->user_id);
            $supplier = User::find($booking->supplier_id);

            $notification = [
                'subject' => 'New Booking',
                'msg' => 'you have one new booking from ' . $booking->first_name,
                'link' => route('driver.pendings'),
                'type' => 'newbooking',
                'booking_id' => $booking->toArray(),
            ];

            $driver->notify(new CustomNotification($notification));

            $fullDriverInfo = null;
            $fullDriverInfo = $driver->driver->toArray();

            $fullDriverInfo['fullname'] = $driver->first_name . ' ' . $driver->last_name;

            $class_name = VehicleClass::find($vehicle->vehicle_class_id)->name;
            $vehicle = $vehicle->toArray();
            $vehicle['class_name'] = $class_name;

            event(new BookingStatusEvent($booking, $fullDriverInfo, $vehicle, $supplier));

            return redirect(route('booking-invoice.show', $invoice->id));
        } else {
            return back()->with('error', 'An error occurred with the message: ' . $result->message);
        }
    }

    public function paymentOnCash($booking, $driver, $vehicle)
    {
        $fullDriverInfo = null;

        if ($booking->save()) {
            if (!is_null($driver)) {
                $driver->save();

                $driver = User::find($driver->user_id);

                $fullDriverInfo = $driver->driver->toArray();

                $fullDriverInfo['fullname'] = $driver->first_name . ' ' . $driver->last_name;

                $notification = [
                    'subject' => 'New Booking',
                    'msg' => 'you have one new booking from ' . $booking->first_name,
                    'link' => route('driver.pendings'),
                    'type' => 'newbooking',
                    'booking_id' => $booking->toArray(),
                ];

                $driver->notify(new CustomNotification($notification));
            }

            if (!is_null($vehicle)) {
                $vehicle->save();

                $class_name = VehicleClass::find($vehicle->vehicle_class_id)->name;
                $vehicle = $vehicle->toArray();
                $vehicle['class_name'] = $class_name;
            }

            if (!is_null($booking->supplier_id)) {
                $supplier = User::find($booking->supplier_id);
            } else {
                $supplier = null;
            }

            $invoice = new Booking_invoice();

            $invoice->booking_id = $booking->id;
            $invoice->status = 'unpaid';
            $invoice->save();

            $log = new BookingLogs();
            $log->driver_id = $booking->driver_id;
            $log->booking_id = $booking->id;
            $log->log = 'booking created';
            $log->updated_by = Auth::user()->id;
            $log->save();

            $pdf = App::make('dompdf.wrapper');
            $invoice_name = 'invoice-' . $invoice->id . '.pdf';
            $pdf->loadView('pages.accounts.invoice.view', compact('booking', 'invoice'))
                ->save('public/assets/invoices/' . $invoice_name)
                ->stream('download.pdf');

           $emailClass = new TestMail($invoice);
           $emailDetails['to'] = $booking->email;
           dispatch(new EmailJob($emailDetails,$emailClass));
           
        }

        event(new BookingStatusEvent($booking, $fullDriverInfo, $vehicle, $supplier));
        return redirect(route('booking-invoice.show', $invoice->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function assignBookingToSupplier($booking_id)
    {
        $users = User::whereRole('3')->get();
        // return $users->supplier->company_name;
        return view('pages.bookings.assignBookingToSupplier', compact('users', 'booking_id'));
    }

    public function removeBookingToSupplier($booking_id)
    {
        $booking = Booking::find($booking_id);

        $booking->supplier_id = null;
        $booking->save();

        return back()->with('warning', 'booking removed from supplier');
    }

    public function assignBookingToSupplierSave(Request $request)
    {
        $booking = Booking::find($request->booking_id);
        $booking->supplier_id = $request->supplier_id;

        $booking->save();

        return redirect('bookings');
    }

    public function assignDriverToBooking($booking_id)
    {
        $booking = Booking::find($booking_id);

        $drivers = DB::table('drivers')
            ->join('users', 'drivers.user_id', '=', 'users.id')
            
            ->where('drivers.supplier_id', $booking->supplier_id)->get();

        $vehicles = DB::table('vehicles')
            
            ->where('vehicles.supplier_id', $booking->supplier_id)->get();        

        return view('pages.bookings.assignDriverToBooking', compact('drivers', 'booking_id','vehicles'));
    }

    public function removeDriverToBooking($booking_id)
    {
        $booking = Booking::find($booking_id);

        $booking->driver_id = null;
        $booking->vehicle_id = null;
        $booking->status = 'open';
        $booking->save();

        return back()->with('warning', 'booking removed from driver');
    }
    public function assignDriverToBookingSave(Request $request)
    {       
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'vehicle_id' => 'required',
        //    'vehicle_id' => 'required',
        ]);
        if ($validator->fails()) {
            // on validator found any error
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $booking = Booking::find($request->booking_id);
      
        $driver = Driver::where('user_id', $request->driver_id)->firstOrFail();
        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        $booking->driver_id = $request->driver_id;
        $booking->vehicle_id = $request->vehicle_id;

        $vehicle->status = 'assigned';
        $booking->status = 'ready';
        $driver->status = 'assigned';

        $booking->supplier_id = $driver->supplier_id;

        $vehicle->save();
        $booking->save();
        $driver->save();

        $user = User::find($driver->user_id);

        $notification = [
            'subject' => 'New Booking',
            'msg' => 'you have one new booking from ' . $booking->first_name,
            'link' => route('driver.pendings'),
            'type' => 'newbooking',
            'booking_id' => $booking,
        ];

        $user->notify(new CustomNotification($notification));

        return redirect(session()->get('booking_ref_page'));
    }

    public function show(Booking $booking)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        return view('pages.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validation_array = [
            'pickup_point' => 'required',
            'pickup_date' => 'required',
            'pickup_time' => 'required',
            'vehicle_class' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'price' => 'required|numeric',
            'contact_no' => 'required',
            'email' => 'required|email',
        ];

        if ($request->booking_type == 'hourly') {
            $validation_array['hourlybooking'] = 'required';
        }

        if ($request->booking_type == 'point-2-point') {
            $validation_array['drop_off'] = 'required';
        }

        $validator = Validator::make($request->all(), $validation_array);

        if ($validator->fails()) {
            // on validator found any error
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        
        
        $log = new BookingLogs();
        $log->driver_id = $booking->driver_id;
        $log->booking_id = $booking->id;
        $log->log = 'booking edited';
        $log->updated_by = Auth::user()->id;
        $log->save();

        $booking = Booking::find($booking->id);

        $booking->pickup_point = $request->pickup_point;

        $booking->drop_off = $request->drop_off;
        $booking->type = $request->booking_type;
        $booking->pickup_date = $request->pickup_date;
        $booking->pickup_time = date('H:i', strtotime($request->pickup_time));
        $booking->duration = $request->hourlybooking;
        $booking->no_of_adults = $request->no_of_adults;
        $booking->no_of_bags = $request->no_of_bags;
        $booking->flightnumber = $request->flightnumber;
        $booking->voucher_code = $request->voucher_code;
        $booking->grand_price = $request->price;
        $booking->service_type = $request->service_type;
        $booking->v_class = $request->vehicle_class;
        $booking->first_name = $request->first_name;
        $booking->last_name = $request->last_name;
        $booking->email = $request->email;
        $booking->contact_no = $request->contact_no;
        $booking->pickup_sign = $request->pickup_sign;
        $booking->special_instructions = $request->special_instructions;
        // $booking->payment_method = $request->payment_method;
        // $booking->name_on_card = $request->name_on_card;
        // $booking->debit_card_no = $request->debit_card_no;
        // $booking->expiry_date = $request->expiry_date;

        if ($booking->status == 'expired') {
            if ($booking->driver_id == null) {
                $booking->status = 'open';
            } else {
                $booking->status = 'ready';
            }
        }

        $booking->save();

        return redirect('/bookings');
    }

    public function destroy(Booking $booking)
    {
        if (!is_null($booking)) {
            $invoice = Booking_invoice::where('booking_id', $booking->id)->first();

            $logs = BookingLogs::where('booking_id', $booking->id)->get();

            foreach ($logs as $log) {
                $log->delete();
            }

            $booking->delete();

            if (!is_null($invoice)) {
                $path = public_path('assets/invoices/') . 'invoice-' . $invoice->id . '.pdf';
                File::delete($path);
                $invoice->delete();
            }

            return back()->with('success', 'Booking Deleted');
        } else {
            abort(403, 'Booking Not Found.');
        }
    }

    public function bulkDestroy(Request $request)
    {
        $count = 0;
        foreach ($request->seleted_id as $id) {
            $booking = Booking::find($id);

            $invoice = Booking_invoice::where('booking_id', $booking->id)->first();

            $logs = BookingLogs::where('booking_id', $booking->id)->get();

            foreach ($logs as $log) {
                $log->delete();
            }

            $booking->delete();

            if (!is_null($invoice)) {
                $path = public_path('assets/invoices/') . 'invoice-' . $invoice->id . '.pdf';
                File::delete($path);
                $invoice->delete();
            }

            $count++;
        }
        return back()->with('success', $count . Str::plural(' record', $count) . ' deleted');
    }

    public function pendingbookings(Booking $bookings)
    {
        $bookings = Booking::where('driver_id', Auth::user()->id)
            ->where('status', 'ready')
            ->get();

        return view('auth.driver.pages.pendingbookings', compact('bookings'));
    }

    public function bookingaction(Request $request)
    {
        $booking = Booking::find($request->booking_id);

        $driver = Driver::where('user_id', $booking->driver_id)->first();

        $vehicle = Vehicle::find($driver->vehicle_id);

        if (!is_null($request->accept)) {
            $booking->status = 'accepted';

            if ($vehicle->status != 'booked') {
                if ($driver->status != 'booked') {
                    $driver->status = 'assigned';
                    $vehicle->status = 'assigned';

                    $driver->save();
                    $vehicle->save();
                }
            }

            $booking->save();

            $log = new BookingLogs();
            $log->driver_id = $booking->driver_id;
            $log->booking_id = $booking->id;
            $log->log = 'Booking accepted';
            $log->updated_by = Auth()->user()->id;
            $log->save();

            event(new BookingStatusEvent($booking));

            return back()->with('success', 'You have accepted this Booking');
        }

        if (!is_null($request->reject)) {
            $booking->status = 'open';
            $booking->driver_id = null;

            $driver->status = 'available';
            $vehicle->status = 'available';

            $driver->save();
            $vehicle->save();
            $booking->save();

            $log = new BookingLogs();
            $log->driver_id = $driver->user_id;
            $log->booking_id = $booking->id;
            $log->log = 'Booking rejected';
            $log->updated_by = Auth()->user()->id;
            $log->save();

            event(new BookingStatusEvent($booking));

            return back()->with('warning', 'You have Rejected this Booking');
        }
    }

    public function canceledbookings()
    {
        $bookings = DB::table('bookings')
            ->join('booking_logs', 'bookings.id', '=', 'booking_logs.booking_id')
            ->where('booking_logs.driver_id', Auth::user()->id)
            ->get();

        return view('auth.driver.pages.canceledbookings', compact('bookings'));
    }

    public function acceptedbookings()
    {
        $bookings = Booking::where('driver_id', Auth::user()->id)
            ->whereIn('bookings.status', ['client', 'accepted', 'active'])
            ->get();

        return view('auth.driver.pages.acceptedbookings', compact('bookings'));
    }

    public function startride($id)
    {
        $active_booking = Booking::where('driver_id', Auth::user()->id)
            ->whereIn('bookings.status', ['active', 'client'])
            ->first();

        if (!is_null($active_booking)) {
            return back()->with('error', 'You have to complete your active Ride');
        }

        // $pickup_client = Booking::where('driver_id', Auth::user()->id)->
        // where('bookings.status', 'client')->
        // first();
        // if (!is_null($pickup_client)) {
        //     return back()->with("toast-error", "You have to complete your active Ride");
        // }

        $booking = Booking::where('driver_id', Auth::user()->id)
            ->where('bookings.status', 'accepted')
            ->where('id', $id)
            ->first();

        $driver = Driver::where('user_id', $booking->driver_id)->first();
        $vehicle = Vehicle::find($driver->vehicle_id);

        if ($booking->pickup_date == date('Y-m-d')) {
            //todays Booking

            $booking->status = 'active';

            $driver->status = 'booked';
            $vehicle->status = 'booked';

            $driver->save();
            $vehicle->save();

            $booking->save();

            $log = new BookingLogs();
            $log->driver_id = $booking->driver_id;
            $log->booking_id = $booking->id;
            $log->log = 'Transfer started';
            $log->updated_by = Auth()->user()->id;
            $log->save();

            event(new BookingStatusEvent($booking));

            return back()->with('success', 'Your ride has started.');
        } elseif ($booking->pickup_date < date('Y-m-d')) {
            // not todays booking

            return back()->with('error', 'Expire: You have missed the booking, Pickup Date was ' . $booking->pickup_date);
        } else {
            return back()->with('error', 'Booking starts on ' . $booking->pickup_date);
        }
    }

    public function noshow($id)
    {
        $booking = Booking::where('driver_id', Auth::user()->id)
            ->where('bookings.status', 'active')
            ->where('id', $id)
            ->first();

        $driver = Driver::where('user_id', $booking->driver_id)->first();
        $vehicle = Vehicle::find($driver->vehicle_id);

        $booking->status = 'noshow';

        $booking->save();

        $all_bookings = Booking::where('driver_id', Auth::user()->id)
            ->whereNotIn('status', ['finish', 'noshow', 'expired'])
            ->count();

        if ($all_bookings <= 0) {
            $driver->status = 'available';
            $vehicle->status = 'available';
            $driver->save();
            $vehicle->save();
        } else {
            $driver->status = 'assigned';
            $vehicle->status = 'assigned';
            $driver->save();
            $vehicle->save();
        }

        $log = new BookingLogs();
        $log->driver_id = $booking->driver_id;
        $log->booking_id = $booking->id;
        $log->log = 'Client no-show';
        $log->updated_by = Auth()->user()->id;
        $log->save();

        event(new BookingStatusEvent($booking));

        return back()->with('warning', 'Booking updated to Client no-show');
    }

    public function pickupclient($id)
    {
        $active_booking = Booking::where('driver_id', Auth::user()->id)
            ->where('bookings.status', 'client')
            ->first();
        if (!is_null($active_booking)) {
            return back()->with('error', 'You have to complete your active Ride');
        }

        $booking = Booking::where('driver_id', Auth::user()->id)
            ->where('bookings.status', 'active')
            ->where('id', $id)
            ->first();

        $booking->status = 'client';

        $booking->save();

        $log = new BookingLogs();
        $log->driver_id = $booking->driver_id;
        $log->booking_id = $booking->id;
        $log->log = 'Client picked up';
        $log->updated_by = Auth()->user()->id;
        $log->save();

        event(new BookingStatusEvent($booking));
        return back()->with('success', 'You have picked up the Client.');
    }

    public function finishride(Request $request, $id)
    {
        $amount = $request->amount;
        $booking = Booking::where('driver_id', Auth::user()->id)
            ->where('id', $id)
            ->where('bookings.status', 'client')
            ->first();

        if ($amount < Voucher::getnewprice($booking->voucher_code, $booking->price)) {
            return back()->with('error', 'Please collect full amount from client');
        }

        $booking->status = 'finish';
        $booking->save();

        $driver = Driver::where('user_id', $booking->driver_id)->first();

        $vehicle = Vehicle::find($driver->vehicle_id);

        $all_bookings = Booking::where('driver_id', Auth::user()->id)
            ->whereNotIn('status', ['finish', 'noshow', 'expired'])
            ->count();

        if ($all_bookings <= 0) {
            $driver->status = 'available';
            $vehicle->status = 'available';
            $driver->save();
            $vehicle->save();
        } else {
            $driver->status = 'assigned';
            $vehicle->status = 'assigned';
            $driver->save();
            $vehicle->save();
        }

        $invoice = Booking_invoice::where('booking_id', $booking->id)->first();
        $invoice->status = 'paid';

        $invoice->save();

        $log = new BookingLogs();
        $log->driver_id = $booking->driver_id;
        $log->booking_id = $booking->id;
        $log->log = 'Transfer completed';
        $log->updated_by = Auth()->user()->id;
        $log->save();

        $admin = Admin::where('user_id', '1')->first();

        if ($booking->payment_method == 'cash') {
            $driver->credit += $booking->grand_price;
            $balance_sheet = new BookingBalanceSheet();

            $balance_sheet->booking_id = $booking->id;
            $balance_sheet->driver_id = $driver->user_id;
            $balance_sheet->supplier_id = null;
            $balance_sheet->credit = $booking->grand_price;
            $balance_sheet->debit = $booking->grand_price;
            $balance_sheet->save();
        } else {
            $admin->credit += $booking->grand_price;

            $balance_sheet = new BookingBalanceSheet();

            $balance_sheet->booking_id = $booking->id;
            $balance_sheet->driver_id = $driver->user_id;
            $balance_sheet->supplier_id = null;
            $balance_sheet->credit = $booking->grand_price;
            $balance_sheet->debit = 0;
            $balance_sheet->save();
        }

        $driver->save();
        $admin->save();

        // if(!is_null($booking->supplier_id)){
        //     $supplier = Supplier::where("user_id",$booking->supplier_id)->first();

        //     if(!is_null($supplier)){

        // if($driver->payment_type == "commission"){

        //     $driver_commission = $driver->amount;
        //     $driver_due = $driver->credit;
        // }else{
        //     $driver_commission = 0;
        //     $driver_due =0;
        // }

        // $supplier_commission = $supplier->commission;
        // $supplier_due = $supplier->credit;

        // $earn_by_supplier = $booking->grand_price;  //

        // $supplier_profit = $earn_by_supplier * ($supplier_commission / 100);

        // $admin_profit = $earn_by_supplier - $supplier_profit;

        // $driver_profit =  $supplier_profit * ($driver_commission / 100);

        //         if($booking->payment_method == "cash"){
        //             $driver->credit +=  $booking->grand_price;
        //         }else{
        //             $admin->credit += $booking->grand_price;
        //         }

        //         $supplier->save();
        //         $driver->save();
        //         $admin->save();
        //     }

        // }else{

        //     if($booking->payment_method == "cash"){
        //         $driver->credit +=  $booking->grand_price;
        //     }else{
        //         $admin->credit += $booking->grand_price;
        //     }

        //    if($driver->payment_type == "commission"){
        //         $commission = $driver->amount;
        //         $driver_due = $driver->credit;
        //         $earn_by_admin = $booking->grand_price;

        //         $driver_profit = $earn_by_admin * ($commission / 100);
        //         $admin_profit = $earn_by_admin - $driver_profit;

        //         $driver->credit += $driver_profit;
        //         $driver->save();

        //    }else{
        //         $admin_profit = $booking->grand_price;
        //    }

        //     $admin->credit += $admin_profit;
        //     $admin->save();
        //     $driver->save();

        // }

        event(new BookingStatusEvent($booking));

        return back()->with('success', 'You have Completed your ride.');
    }

    public function completedbookings()
    {
        $bookings = Booking::where('bookings.status', 'finish')
            ->where('bookings.driver_id', Auth::user()->id)
            ->get();

        return view('auth.driver.pages.completedbookings', compact('bookings'));
    }

    public function todaybookings()
    {
        $todaydate = date('Y-m-d');

        $bookings = Booking::whereNotIn('bookings.status', ['ready', 'finish'])
            ->where('bookings.driver_id', Auth::user()->id)
            ->where('pickup_date', $todaydate)
            ->get();

        return view('auth.driver.pages.todaybookings', compact('bookings'));
    }

    public function search(Request $request)
    {
        $by_date = $request->by_date;
        $by_vehicle_class = $request->by_vehicle_class;
        $by_drives = $request->by_drives;
        $by_suppliers = $request->by_suppliers;
        $booking_status = $request->booking_status;

        $dates = explode(',', $by_date);

        $count = count($dates);

        $bookings = DB::table('bookings');

        $user = Auth()->user();

        if ($user->role == 3) {
            $bookings = $bookings->where('supplier_id', $user->id);
        } elseif ($user->role == 2) {
            $staff = Staff::where('user_id', $user->id)->first();
            $bookings = $bookings->where('supplier_id', $staff->supplier_id);
        } else {
        }

        if (!empty($by_date)) {
            if ($count == 1) {
                $bookings = $bookings->where('bookings.pickup_date', $by_date);
            } elseif ($count > 1) {
                $bookings = $bookings->whereIn('bookings.pickup_date', $dates);
            }
        }

        if (!empty($by_vehicle_class)) {
            $bookings = $bookings->where('bookings.v_class', $by_vehicle_class);
        }

        if (!empty($by_drives)) {
            $bookings = $bookings->where('bookings.driver_id', $by_drives);
        }

        if (!empty($by_suppliers)) {
            $bookings = $bookings->where('bookings.supplier_id', $by_suppliers);
        }

        if (!empty($booking_status)) {
            $bookings = $bookings->where('bookings.status', $booking_status);
        }

        $bookings = $bookings->paginate(20);

        session()->flashInput($request->input());
        // return view('pages/bookings/manage', compact('bookings'));
        if($user->role == 1){

            return view('pages.bookings.manage-for-admin', compact('bookings'));
        }else{
            return view('pages.bookings.manage-for-supplier', compact('bookings'));
        }
    }

    private function isNowBetweenTimes($pickupTime, $startDateTime, $endDateTime)
    {
        $curTimeLocal = $pickupTime;
        $startTime = $curTimeLocal->copy();
        $startTime->hour = $startDateTime->hour;
        $startTime->minute = $startDateTime->minute;
        $endTime = $curTimeLocal->copy();
        $endTime->hour = $endDateTime->hour;
        $endTime->minute = $endDateTime->minute;
        if ($endTime->lessThan($startTime)) {
            $endTime->addDay();
        }

        return $curTimeLocal->isBetween($startTime, $endTime);
    }
}
