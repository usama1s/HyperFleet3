<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;

class Booking_invoice extends Model
{

    public static function getTotal($invoice){
        $booking = Booking::find($invoice->booking_id);
        if(!is_null($booking)){
            return $booking->grand_price;
        }else{
            return 0;
        }

    }

    public static function getCustomerName($invoice){
        $booking = Booking::find($invoice->booking_id);

        if(!is_null($booking)){

            return $booking->first_name." ".$booking->last_name;

        }else{
            return false;
        }

    }


}
