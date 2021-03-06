<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Provider;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    $name = $faker->company;
    return [
        'code' => $faker->numberBetween(),
        'business_name' => $name,
    ];
});
