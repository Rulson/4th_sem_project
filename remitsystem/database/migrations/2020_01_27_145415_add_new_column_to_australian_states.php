<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToAustralianStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('australian_states', function (Blueprint $table) {
            $table->integer('parent_id')->unsigned()->nullable();
            $table->unique( array('name','type','parent_id') );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('australian_states', function (Blueprint $table) {
            $table->dropColumn('australian_state_id');
            $table->dropUnique('australian_states_name_type_parent_id_unique');
        });
    }
}
