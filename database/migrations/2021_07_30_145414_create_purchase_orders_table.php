<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->text('description');
            $table->double('total_value');
            $table->string('user_end');
            $table->enum('type', ['type_1','type_2','type_3']);
            $table->string('manufactured_house');
            $table->date('order_receipt_date');
            $table->date('offer_delivery_date');
            $table->date('delivery_date_required_customer');
            $table->boolean('has_flat');
            $table->unsignedInteger('internal_quote_number');
            $table->unsignedInteger('manufacturer_house_quotation_number');
            $table->string('dispatch_guide_number');
            $table->string('dispatch_date');
            $table->string('actual_delivery_date');
            $table->string('way_to_pay');
            $table->string('invoice_number');
            $table->string('contact_number');


            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedBigInteger('state_order_id');
            $table->foreign('state_order_id')->references('id')->on('state_orders');

            $table->unsignedBigInteger('zone_id');
            $table->foreign('zone_id')->references('id')->on('zones');

            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('employees');

            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers');

            $table->unsignedBigInteger('type_coin_id');
            $table->foreign('type_coin_id')->references('id')->on('type_coins');

            $table->unsignedBigInteger('conveyor_id');
            $table->foreign('conveyor_id')->references('id')->on('conveyors');

            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
