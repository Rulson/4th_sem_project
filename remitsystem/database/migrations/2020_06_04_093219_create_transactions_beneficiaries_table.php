<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions_beneficiaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('transaction_id');
            $table->string('name');
            $table->string('address');
            $table->string('phone_number');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('pickup_district')->nullable();
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
        Schema::dropIfExists('transactions_beneficiaries');
    }
}
