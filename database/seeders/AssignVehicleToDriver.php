<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Driver;
use App\Models\Vehicle;

class AssignVehicleToDriver extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i=10; $i<=20; $i++){


                $vehicle = Vehicle::find($i);
                $driver = Driver::find($i);

                $vehicle->driver_id = $driver->user_id;
                $driver->vehicle_id = $vehicle->id;

                $vehicle->status = "available";
                $driver->status = "available";

                $driver->save();
                $vehicle->save();

        }

    }
}
