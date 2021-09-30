<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Invoice;
use Faker\Generator as Faker;

$factory->define(Invoice::class, function (Faker $faker) {

    $payment_type_id = \App\Models\PaymentType::all()->random()->id;

    $total_value = $faker->numberBetween(10000, 1000000);

    $value_payment = null;
    if ($payment_type_id === 2) {
        $value_payment = $faker->numberBetween(10000, $total_value - 30000);
    }
    return [
        'invoice_number' => $this->faker->numberBetween(),
        'date_issue' => $faker->dateTime(),
        'customer_id' => \App\Models\Customer::all()->random()->id,
        'type_invoice_id' => \App\Models\TypeInvoice::all()->random()->id,
        'payment_type_id' => $payment_type_id,
        'state_id' => \App\Models\StateInvoice::all()->random()->id,
        'value_total' => $total_value,
        'value_payment' => $value_payment,
        'date_payment_client' => $faker->dateTime(),
        'electronic_invoice_number' => $this->faker->numberBetween(),
        'expiration_date' => $faker->dateTime(),
        'date_received_client' => $faker->dateTime(),
        'invoice_date_house_manufacturer' => $faker->dateTime(),
        'commission_receipt_date' => $faker->dateTime(),
        'new_agreed_payment_date' => $faker->dateTime(),
        'invoice_number_house_representative' => $this->faker->numberBetween(),
        'commission_value' => 450000,
        'comments' => $faker->sentence,
    ];
});
