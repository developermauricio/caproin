<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderStateHistory;
use App\Models\StateOrder;
use Faker\Generator as Faker;

$factory->define(PurchaseOrderStateHistory::class, function (Faker $faker) {
    return [
        'purchase_order_id' => PurchaseOrder::inRandomOrder()->first()->id,
        'state_order_id' => StateOrder::inRandomOrder()->first()->id,
        'description' => $faker->paragraph(1),
        'estimated_date' => $faker->date
    ];
});
