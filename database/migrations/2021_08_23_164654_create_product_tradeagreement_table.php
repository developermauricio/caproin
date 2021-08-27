<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTradeagreementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_tradeeagreement', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('tradeagreement_id')->nullable();
            $table->foreign('tradeagreement_id')->references('id')->on('trade_agreements');
            $table->integer('minimum_amount')->nullable();
            $table->string('client_product_code')->nullable();
            $table->string('internal_product_code')->nullable();
            $table->double('unit_value')->nullable();
            $table->mediumText('description')->nullable();
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
        Schema::dropIfExists('product_tradeeagreement');
    }
}
