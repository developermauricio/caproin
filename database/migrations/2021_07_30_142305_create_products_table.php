<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_products_id')->nullable();
            $table->foreign('type_products_id')->references('id')->on('type_products');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('code');
            $table->enum('state', [
                \App\Models\Product::ACTIVE,
                \App\Models\Product::INACTIVE
            ])->default(\App\Models\Zone::ACTIVE);
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
        Schema::dropIfExists('products');
    }
}
