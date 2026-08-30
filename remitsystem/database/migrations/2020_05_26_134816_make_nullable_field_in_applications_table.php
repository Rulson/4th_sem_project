<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeNullableFieldInApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('logo')->nullable()->change();
            $table->string('appstore_url')->nullable()->change();
            $table->string('playstore_url')->nullable()->change();
            $table->string('alert')->nullable()->change();
            $table->string('alert_link')->nullable()->change();
            $table->longText('description')->nullable()->change();
            $table->string('agent_id')->nullable()->change();
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
            $table->string('logo')->nullable(false)->change();
            $table->string('appstore_url')->nullable(false)->change();
            $table->string('playstore_url')->nullable(false)->change();
            $table->string('alert')->nullable(false)->change();
            $table->string('alert_link')->nullable(false)->change();
            $table->longText('description')->nullable(false)->change();
            $table->string('agent_id')->nullable(false)->change();
        });
    }
}
