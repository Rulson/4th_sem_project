<?php

namespace App\Modules\Api\Controllers;

use App\Modules\Referral\Models\Referral;
use App\Modules\Referral\Models\ReferralPoints;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReferralController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function referralList(Request $request){
        if ($request) {
            $access_token = $request->header('X-Access-Token');
            if($access_token) {
                $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
                if ($user) {
                    $referral_instance = new ReferralPoints();
                    $referral_points_instance = $referral_instance->getReferralPoints($user->id);
                    $referral_points['total_claimed_points'] = $referral_points_instance->total_claimed;
                    $referral_points['total_used_points'] = $referral_points_instance->total_used;
                    $referral_points['total_remaining_points'] = $referral_points_instance->remaining;

                    $referrals = (new Referral)->getReferral()->where('referrer_id',$user->id)->get()->toArray();

                    return response()->json(['response' => $referrals,'referral_points'=>$referral_points, 'referral_code' => $user->referral_code, 'message' => 'Success', 'status' => 200]);

                } else {
                    return response()->json(['message' => 'User not found.', 'status' => 404]);
                }
            } else {
                return response()->json(['message' => 'Access token is not defined', 'status'=>404]);
            }
        }
    }

    public function referralTransactionList(Request $request){
        if ($request) {
            $access_token = $request->header('X-Access-Token');
            if($access_token) {
                $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
                if ($user) {
                    $referral_instance = new ReferralPoints();
                    $referrals = $referral_instance->select('date','points','transaction_id','created_at')->where('claimed_by',$user->id)->orderBy('date','desc')->get()->toArray();
                    return response()->json(['response' => $referrals, 'message' => 'Success', 'status' => 200]);

                }
                else {
                    return response()->json(['message' => 'User not found.', 'status' => 404]);
                }
            } else {
                return response()->json(['message' => 'Access token is not defined', 'status'=>404]);
            }

        }
    }

    public function verifyReferralDiscount(Request $request)
    {
        if ($request) {
            $access_token = $request->header('X-Access-Token');
            $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
          //  $referral_check = Referral::where('referrer_id', $user->id)->where('status', 1)->first();
            $referral_points = (new ReferralPoints)->getRemainingReferralPoints($user->id);
           // $points_rate = 10;
            $points_equivalent_to_service_charge = 100;

            if($referral_points <= $points_equivalent_to_service_charge){
                return response()->json(['response'=> ['my_referral_points' => $referral_points],'message' => 'success !!', 'status' => 200]);

            } else {
                return response()->json(['message' => 'Not enough referral points', 'status' => 404]);
            }

        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

}
