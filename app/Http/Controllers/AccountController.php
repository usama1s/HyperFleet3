<?php

namespace App\Http\Controllers;

use App\Models\DriverSalary;
use App\Http\Controllers\Controller;
use App\Notifications\CustomNotification;
use Illuminate\Http\Request;
use App\Models\User;
use Braintree;
class AccountController extends Controller
{

    private $brainTreeGateway;

     public function __construct(){

         $this->brainTreeGateway = new Braintree\Gateway([
             'environment' => env('sandbox'),
             'merchantId' => env('BRAINTREE_MERCHANT_ID'),
             'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
             'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);
        
     }

    public function supplierBooking(){
        
        return view("pages.accounts.payable.suppliers");
    }

    public function supplierbyIdBooking($id){
        
        $clientToken = $this->brainTreeGateway->clientToken()->generate();
        return view("pages.accounts.payable.suppliers-by-id",compact('id','clientToken'));
    }

    public function driversBookings(){
        return view("pages.accounts.payable.drivers"); 
    }

    public function  driverbyIdBooking($id){
        $clientToken = $this->brainTreeGateway->clientToken()->generate();

        $user = User::find($id);

        if($user->driver->payment_type =="fixed"){

            return view('pages.accounts.payable.drivers-salary-by-id',compact('user','clientToken'));
        }else{
            // return view('pages.accounts.payable.drivers-by-id',compact('user','clientToken'));
            return view('pages.accounts.payable.commission-base-single-payable',compact('user','clientToken'));
        }
    }

    public function driverSalaryPaid(DriverSalary $salary){
        $salary->status = "paid";
        $salary->save();
        
        $user = User::find($salary->driver_id);

        $notification = array(
            'subject' => "Salary Received",
            'msg' => "You have received your salary of ".date('M, Y', strtotime($salary->salary_date)),
            'link' => route('driver'),
            'type' => 'newsalary'
        );

        $user->notify(new CustomNotification($notification));

        return back()->with("success","Salary Paid To Driver");
    }
}
