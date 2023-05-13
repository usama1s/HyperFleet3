<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Driver::class, function (Faker $faker) {

    $random_expiries = [
        '2025-06-07',
        '2026-06-07',
        '2024-06-07',
        '2026-06-07',
        '2022-06-07',
    ];

    return [
        'user_id' => factory('App\Models\User')->create()->id,
        'address' => $faker->address,
        'rta_card_expiry' => $random_expiries[array_rand($random_expiries)],
        'license_expiry' => $random_expiries[array_rand($random_expiries)],
        'emirates_expiry' => $random_expiries[array_rand($random_expiries)],
        'driver_image' => 'demo.jpg',
        'license_image' => 'demo.jpg',
        'rta_card_image' => 'demo.jpg',
        'emirates_id' => 'demo.jpg',



    ];
});
