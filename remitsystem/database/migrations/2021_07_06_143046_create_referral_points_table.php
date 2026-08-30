<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date');
            $table->integer('points');
            $table->text('description');
            $table->integer('claimed_by');
            $table->integer('transaction_id')->nullable()->default(null);
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
        Schema::dropIfExists('referral_points');
    }
}
