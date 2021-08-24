<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');

            $table->string('customer_order_number');
            $table->string('Internal_order_number');
            $table->string('manufacturer');
            $table->string('internal_product_code');
            $table->string('client_product_code');

            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            $table->string('customer_product_description')->nullable();

            $table->string('application');
            $table->string('blueprint_number');
            $table->string('blueprint_file');

            $table->unsignedBigInteger('type_coin_id')->nullable();
            $table->foreign('type_coin_id')->references('id')->on('currencies');

            $table->string('value');

            $table->string('internal_quote_number');
            $table->string('house_listing_number');

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
        Schema::dropIfExists('order_details');
    }
}
