<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    $name = $faker->company;
    return [
        'business_name' => $name,
        'number_of_days_after_generating_invoice'=> 3,
        'number_of_days_after_invoice_overdue'=> 5,
    ];
});
