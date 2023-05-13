<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\VehicleClass;

use App\Models\User;
use App\Models\Admin;
use App\Models\Driver;
use App\Models\Supplier;
use App\Models\Voucher;
use App\Models\Staff;
use App\Models\BookingLogs;
use App\Models\Booking_invoice;
use App\Models\BookingBalanceSheet;

use App\Events\BookingStatusEvent;
use App\Models\PricingScheme;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    public function showBookingJSON(Request $request, Booking $booking)
    {

        $data = [];

        $data['booking'] = $booking;
        $data['vehicleClass'] = VehicleClass::getById($booking->v_class);
        $data['booking_log'] = BookingLogs::with('updatedBy')->where('booking_id', $booking->id)->get();


        if (!is_null($booking->driver_id)) {

            $data['user'] = User::find($booking->driver_id);
            $data['driver'] = $data['user']->driver;
            $data['vehicle'] = Vehicle::find($booking->vehicle_id);
        }

        return $data;
    }

    public function assignBooking(Request $request)
    {

        $pickup_time = date('H:i:s', strtotime($request->pickup_time));

        if (is_null($request->v_class)) {
            return $result = [
                "status" => "false",
                "error" => "Plese Select Vehicle Class"
            ];
        }

        if (is_null($request->pickup_time)) {
            return $result = [
                "status" => "false",
                "error" => "Plese Select Pickup time"
            ];
        }

        $pickup_time = new Carbon($pickup_time);

        $drivers = DB::table('drivers')
            ->join('users', 'drivers.user_id', '=', 'users.id')
            ->join('vehicles', 'drivers.vehicle_id', '=', 'vehicles.id')
            ->join('shifts', 'drivers.shift_id', '=', 'shifts.id')
            ->where("drivers.status", '!=', NULL)
            ->where('vehicles.vehicle_class_id', '=', $request->v_class)
            ->where('drivers.supplier_id', $request->supplier_id)
            // ->where('shifts.start', '<', $pickup_time) // 12:00pm<12:49pm
            // ->where('shifts.end', '>', $pickup_time) //  11:59am > 12:59pm
            ->get()
            ->filter(function ($item) use ($pickup_time) {
                $start = new Carbon($item->start);
                $end = new Carbon($item->end);

                if ($this->isNowBetweenTimes($pickup_time, $start, $end)) {
                    return $item;
                }
            });

        if (count($drivers) > 0) {
            return $drivers;
        } else {

            if (is_null($request->supplier_id)) {

                $drivers = DB::table('drivers')
                    ->join('users', 'drivers.user_id', '=', 'users.id')
                    ->join('vehicles', 'drivers.vehicle_id', '=', 'vehicles.id')
                    ->join('shifts', 'drivers.shift_id', '=', 'shifts.id')
                    ->where('vehicles.vehicle_class_id', '=', $request->v_class)
                    ->get();

                if (count($drivers) > 0) {
                    return $result = [
                        "status" => "false",
                        "error" => "No Driver Found in Pickup Time"
                    ];
                } else {
                    return $result = [
                        "status" => "false",
                        "error" => "No Driver Found Against Vehicle Class"
                    ];
                }
            } else {
                return $result = [
                    "status" => "false",
                    "error" => "No driver available for selected Supplier"
                ];
            }
        }
        return $drivers;
    }

    public function changeBookingStatus(Request $request)
    {
        $booking = Booking::find($request->id);

        if (!is_null($booking->driver_id)) {
            $active_booking = Booking::where('driver_id', $booking->driver_id)->where("id", "!=", $booking->id)
                ->whereIn('bookings.status', ['active', 'client', 'finish'])->first();

            $all_bookings = Booking::where('driver_id', $booking->driver_id)->whereNotIn('status', ['finish', 'noshow', 'expired'])->count();

            $driver = Driver::where("user_id", $booking->driver_id)->first();

            $vehicle = Vehicle::find($booking->vehicle_id);

            $bookinglog = new BookingLogs;

            if ($request->status == 'open') {
                if (!is_null($booking->driver_id)) {

                    if (!is_null($booking->driver_id)) {
                        if ($all_bookings <= 0) {

                            $driver->status = "available";
                            $vehicle->status = "available";
                            $driver->save();
                            $vehicle->save();
                        } else {
                            $driver->status = "assigned";
                            $vehicle->status = "assigned";
                            $driver->save();
                            $vehicle->save();
                        }
                    }
                    $booking->driver_id = NULL;
                    $booking->vehicle_id = NULL;
                }
                $status = 'Not Assigned';
            } else if ($request->status == 'ready') {

                if (!is_null($booking->driver_id)) {

                    $driver->status = "assigned";
                    $vehicle->status = "assigned";

                    $driver->save();
                    $vehicle->save();
                }

                $status = 'pending approval';
            } else if ($request->status == 'accepted') {

                if (!is_null($booking->driver_id)) {
                    if ($all_bookings <= 0) {

                        $driver->status = "assigned";
                        $vehicle->status = "assigned";
                        $driver->save();
                        $vehicle->save();
                    } else {
                        $driver->status = "booked";
                        $vehicle->status = "booked";
                        $driver->save();
                        $vehicle->save();
                    }
                }
                $status = 'accept';
            } else if ($request->status == 'active') {

                if (!is_null($booking->driver_id)) {

                    if (!is_null($active_booking)) {
                        return array("error" => 'Driver has to complete active Ride');
                    }

                    $driver->status = "booked";
                    $vehicle->status = "booked";

                    $driver->save();
                    $vehicle->save();
                }

                $status = 'start';
            } else if ($request->status == 'noshow') {

                if (!is_null($booking->driver_id)) {

                    if (!is_null($active_booking)) {
                        return array("error" => 'Driver has to complete active Ride');
                    }

                    if ($all_bookings <= 0) {

                        $driver->status = "available";
                        $vehicle->status = "available";
                        $driver->save();
                        $vehicle->save();
                    } else {
                        $driver->status = "assigned";
                        $vehicle->status = "assigned";
                        $driver->save();
                        $vehicle->save();
                    }
                }
                $status = 'no-show';
            } else if ($request->status == 'client') {

                if (!is_null($booking->driver_id)) {

                    if (!is_null($active_booking)) {
                        return array("error" => 'Driver has to complete active Ride');
                    }

                    if ($vehicle->status != "booked") {

                        if ($driver->status != "booked") {

                            $driver->status = "booked";
                            $vehicle->status = "booked";

                            $driver->save();
                            $vehicle->save();
                        }
                    }
                }

                $status = 'pickup-client';
            } else if ($request->status == 'finish') {

                if (!is_null($booking->driver_id)) {

                    if (!is_null($active_booking)) {
                        return array("error" => 'Driver has to complete active Ride');
                    }

                    if ($all_bookings <= 0) {

                        $driver->status = "available";
                        $vehicle->status = "available";
                        $driver->save();
                        $vehicle->save();
                    } else {
                        $driver->status = "assigned";
                        $vehicle->status = "assigned";
                        $driver->save();
                        $vehicle->save();
                    }
                }
                $status = 'finish';

                $invoice = Booking_invoice::where("booking_id", $booking->id)->first();
                $invoice->status = "paid";

                $invoice->save();

                $admin = Admin::where("user_id", "1")->first();

                if ($booking->payment_method == "cash") {
                    $driver->credit +=  $booking->grand_price;
                    $balance_sheet = new BookingBalanceSheet;

                    $balance_sheet->booking_id = $booking->id;
                    $balance_sheet->driver_id = $driver->user_id;
                    $balance_sheet->supplier_id = null;
                    $balance_sheet->credit = $booking->grand_price;
                    $balance_sheet->debit = $booking->grand_price;
                    $balance_sheet->save();
                } else {
                    $admin->credit += $booking->grand_price;
                    $balance_sheet = new BookingBalanceSheet;
                    $balance_sheet->booking_id = $booking->id;
                    $balance_sheet->driver_id = $driver->user_id;
                    $balance_sheet->supplier_id = null;
                    $balance_sheet->credit = $booking->grand_price;
                    $balance_sheet->debit = 0;
                    $balance_sheet->save();
                }
            } else {
                $status = 'expired';
            }

            $bookinglog->log = $status;
            $bookinglog->updated_by = $request->loginUser;
            $bookinglog->booking_id = $booking->id;

            $booking->status = $request->status;
            $bookinglog->save();
            $booking->save();

            event(new BookingStatusEvent($booking));

            return array("msg" => "Booking status updated");
        }

        //  return back()->with("success","booking status updated");

    }

    public function checkPricing(Request $request, PricingScheme $pricing_scheme_id)
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


        $userPickUp = $request->pickup_point;
        $userDropoff = $request->drop_off_point;

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



                if ((0 >= $pickup_radius) and  ($pickup_radius <= $start_raduis)) {
                    if ((0 >= $dropoff_radius) and  ($dropoff_radius <= $end_raduis)) {
                        $availableRoutes[] = $route;
                    }
                }
            }
        }

        if (count($availableRoutes) == 0) {

            if (!is_null($userDropoff)) {
                //point 2 point calculation
                $userDistance = floatval($request->distance);

                // dd($pricing_scheme_id);

                if ($userDistance <= 10) {
                    $status = true;
                    $preprice=$pricing_scheme_id->up_to_ten;
                    $prenum=str_replace(',', '.', $preprice);
                    $price=floatval($prenum);
                    $type = "Up to 10KM Fixed Pricing";
                    $msg = "Price Found";
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
               

                if ($request->hourly <  $pricing_scheme_id->minimum_hours) {
                    $status = false;
                    $price = null;
                    $type = "error";
                    $msg = "You can book for minimum ".$pricing_scheme_id->minimum_hours." hrs";
                } else {
                    if ($request->hourly == PricingScheme::PER_DAY_HOURS) {
                        $status = true;
                        $price = $pricing_scheme_id->price_per_day;
                        $type = "Per Day Booking (" . PricingScheme::PER_DAY_HOURS . " Hrs)";
                        $msg = "Price Found";
                    } else {
                        
                        $status = true;
                        $price = $request->hourly * $pricing_scheme_id->price_per_hour;
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

    private function isNowBetweenTimes($pickupTime, $startDateTime, $endDateTime)
    {
        $curTimeLocal = $pickupTime;
        $startTime = $curTimeLocal->copy();
        $startTime->hour = $startDateTime->hour;
        $startTime->minute = $startDateTime->minute;
        $endTime = $curTimeLocal->copy();
        $endTime->hour = $endDateTime->hour;
        $endTime->minute = $endDateTime->minute;
        if ($endTime->lessThan($startTime)) {

            $endTime->addDay();
        }

        return ($curTimeLocal->isBetween($startTime, $endTime));
    }
}
