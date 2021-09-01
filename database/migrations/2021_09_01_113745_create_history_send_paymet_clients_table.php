<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorySendPaymetClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_send_paymet_clients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->integer('type_send')->nullable();
            $table->integer('detail')->nullable();
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
        Schema::dropIfExists('history_send_paymet_clients');
    }
}
