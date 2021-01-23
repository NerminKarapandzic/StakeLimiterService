<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Device;

$factory->define(Device::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid(),
        'restrExpiry' => now()
    ];
});
