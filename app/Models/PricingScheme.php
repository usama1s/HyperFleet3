<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingScheme extends Model
{

    const PER_DAY_HOURS = 8;
    public function vehicle(){
        return $this->hasMany('App\Models\Vehicle','pricing_scheme_id');
    }
//     public function getBasePointAttribute(){
//         return json_decode($this->attributes['base_point']);
// $basepoint = json_decode($this->attributes['base_point']);
// return $basepoint;
//     }
    public function routes(){
        return $this->hasMany(Route::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id','user_id')->withDefault([
            "company_name" => null
        ]);
    }

    public function bookingagent()
    {
        return $this->belongsTo(BookingAgent::class,'booking_agent_id','user_id')->withDefault([
            "company_name" => null
        ]);
    }

}
