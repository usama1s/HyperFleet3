<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Authenticatable
{


    use Notifiable;

    protected $guard = 'customer';
    protected $fillable = [
        'first_name','last_name', 'email', 'password','contact_no'
    ];
    protected $hidden = [
      'password', 'remember_token',
    ];


    public function fullname(){
        return "{$this->first_name} {$this->last_name}";
    }
}
