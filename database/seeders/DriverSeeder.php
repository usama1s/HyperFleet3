<?php
namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use App\Models\Shift;

use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<=20; $i++){

            factory(Driver::class)->create()->each(function ($vehicle) use ($i) {
               $driver = Driver::find($i);
               $user = User::find($driver->user_id);
               $user->role = "4";
               $driver->driver_image = $i.".jpg";
               $driver->license_image = $i.".jpg";
               $driver->rta_card_image = $i.".jpg";
               $driver->emirates_id = $i.".jpg";

               $driver->shift_id = Shift::inRandomOrder()->first()->id;

               $driver->save();
               $user->save();

             });

        }
    }
}
