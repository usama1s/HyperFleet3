<?php

namespace App\Http\Controllers;

use App\Models\Payment as PaymentModal;

use App\Models\User;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\BookingLogs;
use App\Models\Booking_invoice;
use App\Models\Vehicle;
use App\Models\VehicleClass;
use App\Models\Voucher;

use App\Notifications\CustomNotification;
use App\Events\BookingStatusEvent;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;

/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;
use App;

use Braintree;

class PaymentController extends Controller
{

    private $_api_context;
    private $brainTreeGateway;

    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
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

    public function index(){
        $payments =  PaymentModal::latest();

        if(auth()->user()->role ==1){
            $payments = $payments->get();
        }elseif(auth()->user()->role ==3){

            $payments = $payments->where('supplier_id',auth()->user()->id)->get();
        }
        return view('pages.accounts.payment.manage',compact('payments'));
    }

    public function supplierReceive(Request $request){
       
        $currency = Config('currency');
        $user = User::find($request->supplier_id);
        
        $sup_credit =  (float) $user->supplier->credit;

        $user->supplier->credit =  $sup_credit - $request->payment;
        $user->supplier->save();

        $payment_log = new PaymentModal;
        $payment_log->transaction_id = uniqid("HYP-");
        $payment_log->payment_status = "paid";
        $payment_log->payment_type = "cash";
        $payment_log->currency_code =  $currency;
        $payment_log->supplier_id = $user->id;
        $payment_log->save();

        return back()->with("success","Amount Receive");
    }

    public function supplierSendPayment(Request $request){
        $amount = $request->payment;
        $nonce = $request->payment_method_nonce;

    
         $result = $this->brainTreeGateway->transaction()->sale([
            'amount' => $amount,
            'paymentMethodNonce' => $nonce,
            'customer' => [
                'firstName' => auth()->user()->first_name,
                'lastName' => auth()->user()->last_name,
                'email' => auth()->user()->email,
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

            $user = User::find($request->supplier_id);
            $user->supplier->credit = $user->supplier->credit - $amount;
            $user->supplier->save();

            $payment_log = new PaymentModal;
            $payment_log->transaction_id = $transaction_id;
            $payment_log->payment_status = "paid";
            $payment_log->payment_type =  $payment_type;
            $payment_log->currency_code = $currency_code;
            $payment_log->supplier_id = $user->id;
            $payment_log->save();

            return back()->with('success', 'Transaction successful. The ID is:'. $transaction->id);
        } else {

 
            // $_SESSION["errors"] = $errorString;
            // header("Location: index.php");
            return back()->with('error','An error occurred with the message: '.$result->message);
        }
    }

    public function destroy(PaymentModal $payment){

        
        if(!is_null($payment)){
           
            $payment->delete();

            return back()
            ->with('success', 'Payment Deleted');
        }else{
            abort(403, 'Payment Not Found.');
        }
    }

    public function bulkDestroy(Request $request){
        //bulk Destroy

        $count = 0;
        foreach($request->seleted_id as $payment){
            $payment = PaymentModal::find($payment);
           
            $payment->delete();
            $count++;
           
        }

        return back()->with("success",$count. Str::plural(' record',$count).' deleted');

    }



    public function getPaymentStatus(Request $request)
    {

     
       
        $payment_id = $request->paymentId;
      
       
        
        if (empty($request->PayerID) || empty($request->token)) {
          return back()->with("error","Payment Failed. Please Try Again");
        }else{

            $paymentModel =  new PaymentModal;

          
            $paymentModel->payment_id = $payment_id;
            $paymentModel->currency_code = "USD";
            $paymentModel->payment_status = "Paid";
            $paymentModel->payment_type = "Paypal";

            $paymentModel->save();

        }

        try {
            $payment = Payment::get($payment_id, $this->_api_context);
                
            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);
    
            /**Execute the payment **/
            $result = $payment->execute($execution, $this->_api_context);
            
            $transaction_id =  $result->transactions[0]->related_resources[0]->order->id;
            
            if ($result->getState() == 'approved') {
    
                $booking =  Session::get("confirm_booking");
                $driver =  Session::get("confirm_driver");
                $vehicle =  Session::get("confirm_vehicle");
    
                if($booking->save()){
                    $invoice =new Booking_invoice;
        
                    $invoice->booking_id = $booking->id;
                    $invoice->status = 'paid';
                    $invoice->save();
        
                    $log =new BookingLogs;
                    $log->driver_id = $booking->driver_id;
                    $log->booking_id = $booking->id;
                    $log->log = "add-booking";
                    $log->save();
        
                    $pdf = App::make('dompdf.wrapper');
                    $invoice_name = "invoice-".$invoice->id.'.pdf';
                    $pdf->loadView('pages.accounts.invoice.view',compact('booking','invoice'))->save('public/invoices/'.$invoice_name)->stream('download.pdf');
        
                    Mail::to($booking->email)->send(new TestMail($invoice));
        
                }
                $driver->save();
                $vehicle->save();
    
                $paymentModel->booking_id = $booking->id;
                $paymentModel->transaction_id = $transaction_id;
                $paymentModel->save();
    
                Session::forget('confirm_booking');
                Session::forget('confirm_driver');
                Session::forget('confirm_vehicle');
    
                
                $driver = User::find($driver->user_id);
                $supplier = User::find($booking->supplier_id);
                
                $notification = array(
                    'subject' => "New Booking",
                    'msg' => "you have one new booking from ".$booking->first_name,
                    'link' => route('driver.pendings'),
                    'type' => 'newbooking',
                    "booking_id" => $booking->toArray()
                );
        
                $driver->notify(new CustomNotification($notification));
        
                $fullDriverInfo = null;
                $fullDriverInfo = $driver->driver->toArray();
        
                $fullDriverInfo['fullname'] = $driver->first_name." ".$driver->last_name;
        
             
                $class_name = VehicleClass::find($vehicle->vehicle_class_id)->name;
                $vehicle = $vehicle->toArray();
                $vehicle['class_name'] = $class_name;
               
                
        
                event(new BookingStatusEvent($booking,$fullDriverInfo,$vehicle,$supplier)); 
                
                return back()->with("success","Your Booking#".$booking->id);
            }
            } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
                                 
                return back()->with("error","Internet Problem");
                
            }

       
        
       
        return back()->with("error","Payment Failed. Please Try Again");
    }
}
