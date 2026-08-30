<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('contact_person')->nullable();
            $table->string('designation')->nullable();
            $table->string('company_name')->nullable();
            $table->string('suburb')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('contact_person');
            $table->dropColumn('designation');
            $table->dropColumn('company_name');
            $table->dropColumn('suburb');
        });
    }
};
