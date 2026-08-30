<?php

use App\Modules\Referral\Constant\ReferralSystemConstant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferralSystemColumnInReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            Schema::table('referrals', function (Blueprint $table) {
                $table->tinyInteger('referral_system')->default(ReferralSystemConstant::OLD)->comment('0: Old system, 1: New system');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
