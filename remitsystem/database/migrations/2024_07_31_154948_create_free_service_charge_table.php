<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreeServiceChargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_service_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referrer_user_id');
            $table->unsignedBigInteger('referred_user_id');
            $table->unsignedBigInteger('redeemed_transaction_id')->nullable();
            $table->boolean('used')->default(0)->comment('0: not used, 1: used');
            $table->unsignedBigInteger('transaction_id')->nullable()->comment('The transaction that created this free service charge');
            $table->foreign('referrer_user_id')->references('id')->on('users');
            $table->foreign('referred_user_id')->references('id')->on('users');
            $table->foreign('redeemed_transaction_id')->references('id')->on('transactions');
            $table->foreign('transaction_id')->references('id')->on('transactions');
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
        Schema::dropIfExists('free_service_charge');
    }
}
