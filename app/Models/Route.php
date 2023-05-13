<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    //


    public function getStartPointAttribute()
    {
        return json_decode($this->attributes['start_point']);
    }

    public function getEndPointAttribute()
    {
        return json_decode($this->attributes['end_point']);

    }


    // function getStartCords(){
    //    return  [
    //     'lat' => $this->start_point->pickup_lat ?? 0,
    //     'lng' =>$this->start_point->pickup_long ?? 0
    //    ];
    // }

    // function getEndCords(){
    //     return  [
    //      'lat' => $this->end_point->dropoff_lat ?? 0,
    //      'lng' =>$this->end_point->dropoff_long ?? 0
    //     ];
    //  }
}
