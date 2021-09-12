<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Currency;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Faker\Generator as Faker;

$factory->define(OrderDetail::class, function (Faker $faker) {
    $quantity = $faker->numberBetween(1, 5);
    $value = $faker->numberBetween(10000);
    $total = $quantity*$value;
    return [
        'purchase_order_id' => PurchaseOrder::inRandomOrder()->first()->id,
        'product_id' => Product::inRandomOrder()->first()->id,
        'currency_id' => Currency::inRandomOrder()->first()->id,
        'manufacturer' => $faker->company(),
        'internal_product_code' => $faker->numberBetween(10000),
        'client_product_code' => $faker->numberBetween(10000),
        'customer_product_description' => $faker->paragraph(1),
        'application' => $faker->paragraph(1),
        'blueprint_number' => $faker->numberBetween(10000),
        'blueprint_file' => 'file.pdf',
        'value' => $value,
        'quantity' => $quantity,
        'total_value' => $total,
        // 'internal_quote_number' => $faker->numberBetween(10000),
        'house_quote_number' => $faker->numberBetween(10000),
    ];
});
