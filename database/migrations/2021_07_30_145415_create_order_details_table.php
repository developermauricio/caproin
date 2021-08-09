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

            $table->string('customer_product_description')->nullable();
            $table->string('client_product_code')->unique();
            $table->string('application');
            $table->string('value');
            $table->string('flat');

            $table->unsignedBigInteger('type_coin_id')->nullable();
            $table->foreign('type_coin_id')->references('id')->on('type_coins');

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
