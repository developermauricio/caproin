<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Invoice;
use Faker\Generator as Faker;

$factory->define(Invoice::class, function (Faker $faker) {
    return [
        'invoice_number' => $this->faker->numberBetween(),
        'date_issue' => $faker->dateTime(),
        'customer_id' => \App\Models\Customer::all()->random()->id,
        'type_invoice_id' => \App\Models\TypeInvoice::all()->random()->id,
        'state_id' => \App\Models\StateInvoice::all()->random()->id,
        'value_total' => 150000000,
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
