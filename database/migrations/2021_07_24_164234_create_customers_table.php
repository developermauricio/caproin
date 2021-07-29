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
            $table->unsignedBigInteger('customer_position_id');
            $table->foreign('customer_position_id')->references('id')->on('customer_positions');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('customer_category_id')->nullable();
            $table->foreign('customer_category_id')->references('id')->on('customer_categories');
            $table->unsignedBigInteger('customer_type_id')->nullable();
            $table->foreign('customer_type_id')->references('id')->on('customer_types');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
