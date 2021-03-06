<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Conveyor;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\OrderType;
use App\Models\PaymentType;
use App\Models\Provider;
use App\Models\PurchaseOrder;
use App\Models\Zone;
use App\User;
use Faker\Generator as Faker;

$factory->define(PurchaseOrder::class, function (Faker $faker) {

    $delivery_date_required_customer = $faker->dateTimeBetween('-130 days');
    $order_receipt_date = $faker->dateTimeBetween('-180 days');
    $offer_delivery_date = $faker->dateTimeBetween('-160 days');
    $expected_dispatch_date = $faker->dateTimeBetween('-160 days');

    $actual_dispatch_date = $faker->randomElement([null, $faker->dateTimeBetween('-160 days'), $expected_dispatch_date, null]);
    $actual_delivery_date = null;
    if ($actual_dispatch_date) {
        $actual_delivery_date = $faker->randomElement([null, $faker->dateTimeBetween('-160 days'), $offer_delivery_date, null]);
    }
    return [
        'customer_order_number' => $faker->numberBetween(5000),
        'internal_order_number' => $faker->numberBetween(5000),
        'customer_id' => Customer::inRandomOrder()->first()->id,
        'final_user' => $faker->text(20),
        'order_type_id' => OrderType::inRandomOrder()->first()->id,
        'zone_id' => Zone::inRandomOrder()->first()->id,
        'seller_id' => Employee::inRandomOrder()->first()->id,
        'house' => $faker->company(),
        'description' => $faker->paragraph(1),
        // 'has_blueprint' => $faker->randomElement([0, 1]),
        'currency_id' => Currency::inRandomOrder()->first()->id,
        'total_value' => $faker->randomFloat(2, 1000, 200000),
        'internal_quote_number' => $faker->numberBetween(5000),
        'manufacturer_house_quotation_number' => $faker->numberBetween(5000),
        'dispatch_guide_number' => $faker->numberBetween(5000),
        'conveyor_id' => Conveyor::inRandomOrder()->first()->id,
        'trm' => $faker->numberBetween(3000, 4000),
        'total_delivery' => $faker->randomElement([0, 1]),
        'order_receipt_date' => $order_receipt_date,
        'offer_delivery_date' => $offer_delivery_date,
        'delivery_date_required_customer' => $delivery_date_required_customer,
        'expected_dispatch_date' => $expected_dispatch_date,
        'actual_dispatch_date' => $actual_dispatch_date,
        'actual_delivery_date' => $actual_delivery_date,
        'payment_id' => PaymentType::inRandomOrder()->first()->id,
        'invoice_id' => Invoice::inRandomOrder()->first()->id,
        'invoice_state' => $faker->randomElement(['No subida', 'No Entregada']),
        'invoice_number' => $faker->numberBetween(5000),
        'contact_number' => $faker->numberBetween(5000),
        // 'provider_id' => Provider::inRandomOrder()->first()->id
    ];
});
