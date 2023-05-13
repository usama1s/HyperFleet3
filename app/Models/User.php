<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,  HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password','role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function bookingAgent()
    {
        return $this->hasOne(BookingAgent::class);
    }


    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function booking()
    {
        return $this->hasMany(Model\Booking::class,"driver_id");
    }

    public function DriverVehicle(){
        return $this->hasOne(Model\Vehicle::class,'driver_id');
    }

    public function SupplierVehicle(){
        return $this->hasMany(Model\Vehicle::class,'supplier_id');
    }

    public function bank_details(){
        return $this->hasOne(BankDetail::class,'user_id');
    }

    public function fullname(){
        return "{$this->first_name} {$this->last_name}";
    }



    public static function getFullName($user_id){
        $u = User::find($user_id);

        if(!is_null($u)){
            return $u->first_name . " " . $u->last_name;
        }else{
            return false;
        }

    }



    public static function getEmail($user_id){
        $u = User::find($user_id);

        if(!is_null($u)){
            return $u->email;
        }else{
            return false;
        }

    }
}

