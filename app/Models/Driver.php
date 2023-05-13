<?php

namespace App\Models;


use App\Models\Vehicle;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

    function salaries(){
        return $this->hasMany('App\Models\DriverSalary','driver_id',"user_id");
    }

    function balancesheet(){
        return $this->hasMany('App\Models\BookingBalanceSheet','driver_id',"user_id");
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class,'user_id','supplier_id');
    }

    public function dueSalary(){
        $sum= 0;
        foreach($this->salaries as $salary){
            if($salary->status == "unpaid"){

                $sum = $sum + $salary->salary;
            }
        }

        return $sum;
    }

    public function fullName($id){
         $user = User::find($id);
            if(!is_null($user)){
                return $user->first_name. " ". $user->last_name;
            }else{
                return null;
            }

    }

    public function supplierName($id){
         $user = DB::table('drivers')
        ->join('suppliers','drivers.supplier_id','=','suppliers.user_id')
        ->join('users','drivers.supplier_id','=','users.id')
        ->where('drivers.user_id',$id)->first();


           if(!is_null($user)){
               return $user->first_name. " ". $user->last_name;
           }else{
               return null;
           }

   }

    public function withVehicle($driver_id,$prop){

        if(!is_null($driver_id)){

            $vehicle = Vehicle::where("driver_id",$driver_id)->first();
        }
        else{
            $vehicle = null;
        }

        if(!is_null($vehicle)){
            return $vehicle[$prop];
        }else{
            return "No Vehicle";
        }

    }

    public function BookingRejected($driver_id,$booking_id){

        $rejected =  BookingLogs::where("driver_id",$driver_id)->where("booking_id",$booking_id)->where("log","reject")->first();
        if(is_null($rejected)){
            return false;
        }else{
            return true;
        }
    }

    public function driverdata($id)
    {
            $user = User::find($id);

        return array(
            "contact_no"=>$user->contact_no,
            "email"=>$user->email,
            "image"=>$user->driver->driver_image,

        );
    }
}
