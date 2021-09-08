<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->integer('number_of_days_after_generating_invoice')->nullable(); //Numero de dias para enviar cobro despues de generada la factura
            $table->integer('number_of_days_after_invoice_overdue')->nullable(); //Numero de dias despues de vencidad la factura;
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('principal')->default(0)->nullable();
            $table->integer('sub_sede_of')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
