<?php

namespace App\Console\Commands;

use App\Modules\Referral\Constant\ReferralSystemConstant;
use App\Modules\Referral\Models\FreeServiceCharge;
use App\Modules\Referral\Models\Referral;
use Illuminate\Console\Command;

class ConvertReferralsToFreeServiceCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:referral-to-free-service-charge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all previous referral to a free service charge';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $referrals = Referral::where('status', 1)
            ->join('users', 'users.id', '=', 'referrals.user_id')
            ->join('users as referrer_users', 'referrer_users.id', '=', 'referrals.referrer_id')
            ->select('referrals.*')
            ->where('referrer_users.level_id',5)
            ->get()
            ->unique('referrer_id');
        foreach ($referrals as $referral){
            if($referral->referral_system === ReferralSystemConstant::NEW){
                continue;
            }
            FreeServiceCharge::create([
                'referrer_user_id' => $referral->referrer_id,
                'referred_user_id' => $referral->user_id,
            ]);
        }
        return true;
    }
}
