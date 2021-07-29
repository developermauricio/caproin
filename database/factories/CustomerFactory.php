<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'customer_position_id' => \App\Models\CustomerPosition::all()->random()->id,
        'customer_category_id' => \App\Models\CustomerCategory::all()->random()->id,
    ];
});
