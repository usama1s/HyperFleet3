<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Supplier;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'user_id' => factory('App\Models\User')->create()->id,
        'company_name' => $faker->company,
        'agreement' => 'agreement',
        'credit' => $faker->numberBetween($min = 5, $max = 80).'$',
        'address' => $faker->address,
        'sales_person' => $faker->firstNameMale,
        'payment_method' => $faker->creditCardType,
        'commission' => $faker->numberBetween($min = 5, $max = 80).'$',
        'details' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'payment_terms' => 'payment terms',
        'bank_details' => 'creditCardDetails',
        'image' => 'demo.jpg',
    ];
});
