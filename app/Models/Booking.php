<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    public function driver(){
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function agent(){
        return $this->belongsTo(User::class);
    }

    public static function getPaymentMethod($id){
        $booking = Booking::find($id);

        if(!is_null($booking)){
            return $booking->payment_method;
        }else{
            return "unknown method";
        }
    }
}
