<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TradeAgreement;
use Faker\Generator as Faker;

$factory->define(TradeAgreement::class, function (Faker $faker) {
    return [
        'consecutive_Offer' => $this->faker->numberBetween(),
        'version' => '1.0',
        'state' => 1,
        'delivery_time' => '15 dias',
        'internal_product_code' => '54856424',
        'client_product_code' => '696969699',
        'customer_id' => \App\Models\Customer::all()->random()->id,
        'short_description' => $faker->sentence($nbWords = 10, $variableNbWords = true),
        'currency_id' => \App\Models\Currency::all()->random()->id,
        'creation_date' => $faker->dateTime(),
        'final_date' => $faker->dateTime(),
        'unit_value' => 150000000,
        'TRM' => 3850,
    ];
});
