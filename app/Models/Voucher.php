<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{

    protected $fillable = [
        'description'
    ];

    public static function getnewprice($code,$price){
        $v = Voucher::where("code",$code)->first();
        if(!is_null($v)){
            if($v->type == "flat"){
                // flat
                $new_price = ($price - $v->discount);

                if($new_price < 1){
                    $new_price = 0;
                }

                return $new_price;
            }else if($v->type == "percentage"){
                //percentage
                $cal_discount = ($price * ($v->discount/100));
                $new_price =  $price - $cal_discount;


                if($new_price < 1){
                    $new_price = 0;
                }

                return $new_price;

            }

        }else{
            return $price;
        }
    }

    public static function isValid($code){
        $v = Voucher::where("code",$code)->first();
        if(!is_null($v)){
           return true;
        }else{
            return false;
        }
    }

    public static function discount($code){
        $v = Voucher::where("code",$code)->first();
        if(!is_null($v)){
            if($v->type == "flat"){
                // flat
                $discount = $v->discount;

                return $discount;

            }else if($v->type == "percentage"){
                //percentage
                $discount = ($v->discount)."%";

                return $discount;

            }
        }else{
            return false;
        }
    }
}
