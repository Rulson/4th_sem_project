<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('bank_account_name')->nullable();
            $table->string('bank_bsb')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('bank_account_name');
            $table->dropColumn('bank_bsb');
            $table->dropColumn('bank_account_number');
            $table->dropColumn('bank_name');
        });
    }
}
