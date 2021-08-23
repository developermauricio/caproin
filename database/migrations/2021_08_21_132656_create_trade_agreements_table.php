<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_agreements', function (Blueprint $table) {
            $table->id();
            $table->string('consecutive_Offer')->nullable();
            $table->string('version')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->mediumText('short_description')->nullable();
            $table->enum('state', [
                \App\Models\TradeAgreement::VIGENTE,
                \App\Models\TradeAgreement::FINALIZADO,
            ])->default(\App\Models\TradeAgreement::VIGENTE);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->date('creation_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('final_date')->nullable();
            $table->double('unit_value')->nullable();
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
        Schema::dropIfExists('trade_agreements');
    }
}
