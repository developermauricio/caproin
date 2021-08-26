<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->date('date_issue')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedBigInteger('type_invoice_id')->nullable();
            $table->foreign('type_invoice_id')->references('id')->on('type_invoices');
            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('state_invoices');
            $table->unsignedBigInteger('payment_type_id')->nullable();
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
            $table->double('value_total')->nullable();
            $table->double('value_payment')->nullable();
            $table->date('date_payment_client')->nullable();
            $table->string('electronic_invoice_number')->nullable();
            $table->date('expiration_date')->nullable();
            $table->date('date_received_client')->nullable();
            $table->date('invoice_date_house_manufacturer')->nullable();
            $table->date('commission_receipt_date')->nullable();
            $table->date('new_agreed_payment_date')->nullable();
            $table->string('invoice_number_house_representative')->nullable();
            $table->double('commission_value')->nullable();
            $table->text('comments')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
