<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    // public function getPermission($user_id, $slug, $type)
    // {
    //     $data = Permission::where("user_id", $user_id)
    //     ->where("slug", $slug)->first();
    //     if (!is_null($data)) {
    //         return $data[$type];
    //     } else {
    //         return null;
    //     }
    // }

    // public static function getAllPermissions($user_id)
    // {
    //     $vehicle = Permission::where("user_id", $user_id)->where("slug", "vehicle")->first();
    //     $driver = Permission::where("user_id", $user_id)->where("slug", "driver")->first();
    //     $supplier = Permission::where("user_id", $user_id)->where("slug", "supplier")->first();
    //     $accounts = Permission::where("user_id", $user_id)->where("slug", "account")->first();
    //     return  array(
    //         "vehicle" => $vehicle,
    //         "driver" => $driver,
    //         "supplier" => $supplier,
    //         "account" => $accounts,
    //    );
    // }
}
