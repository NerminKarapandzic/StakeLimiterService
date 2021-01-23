<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Ticket;
use App\Device;

$factory->define(Ticket::class, function (Faker $faker) {
    $devices = Device::all();
    $devicesLength = count($devices);
    return [
        'id' => $faker->uuid(),
        'device_id' => $devices[$faker->numberBetween(0, ($devicesLength - 1))],
        'stake' =>  (float)$faker->numberBetween(10, 300)
    ];
});
