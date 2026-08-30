<?php

namespace App\Modules\Api\Controllers;

use App\Http\Controllers\BaseController;
use App\Modules\Beneficiary\Models\BankDetails;
use App\Modules\Beneficiary\Models\BankList;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Beneficiary\Models\BeneficiaryBankDetails;
use App\Modules\Coupon\Models\Coupon;
use App\Modules\Coupon\Models\CouponUsage;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\Sender;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionDetails;
use App\Modules\Transaction\Models\TransactionDocument;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CouponController extends ApiController
{
    public function index(Request $request)
    {
        if ($request) {
            $banklist = BankList::select('id','name')->get();
            if($banklist){
                return response()->json(['response' => $banklist, 'message' => 'Success', 'status' => 200]);
            } else {
                return response()->json(['message' => 'Bank not found', 'stauts' =>404]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    /*public function verifyCoupon(Request $request){
        if($request){
            $validator = Validator::make($request->all(), [
                'code' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(['response'=>$validator->errors(),'message'=>'Invalid entry.','status'=>422]);
            }
            $coupon = Coupon::where('code','=',$request->code)->where('start_date','<=',date('Y-m-d H:i:s'))->where('end_date','>=',date('Y-m-d H:i:s'))->first();
            if($coupon){
                $coupon_usages = CouponUsage::where('coupon_id','=',$coupon->id)->get();
                if($coupon_usages->count() <= $coupon->total_uses){
                    $access_token = $request->header('X-Access-Token');
                    $user = User::where('api_token', $access_token)->where('level_id',5)->first();
                    $coupon_usage = CouponUsage::where('coupon_id','=',$coupon->id)->where('user_id','=',$user->id)->first();
                    if($coupon_usage){
                        return response()->json(['message' => 'Coupon is already used !!', 'status' => 407]);
                    } else {
                        return response()->json(['response'=>$coupon,'message'=>'success','status'=>200]);
                    }
                } else {
                    return response()->json(['message' => 'Invalid coupon', 'status' => 406]);
                }
            } else {
                return response()->json(['message' => 'Coupon Not Valid', 'status' => 404]);
            }

        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }*/

    public function verifyCoupon(Request $request){
        if($request){
            $validator = Validator::make($request->all(), [
                'code' => 'required',
            ]);
            if($validator->fails()){
                return response()->json(['response'=>$validator->errors(),'message'=>'Invalid entry.','status'=>422]);
            }
            $access_token = $request->header('X-Access-Token');
            $user = User::where('api_token', $access_token)->where('level_id',5)->first();
            $coupon = Coupon::where('code','=',$request->code)
                ->where('start_date','<=',date('Y-m-d H:i:s'))
                ->where(function($q){
                    $q->where('end_date','=',null)
                        ->orWhere('end_date','>=',date('Y-m-d H:i:s'));
                })
                ->where(function($q1){
                    $q1->where('application_id','=',0)
                        ->orWhere('application_id','=',getAppDetails()->id);
                })
                ->where(function($q2) use ($user){
                    $q2->where('user_type','=',0)
                        ->orWhere(function ($q3) use ($user){
                            $q3->where('user_type','=',1)
                                ->where('start_date','>',$user->created_at);
                        })->orWhere(function ($q4) use ($user){
                            $q4->where('user_type','=',2)
                                ->where('start_date','<=',$user->created_at);
                        });
                })
                ->where('published' , 1)
                ->first();
            if($coupon){
                $total_coupon_usages = CouponUsage::where('coupon_id','=',$coupon->id)->get();
                if (($coupon->uses_total != 0 && ($total_coupon_usages->count() < $coupon->uses_total)) || $coupon->uses_total == 0) {
                    $coupon_usage = CouponUsage::where('coupon_id','=',$coupon->id)->where('user_id','=',$user->id)->first();
                    if($coupon_usage){
                        return response()->json(['message' => 'Coupon is already used !!', 'status' => 407]);
                    } else {
                        return response()->json(['response'=>$coupon,'message'=>'success','status'=>200]);
                    }
                } else {
                    return response()->json(['message' => 'Invalid coupon', 'status' => 406]);
                }
            } else {
                return response()->json(['message' => 'Coupon Not Valid', 'status' => 404]);
            }

        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }
}
