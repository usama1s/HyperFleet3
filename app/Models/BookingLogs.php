<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingLogs extends Model
{
    public function updatedBy()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
}
