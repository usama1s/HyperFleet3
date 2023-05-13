<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleClass extends Model
{
    //

    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }

    public static function getById($id){
        $a = VehicleClass::where('id',$id)->first();
        if(!is_null($a)){
            return $a['name'];
        }else{
            return null;
        }

    }
}
