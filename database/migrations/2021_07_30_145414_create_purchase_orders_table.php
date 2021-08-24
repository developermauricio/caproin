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

            //header
            $table->string('customer_order_number')->unique();
            $table->string('internal_order_number')->unique();

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->string('final_user');

            $table->unsignedBigInteger('order_type_id');
            $table->foreign('order_type_id')->references('id')->on('order_types');

            $table->unsignedBigInteger('zone_id');
            $table->foreign('zone_id')->references('id')->on('zones');

            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('employees');

            $table->string('house');
            $table->text('description');
            $table->boolean('has_blueprint');

            $table->unsignedBigInteger('type_coin_id');
            $table->foreign('type_coin_id')->references('id')->on('type_coins');

            $table->double('total_value');

            // $table->unsignedBigInteger('state_order_id');
            // $table->foreign('state_order_id')->references('id')->on('state_orders');

            $table->string('internal_quote_number');
            $table->string('manufacturer_house_quotation_number');

            $table->string('dispatch_guide_number');
            $table->unsignedBigInteger('conveyor_id');
            $table->foreign('conveyor_id')->references('id')->on('conveyors');

            $table->date('order_receipt_date');
            $table->date('offer_delivery_date');
            $table->date('delivery_date_required_customer');
            $table->date('expected_dispatch_date');
            $table->date('actual_dispatch_date');
            $table->date('actual_delivery_date');

            $table->unsignedBigInteger('payment_id');
            $table->foreign('payment_id')->references('id')->on('payment_types');

            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices');

            //o Archivo de factura (Enlace que redirija a la factura o factura Adjunta PDF)

            $table->enum('invoice_state', ['No subida', 'No Entregada']);

            $table->string('invoice_number');
            $table->string('contact_number');

            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers');

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
