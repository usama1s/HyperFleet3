<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\User;
use App\Models\Staff;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;


class Report extends Model
{
    //

    public static function getTodayBookingsCount(){
        $login_user = Auth()->user();

        if($login_user->role == 3){

            $bookings = DB::table('users')
            ->join('bookings', 'users.id', '=', 'bookings.supplier_id')
            ->where("bookings.supplier_id",$login_user->id)
            ->where('pickup_date', Carbon::now()->format('Y-m-d'))
            ->orderBy('pickup_time', 'ASC')
            ->get();

        }else if($login_user->role == 2){
           $staff = Staff::where("user_id",$login_user->id)->first();
           $bookings = DB::table('users')
           ->join('bookings', 'users.id', '=', 'bookings.supplier_id')
           ->where("bookings.supplier_id",$staff->supplier_id)->where('pickup_date', Carbon::now()->format('Y-m-d'))->orderBy('pickup_time', 'ASC')->get();
        }else{

            $bookings = Booking::orderby("pickup_time","ASC")->where('pickup_date', Carbon::now()->format('Y-m-d'))->get();

        }

        return count($bookings);
    }

    public static function getTotalBookingsCount(){
        if(auth()->user()->role == 1){
            $bookings = Booking::all();
       }elseif(auth()->user()->role == 3){
           $bookings =  Booking::where("supplier_id",auth()->user()->id)->get();
       }else{
           $bookings = [];
       }

       return count($bookings);
    }

    public static function getTotalDriverCount(){
        if(auth()->user()->role == 1){
            $driver = Driver::all();
       }elseif(auth()->user()->role == 3){
           $driver = Driver::where("supplier_id",auth()->user()->id)->get();
       }else{
           $driver = [];
       }

       return count($driver);
    }

    public static function getTotalVehicleCount(){
        if(auth()->user()->role == 1){
            $vehicle = Vehicle::all();
       }elseif(auth()->user()->role == 3){
           $vehicle = Vehicle::where("supplier_id",auth()->user()->id)->get();
       }else{
           $vehicle = [];
       }

       return count($vehicle);
    }

    public static function getAvailableVehicleCount(){
        if(auth()->user()->role == 1){
            $vehicle = Vehicle::where('status', "available")->get(); // all();
        }elseif(auth()->user()->role == 3){
            $vehicle = Vehicle::where("supplier_id",auth()->user()->id)
            ->where('status', "available")->get();
        }else{
            $vehicle = [];
        }

        return count($vehicle);
    }

    public static function getTotalSupplierCount(){
        if(auth()->user()->role == 1){
            $supplier = Supplier::all();
       }else{
        $supplier = [];
       }

       return count($supplier);
    }
    public static function MonthlyReport_BarChart(){

        $bookings_count = [
           0,
           0,
           0,
           0,
           0,
           0,
           0,
           0,
           0,
           0,
           0,
           0,
           0,
       ];
       if(auth()->user()->role == 1){
             $bookings = Booking::all();
        }elseif(auth()->user()->role == 3){
            $bookings =  Booking::where("supplier_id",auth()->user()->id)->get();
        }else{
            $bookings = [];
        }

            foreach($bookings as $booking)
            {
                 $mon_num =  $booking->created_at->month;
                $bookings_count[$mon_num] = $bookings_count[$mon_num]+1;

            }



        return   json_encode($bookings_count);

    }

    public static function WeeklyCompleteBookings(){


         $monday = date("Y-m-d", strtotime('monday this week'));
         $sunday = date("Y-m-d", strtotime('sunday this week'));

         $week = [0,0,0,0,0,0,0];

        if(auth()->user()->role == 1){
            $bookings = Booking::where("status","=","finish")->whereBetween("pickup_date",[$monday,$sunday])->get();
        }elseif(auth()->user()->role == 3){
            $bookings =  Booking::where("status","=","finish")->whereBetween("pickup_date",[$monday,$sunday])->where("supplier_id",auth()->user()->id)->get();
        }else{
            $bookings = [];
        }

           foreach($bookings as $booking)
           {
                $day_num =  Carbon::parse($booking->pickup_date)->dayOfWeek;

               $week[$day_num] = $week[$day_num]+1;

           }



       return json_encode($week);


    }

    public static function WeeklyPendingBookings(){


        $monday = date("Y-m-d", strtotime('monday this week'));
        $sunday = date("Y-m-d", strtotime('sunday this week'));

        $week = [0,0,0,0,0,0,0];
       if(auth()->user()->role == 1){
           $bookings = Booking::where("status","!=","finish")->whereBetween("pickup_date",[$monday,$sunday])->get();
       }elseif(auth()->user()->role == 3){
        $bookings =  Booking::where("status","=","finish")->whereBetween("pickup_date",[$monday,$sunday])->where("supplier_id",auth()->user()->id)->get();
        }else{
            $bookings = [];
        }

          foreach($bookings as $booking)
          {
               $day_num =  Carbon::parse($booking->pickup_date)->dayOfWeek;

              $week[$day_num] = $week[$day_num]+1;

          }



      return json_encode($week);


   }

   public static function TopFiveSuppliers(){

    $top_supliers = [];


    if(auth()->user()->role == 1){
         $bookings = Booking::where("status","=","finish")->get();
    }else{
        $bookings = [];
    }

       foreach($bookings as $booking)
       {

        if(array_key_exists($booking->supplier_id, $top_supliers)){

            $top_supliers[$booking->supplier_id]  =   $top_supliers[$booking->supplier_id] + 1;
        }else{
            $top_supliers[$booking->supplier_id] = 1;
        }

       }



        $top_supliers = array_slice($top_supliers,0,5,true);


        $supplier_names =[];
        $supplier_data = [];
       foreach($top_supliers as $key=>$value)
       {
           $supplier_names[] = User::getFullName($key);
           $supplier_data[] = $value;
       }

       return  json_encode(array(
           "names" => $supplier_names,
           "data" => $supplier_data
       ));





   }

   public static function TopFiveDriver(){

    $top_drivers = [];


    if(auth()->user()->role == 1){
         $bookings = Booking::where("status","=","finish")->get();
    }elseif(auth()->user()->role == 3){
        $bookings = Booking::where("status","=","finish")->where("supplier_id",auth()->user()->id)->get();
    }else{
        $bookings = [];
    }

        //  dd($bookings);
       foreach($bookings as $booking)
       {

        if(array_key_exists($booking->driver_id, $top_drivers)){

            $top_drivers[$booking->driver_id]  =   $top_drivers[$booking->driver_id] + 1;
        }else{
            $top_drivers[$booking->driver_id] = 1;
        }

       }



        $top_drivers = array_slice($top_drivers,0,5,true);


       foreach($top_drivers as $key=>$value)
       {
           $driver_names[] = User::getFullName($key);
           $driver_data[] = $value;
       }

       $driver_names =[];
       $driver_data = [];

       return  json_encode(array(
           "names" => $driver_names,
           "data" => $driver_data
       ));





   }
}
