<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BookingAgent extends Model
{
public function getProfileImage(){

        $check_image_file = public_path('storage/assets/booking-agent/').$this->profile_image;
        if(is_file($check_image_file)){
            return  asset('/public/storage/assets/booking-agent').'/'.$this->profile_image;
        }else{
            return asset('public/images/')."/default-user.jpg";
        }
    }

    public function getAgreementDownload(){

        $check_image_file = 'booking-agent-agreements/'.$this->agreement;

        return Storage::download($check_image_file);

    }

    public function getAgreementPath(){
        return $check_image_file = storage_path('app/booking-agent-agreements/').$this->agreement;
        if(is_file($check_image_file)){
            return  asset('/public/storage/assets/booking-agent').'/'.$this->agreement;
        }else{
            return null;
        }
    }
}
