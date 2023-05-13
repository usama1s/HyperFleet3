<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Staff;

$factory->define(Staff::class, function (Faker $faker) {
    return [
        'user_id' => factory('App\Models\User')->create()->id,
        'image' => 'demo.jpg',

    ];
});
