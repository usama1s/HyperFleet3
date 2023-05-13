<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Customer;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstNameMale,
        'last_name' => $faker->lastName,
        'contact_no' => $faker->e164PhoneNumber,
        'email' => $faker->unique()->safeEmail,
        // 'email_verified_at' => now(),
        // 'password' => Hash::make('user123'), // secret
        // 'remember_token' => Str::random(10),
        // 'role' => "5"
    ];
});
