<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Conveyor;
use Faker\Generator as Faker;

$factory->define(Conveyor::class, function (Faker $faker) {
    $name = $faker->company;
    return [
        'name' => $name,
    ];
});
