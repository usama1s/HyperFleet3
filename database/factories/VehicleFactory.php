<?php

namespace Database\Factories;

use App\Models\VehicleClass;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $manufacturers = [
            'LeBaron',
            'Fleetwood',
            'Willoughby',
            'Derham ',
            'Fisher',
        ];

        $vehicle_years = [
            '2015',
            '2016',
            '2017',
            '2018',
            '2019',
            '2020'
        ];

        $random_expiries = [
            '2025-06-07',
            '2026-06-07',
            '2024-06-07',
            '2026-06-07',
            '2022-06-07',
        ];

        $insurance_companies = [
            'RV24',
            'AXA',
            'Helvetia Versicherungen',
            'DA Direkt',
            'BarmeniaDirekt Versicherungen'
        ];

        $models_vehicle = [
            'Cadillac XTS',
            'Chrysler 300',
            'Great Wall Hover',
            'FAW Hongqi',
            'Hyundai Equus',
            'Samand Sarir',
            'Lexus LM',
            'Lincoln MKS'
        ];

        return [
            'vehicle_class_id' => VehicleClass::all()->random(1)->first()->id,
            'manufacturer' => $manufacturers[array_rand($manufacturers)],
            'license_plate' => $this->faker->bothify('???-####'),
            'car_model' => $models_vehicle[array_rand($models_vehicle)],
            'car_year' => $vehicle_years[array_rand($vehicle_years)],
            'car_color' => $this->faker->colorName,
            'insurance_detail' => $insurance_companies[array_rand($insurance_companies)],
            'insurance_expiry' => $random_expiries[array_rand($random_expiries)],
            'registration_detail' => $insurance_companies[array_rand($insurance_companies)],
            'registration_expiry' => $random_expiries[array_rand($random_expiries)],
            'registration_file' => 'demo.png',
            'insurance_file' => 'demo.png',
            'seats' => $this->faker->numberBetween($min = 1, $max = 18),
            'luggage' => $this->faker->numberBetween($min = 1, $max = 6),
            'price' => $this->faker->numberBetween($min = 90, $max = 130),
            'description' => $this->faker->text($maxNbChars = 100),
            'image' => 'test.jpg',


        ];
    }
}
