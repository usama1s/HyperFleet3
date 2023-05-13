<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static function getMeta($key){
        $meta =  Setting::whereMeta($key)->first();

        if(!is_null($meta)){
            return $meta->value;
        }else{
            return false;
        }
    }
}
