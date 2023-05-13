<?php
namespace Database\Seeders;

use App\Models\VehicleClass;
use Illuminate\Database\Seeder;

class VehicleClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes_list = [
            "Economy Class",
            "First Class",
            "Business Class",
        ];

        foreach ($classes_list as $class) {
            \App\Models\VehicleClass::create(
                 [
                    'name' => $class,
                    'price' => '100',
                    'thumbnail' => '2.jpg',
                    'desc'=>'test',
                    'passengers' => '2',
                    'bags' => '2',

                 ]
             );
        }
    }
}
