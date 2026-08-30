<?php

namespace App\Modules\Referral\Models;

use App\Modules\Referral\Constant\ReferralSystemConstant;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferralPoints extends Model {

    //
    protected $table = 'referral_points';

    protected $fillable = [
      'date',
      'points',
      'description',
      'claimed_by',
      'transaction_id'
    ];
    public  $timestamps = true;


    public function add($user_id,$referral_code,$discount_percent){
        $referrer = User::where('referral_code',$referral_code)->first();
     //   dd($referrer->id);
        if(!empty($referrer)){
           $a =  Referral::create([
                'referrer_id' => $referrer->id,
                'user_id' => $user_id,
                'discount_percent' => $discount_percent,
                'status' => 0,
                'referral_system'=>ReferralSystemConstant::NEW
            ]);
        }
    }

    public function getReferralPoints($user_id){
        $claimed = 0;
        $used = 0;
        $remaining = 0;

        $referral_points = ReferralPoints::query();

        $user = User::find($user_id);
        if(!empty($user)) {
            if(!in_array($user->level_id,[1,2])){
                $referral_points = $referral_points->where('claimed_by',$user_id);
            }

            $referral_points = $referral_points->get();
            foreach($referral_points as $r_points){
                if($r_points->points > 0){
                    $claimed += $r_points->points;
                }else{
                    $used += $r_points->points;
                }
            }
            $remaining = $claimed - abs($used);
        }

        $points = new Collection();

        $points->total_claimed = $claimed;
        $points->total_used = abs($used);
        $points->remaining = $remaining;

        return $points ;
    }
    public function getRemainingReferralPoints($user_id){
        $referral_points = ReferralPoints::where('claimed_by',$user_id)->sum('points');
        return $referral_points;
    }
}
