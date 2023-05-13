<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{


    public static function CountUnRead(){
         $msgs = Message::where("receiver",Auth()->user()->id)->where("is_read","0")->where("receiver_del","0")->get();

        if(!is_null($msgs) && count($msgs) > 0){
            return count($msgs);
        }else{
            return false;
        }
    }
}
