<?php

namespace App\Modules\Referral\Models;

use App\Modules\Referral\Constant\ReferralSystemConstant;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Referral extends Model {

    //
    protected $table = 'referrals';

    protected $fillable = [
      'referrer_id',
      'user_id',
      'discount_percent',
      'status',
      'referral_system'
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
                'referral_system' => ReferralSystemConstant::NEW
            ]);
        }
    }

    public function getReferral(){
        $referrals = Referral::leftJoin('users as U1','U1.id','=','referrals.referrer_id')
            ->leftJoin('person as P1', 'P1.id', '=', 'U1.person_id')
            ->leftJoin('users as U2','U2.id','=','referrals.user_id')
            ->leftJoin('person as P2', 'P2.id', '=', 'U2.person_id')
            ->select('referrals.referrer_id','referrals.status','referrals.discount_percent','referrals.id','referrals.created_at',
                DB::raw('CONCAT_WS(" ", P1.first_name, NULLIF(P1.middle_name,""), P1.last_name) AS referrer'),
                DB::raw('CONCAT_WS(" ", P2.first_name, NULLIF(P2.middle_name,""), P2.last_name) AS client_name')
            );


        return $referrals;
    }

    public function getReferralCounts($user_id){
        $counts = new Collection();




        $counts->no_of_referrals = $this->referralQuery($user_id)->count();
        $counts->no_of_approved = $this->referralQuery($user_id)->where('status',1)->count();
        $counts->no_of_referral_users = $this->referralQuery($user_id)->distinct()->count('referrer_id');
        $counts->no_of_clients_referred = $this->referralQuery($user_id)->distinct()->count('user_id');
        return $counts;
    }

    public function referralQuery($user_id){
        $user = User::find($user_id);
        $referrals = Referral::query();
        if(!in_array($user->level_id,[1,2])){
            $referrals = $referrals->where('referrer_id',$user_id);
        }
        return $referrals;
    }
}
