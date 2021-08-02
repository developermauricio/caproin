<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'type_products_id' => \App\Models\TypeProduct::all()->random()->id,
        'name' => $faker->jobTitle,
        'description' => $faker->sentence($nbWords = 40, $variableNbWords = true),
        'short_description' => $faker->sentence($nbWords = 10, $variableNbWords = true),
        'code' => $faker->numberBetween(),
    ];
});
