<?php
namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<=20; $i++){

            Vehicle::factory()->create()->each(function ($vehicle) use ($i) {
                $vehicle = \App\Models\Vehicle::find($i);
                $vehicle->image = $i.'.jpg';
                $vehicle->insurance_file = $i.'.jpg';
                $vehicle->registration_file = $i.'.jpg';
                $vehicle->save();

             });


        }

    }
}
