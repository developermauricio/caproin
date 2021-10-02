<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderStateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_state_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');

            $table->unsignedBigInteger('state_order_id');
            $table->foreign('state_order_id')->references('id')->on('state_orders');

            $table->text('description')->nullable();
            $table->dateTime('estimated_date')->nullable();
            $table->timestamps();
        });

        Schema::table('purchase_orders', function (Blueprint $table){
            $table->unsignedBigInteger('current_status_id')->nullable();
            $table->foreign('current_status_id')->references('id')->on('purchase_order_state_histories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_state_histories');
        // Schema::table('purchase_orders', function (Blueprint $table){
        //     $table->dropForeign('current_status_id')->nullable();
        //     $table->foreign('current_status_id')->references('id')->on('purchase_order_state_histories');
        // });
    }
}
