<?php

use Faker\Generator as Faker;

$factory->define(App\Address::class, function (Faker $faker) {
    return [
        'line_1' => $faker->address,
        'line_2' => $faker->address,
        'line_3' => $faker->address,
        'post_code' => $faker->postcode,
        'city' => $faker->city,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
    ];
});