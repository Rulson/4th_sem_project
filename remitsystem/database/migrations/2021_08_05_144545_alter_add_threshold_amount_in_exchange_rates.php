<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddThresholdAmountInExchangeRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->decimal('threshold_amount',10,2)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->dropColumn(['threshold_amount']);
        });
    }
}
