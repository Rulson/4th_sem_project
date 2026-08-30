<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDataTypeOfCreatedDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_payments', function (Blueprint $table) {
            $table->string('date')->change();
            $table->date('created_at')->change();
        });

        Schema::table('distributor_accounts', function (Blueprint $table) {
            $table->date('created_at')->change();
        });

        Schema::table('distributor_payments', function (Blueprint $table) {
            $table->date('date')->change();
        });

        Schema::table('identifications', function (Blueprint $table) {
            $table->date('expiry_date')->change();
        });
        Schema::table('persons', function (Blueprint $table) {
            $table->date('dob')->change();
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->date('transaction_date')->change();
        });

        Schema::table('agent_accounts', function (Blueprint $table) {
            $table->date('created_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_payments', function (Blueprint $table) {
            $table->string('date',45)->change();
            $table->string('created_at',45)->change();
        });

        Schema::table('distributor_accounts', function (Blueprint $table) {
            $table->string('created_at')->change();
        });

        Schema::table('distributor_payments', function (Blueprint $table) {
            $table->string('date',45)->change();
        });

        Schema::table('identifications', function (Blueprint $table) {
            $table->string('expiry_date',45)->change();
        });
        Schema::table('persons', function (Blueprint $table) {
            $table->string('dob',45)->change();
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->string('transaction_date',45)->change();
        });

        Schema::table('agent_accounts', function (Blueprint $table) {
            $table->string('created_at',45)->change();
        });
    }
}
