<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContactDetailsInApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('contact_email')->nullable()->default(null);
            $table->string('street')->nullable()->default(null);
            $table->string('state')->nullable()->default(null);
            $table->string('postcode')->nullable()->default(null);
            $table->integer('country_id')->nullable()->default(null);
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
            $table->dropColumn('contact_email');
            $table->dropColumn('street');
            $table->dropColumn('state');
            $table->dropColumn('postcode');
            $table->dropColumn('country_id');
            //
        });
    }
}
