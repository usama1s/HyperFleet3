<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\VehicleClass;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Supplier;
use App\Models\Voucher;
use App\Models\Staff;
use App\Models\BookingLogs;


class CustomerController extends Controller
{

    public function show($id){

        $customer = Customer::findOrFail($id);
        return response($customer,200);
    }

}
