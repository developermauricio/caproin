<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Faker\Generator as Faker;

$factory->define(ProductPrice::class, function (Faker $faker) {
    return [
        'price' => $faker->numberBetween(10, 100000),
        'product_id' => Product::inRandomOrder()->first()->id,
        'currency_id' => Currency::inRandomOrder()->first()->id
    ];
});
