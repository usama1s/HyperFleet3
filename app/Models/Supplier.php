<?php

namespace App\Models;
use App\Models\User;
use App\Models\Booking;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public function fullName($id){
        $user = User::find($id);
        if(!is_null($user)){
            return $user->first_name. " ". $user->last_name;
        }else{
            return null;
        }
    }

    public function supplierdata($id){
        //$driver =  Driver::where("user_id",$id)->first();
            $supplier = Supplier::where('user_id',$id)->first();

            if(is_null($supplier)){

                return array(
                    "company_name"=>"",
                    "contact_no"=>"",
                    "image"=>"",
                );

            }else{

                return array(
                    "company_name"=>$supplier->company_name,
                    "contact_no"=>$supplier->contact_no,
                    "image"=>$supplier->image,
                );

            }


    }

    public static function totalBookings($id){
        $bookings = Booking::where("supplier_id",$id)->get();
        if(!is_null($bookings)){
            return count($bookings);
        }else{
            return 0;
        }
    }


    public static function completeBookings($id){
        $bookings = Booking::where("supplier_id",$id)->where("status","finish")->get();


        if(!is_null($bookings)){
            return count($bookings);
        }else{
            return 0;
        }
    }

    public static function pendingBookings($id){
        $bookings = Booking::where("supplier_id",$id)
        ->whereNotIn("status",['finish','no-show'])
        ->get();


        if(!is_null($bookings)){
            return count($bookings);
        }else{
            return 0;
        }
    }

    public static function totalProfit($id){

        $bookings = Booking::where("supplier_id",$id)->where("status","finish")->get();

        if(!is_null($bookings)){
            $total = 0;

           foreach($bookings as $booking){
                $price =$booking->grand_price;
                $total += $price;
           }

           return $total;

        }else{
            return 0;
        }
    }


    public static function getCommision($id){
        $booking = Supplier::where("user_id",$id)->first();

        if(!is_null($booking)){
            return $booking->commission;
        }else{
            return 0;
        }
    }


}
