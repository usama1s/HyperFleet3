<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
namespace Database\Factories;

use App\Models\VehicleClass;
use Faker\Generator as Faker;

$factory->define(VehicleClass::class, function (Faker $faker) {
    return [
        'name' => 'bussiness',
        'price' => '3131',
        'thumbnail' => '2.jpg',
        'passengers' => '2',
        'bags' => '2',
    ];
});
