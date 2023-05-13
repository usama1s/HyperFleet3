<?php

namespace App\Models;

use App\Models\PricingScheme;
use App\Models\Supplier;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    public function pricing()
    {
        return $this->belongsTo('App\Models\PricingScheme', 'pricing_scheme_id', 'id');
    }

    public function vehicleClass()
    {
        return $this->belongsTo(VehicleClass::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id','user_id')->withDefault([
            "company_name" => "HyperBooking"
        ]);
    }

    public function getPlate($id)
    {
        $vehicle = Vehicle::find($id);
        return $vehicle->license_plate;
    }

    public function getProperty($id, $prop)
    {
        $vehicle = Vehicle::find($id);
        return $vehicle->$prop;
    }

    public static function getClassID($id)
    {
        $vehicle = Vehicle::find($id);
        return $vehicle->vehicle_class_id;
    }

    public static function getClassName($id)
    {
        $vehicle = Vehicle::find($id);
        return VehicleClass::find($vehicle->vehicle_class_id)->name();
    }

    public function vdata($id)
    {
        $vehicle =  Vehicle::find($id);

        $vehicle_class_id = $vehicle->vehicle_class_id;
        $vehicle_class = VehicleClass::find($vehicle_class_id);
//        $vehicle_pricing_id = $vehicle->pricing_scheme_id;
//        $vehicle_pricing = PricingScheme::find($vehicle_pricing_id);

        return array(
            "vclass" => $vehicle_class->name,
            "color" => $vehicle->car_color,
            "seats" => $vehicle->seats,
            "luggage" => $vehicle->luggage,
//            "vpricing" => $vehicle_pricing->title,
            "car_info" => $vehicle->manufacturer . " " . $vehicle->car_model . " " . $vehicle->car_year,
            "image" => $vehicle->image,

        );
    }

    public function calculatePrice($userPickUp, $userDropoff,$hourly,$userDistance)
    {
        $data = [
            "status" => false,
            'price' =>  0,
            'type_of_pricing' => null,
            'message' => "No pricing found"
        ];
        $price = 0;
        $type = null;
        $status = false;
        $msg = "No pricing found";
        $availableRoutes = [];

        $pricing_scheme_id = $this->pricing;

        if (!is_null($userDropoff)) {
            $sqlRoutes = DB::table('routes')->select(
                'id',
                "start_point",
                "end_point",
                "price",
                "distance",
                "isValidForReturn",
                'pricing_scheme_id',
                'start_radius',
                'end_radius',
                DB::raw("JSON_VALUE(start_point,'$.pickup_lat') as pickupLat"),
                DB::raw("JSON_VALUE(start_point,'$.pickup_long') as pickupLng"),

                DB::raw("(111.111 *
            DEGREES(ACOS(LEAST(1.0, COS(RADIANS(JSON_VALUE(start_point,'$.pickup_lat')))
                 * COS(RADIANS(" . $userPickUp['lat'] . "))
                 * COS(RADIANS(JSON_VALUE(start_point,'$.pickup_long') - " . $userPickUp['lng'] . "))
                 + SIN(RADIANS(JSON_VALUE(start_point,'$.pickup_lat')))
                 * SIN(RADIANS(" . $userPickUp['lat'] . ")))))) as pickup_radius"),

                DB::raw("JSON_VALUE(end_point,'$.dropoff_lat') as dropoffLat"),
                DB::raw("JSON_VALUE(end_point,'$.dropoff_long') as dropoffLng "),

                DB::raw("(111.111 *
            DEGREES(ACOS(LEAST(1.0, COS(RADIANS(JSON_VALUE(end_point,'$.dropoff_lat')))
                 * COS(RADIANS(" . $userDropoff['lat'] . "))
                 * COS(RADIANS(JSON_VALUE(end_point,'$.dropoff_long') - " . $userDropoff['lng'] . "))
                 + SIN(RADIANS(JSON_VALUE(end_point,'$.dropoff_lat')))
                 * SIN(RADIANS(" . $userDropoff['lat'] . ")))))) as dropoff_radius"),

            )
                ->where('pricing_scheme_id', '=', $pricing_scheme_id->id)

                ->get();

            foreach ($sqlRoutes as $route) {
                $start_raduis = floatval($route->start_radius);
                $pickup_radius = floatval($route->pickup_radius);

                $end_raduis = floatval($route->end_radius);
                $dropoff_radius = floatval($route->dropoff_radius);

                 if ((0 >= $pickup_radius) and  ($pickup_radius <= $start_raduis)) {    // TODO: das weg
                     if ((0 >= $dropoff_radius) and  ($dropoff_radius <= $end_raduis)) {   // TODO: das weg
                        $availableRoutes[] = $route;
                     }   // TODO: das weg
                 }   // TODO: das weg
            }
        }

        if (count($availableRoutes) == 0) {

            if (!is_null($userDropoff)) {
                //point 2 point calculation
                $userDistance = floatval($userDistance);

                // dd($pricing_scheme_id);

                if ($userDistance <= 10) {
                    
                    $preprice=$pricing_scheme_id->up_to_ten;
                    $prenum=str_replace(',', '.', $preprice);
                    $price=floatval($prenum);
                    $type = "Up to 10KM Fixed Pricing";
                    $msg = "Price Found";
                    $status = true;

                } else if ($userDistance > 10 && $userDistance <= 100) {
                    $status = true;
                    $inclkm = 10;
                    $preprice=$pricing_scheme_id->up_to_ten;
                    $prenum=str_replace(',', '.', $preprice);
                    $basePrice=floatval($prenum);
                    $predist=$pricing_scheme_id->ten_to_hundred;
                    $drate=str_replace(',', '.', $predist);
                    $dval=floatval($drate);
                    $kmPrice = ($userDistance - $inclkm) * $dval;
                    $price = $basePrice + $kmPrice;
                    $type = "10KM to 100KM + base Pricing";
                    $msg = "Price Found";
                } else if ($userDistance > 100 && $userDistance <= 200) {
                    $status = true;
                    $inclkm = 10;
                    $preprice=$pricing_scheme_id->up_to_ten;
                    $prenum=str_replace(',', '.', $preprice);
                    $basePrice=floatval($prenum);  // 10 km                    
                    $dhpreprice=$pricing_scheme_id->hundred_to_twoHundred;
                    $dhdist=str_replace(',', '.', $dhpreprice);
                    $dhval=floatval($dhdist);
                    $kmPrice = ($userDistance - $inclkm) * $dhval;
                    $price = $basePrice + $kmPrice;
                    $type = "100KM to 200KM  Pricing";
                    $msg = "Price Found";
                } else if ($userDistance > 200) {                   
                    $status = true;
                    $inclkm = 10;
                    $preprice=$pricing_scheme_id->up_to_ten;
                    $prenum=str_replace(',', '.', $preprice);
                    $basePrice=floatval($prenum);  // 10 km                    
                    $mhpreprice=$pricing_scheme_id->twoHundred_and_above;
                    $mhnum=str_replace(',', '.', $mhpreprice);
                    $mhval=floatval($mhnum);
                    $kmPrice = ($userDistance - $inclkm)* $mhval;
                    $price = $basePrice + $kmPrice;
                    $type = "200 KM to above Pricing";
                    $msg = "Price Found";
                }
} else {
                // hourly price

                if (intval($hourly) < intval($pricing_scheme_id->minimum_hours)) {
                    $status = false;
                    $price = null;
                    $type = "error";
                    $msg = "You can book for minimum " . $pricing_scheme_id->minimum_hours . " hrs";
                } else {
                    if (intval($hourly) == PricingScheme::PER_DAY_HOURS) {
                        $status = true;
                        $price = floatval($pricing_scheme_id->price_per_day);
                        $type = "Per Day Booking (" . PricingScheme::PER_DAY_HOURS . " Hrs)";
                        $msg = "Price Found";
                    } else {

                        $status = true;
                        $price = floatval($hourly) * floatval($pricing_scheme_id->price_per_hour);
                        $type = " Per Hours Pricing";
                        $msg = "Price Found";
                    }
                }
            }
        } else {
            // price form routes
            $status = true;
            $price = $availableRoutes[0]->price;
            $type = "Route Pricing";
            $msg = "Price Found";
        }

        $data = [
            "status" => $status,
            'price' =>  $price,
            'type_of_pricing' => $type,
            'message' => $msg
        ];      

        return $data;
    }
}