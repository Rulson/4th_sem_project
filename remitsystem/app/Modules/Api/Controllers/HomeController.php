<?php

namespace App\Modules\Api\Controllers;

use App\Exceptions\ExchangeRateIntervalException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\GetExchangeRatesRequest;
use App\Modules\Agent\Models\Agent;
use App\Modules\Application\Models\Application;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Beneficiary\Models\BeneficiaryBankDetails;
use App\Modules\Notification\Models\SendNotification;
use App\Modules\Referral\Models\FreeServiceCharge;
use App\Modules\Referral\Models\Referral;
use App\Modules\Referral\Models\ReferralPoints;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\IdentificationTypes;
use App\Modules\Sender\Models\Sender;
use App\Modules\Settings\Models\Settings;
use App\Modules\Transaction\Constants\FreeServiceChargeConstant;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionDetails;
use App\Modules\Transaction\Models\TransactionDocument;
use App\Modules\User\Models\ExchangeRate;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\User;
use App\Services\ExchangeRateIntervalService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends ApiController
{
    function __construct(Beneficiary $beneficiary,
                         User $user,
            private ExchangeRateIntervalService $exchangeRateIntervalService)
    {
        $this->beneficiary = $beneficiary;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function home(Request $request)
    {
        if ($request) {
            $access_token = $request->header('X-Access-Token');
            if($access_token){
                $total_amount_au = 0;
                $total_amount_np = 0;
                $total_month_au = 0;
                $total_month_np = 0;
                $user = User::where('api_token', $access_token)->where('level_id',5)->first();
                $rate = ExchangeRate::orderBy('id', 'desc')->first();
                $home['today_rate']['date'] = Carbon::today()->format('Y-m-d');

                //$home['today_rate']['rate'] = $rate->exchange_rate;

                $list = ExchangeRate::whereDate('created_at',$rate->created_at)->orderBy('threshold_amount','desc')->pluck('exchange_rate','threshold_amount')->toArray();
                $a = [];
                $i = 0;
                foreach($list as $key=>$value){
                    $a[$i]['sending_amount'] = $key;
                    $a[$i]['rate'] = $value;
                    $i++;
                }
                $home['today_rate']['rates'] = $a;
                $home['today_rate']['rate'] = end($a)['rate'];

                $today_rate_value = (float) end($a)['rate'];

                $yesterday_rate = ExchangeRate::whereDate('created_at', Carbon::yesterday())
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$yesterday_rate) {
                    $yesterday_rate = ExchangeRate::whereDate('created_at', '<', Carbon::today())
                        ->orderBy('created_at', 'desc')
                        ->first();
                }

                $rate_change_percent   = 0;
                $rate_change_direction = 'same';

                if ($yesterday_rate && (float) $yesterday_rate->exchange_rate > 0) {
                    $yesterday_rate_value  = (float) $yesterday_rate->exchange_rate;
                    $signed_change         = (($today_rate_value - $yesterday_rate_value) / $yesterday_rate_value) * 100;
                    $rate_change_percent   = round(abs($signed_change), 2);
                    $rate_change_direction = $signed_change > 0 ? 'up' : ($signed_change < 0 ? 'down' : 'same');
                }

                $home['today_rate']['change_percent']   = $rate_change_percent;
                $home['today_rate']['change_direction']  = $rate_change_direction;   // 'up' | 'down' | 'same'
                $home['today_rate']['yesterday_rate']    = $yesterday_rate?->exchange_rate;

                $application = getAppDetails();
                $home['sliders'] = [];
                $i = 0;
                $slider_data = DB::table('sliders')->where('application_id',$application->id)->limit(5)->get();
                foreach ($slider_data as $data) {
                    $home['sliders'][$i]['name'] = $data->name;
                    $home['sliders'][$i]['url'] = $data->url;
                    $home['sliders'][$i]['image'] = url('/') . '/sliders/' . $data->image;
                    $i++;
                }
                $application = Application::where('package_name',$request->header('X-APP-PKG'))->first();
                $sender = Sender::where('person_id',$user->person_id)->first();
                $service_charge = Settings::select('service_charge')->first();
                $serviceCharge = $service_charge->service_charge;
                if(isset($application) && ($application->service_charge != '' || $application->service_charge != null)){
                    $serviceCharge = $application->service_charge;
                }

                if(isset($sender) ){
                    if($sender->service_charge != '' || $sender->service_charge != null){
                        $serviceCharge = $sender->service_charge;
                    }
                    else{
                        $agt = User::find($sender->added_by);
                        if($agt && $agt->level_id == 3){
                            $agent_detail = Agent::where('user_id',$agt->id)->first();
                            if(isset($agent_detail) && ($agent_detail->agent_service_charge != '' || $agent_detail->agent_service_charge != null)){
                                $serviceCharge = $agent_detail->agent_service_charge;
                            }
                        }
                    }
                }

                $home['service_charge'] = $serviceCharge;
                if($user->level_id == 5){
                    $freeServiceCharge = FreeServiceCharge::where('referrer_user_id',$user->id)
                        ->where('used',FreeServiceChargeConstant::NOT_USED)
                        ->first();
                    if($freeServiceCharge){
                        $home['service_charge'] = '0.00';
                    }
                }
                if ($user) {
                    $transactions = $this->getTransaction()->where('transactions.added_by', $user->id)->get();
                    $month_transactions = $this->getTransaction()->where('transactions.added_by', $user->id)->where('transaction_date','>=',Carbon::now()->startOfMonth()->toDateString())->where('transaction_date','<=',Carbon::now()->endOfMonth()->toDateString())->get();
                } else {
                    return response()->json(['message' => 'Token Expired', 'status' => 403]);
                }
                $home['no_of_transaciton'] = !empty($transactions) ? count($transactions) : 0;
                $home['notification'] = [];
                foreach($transactions as $transaction){
                    $total_amount_np += $transaction->paymentAmount;
                    $total_amount_au += $transaction->sendingAmount;
                }
                foreach($month_transactions as $m_transaction){
                    $total_month_np += $m_transaction->paymentAmount;
                    $total_month_au += $m_transaction->sendingAmount;
                }
                $home['this_month_au'] = intval($total_month_au);
                $home['this_month_np'] = intval($total_month_np);
                $home['all_time_np'] = intval($total_amount_np);
                $home['all_time_au'] = intval($total_amount_au);

                $bank_account_name = 'Nepal Paisa';
                $bank_details = [
                    'Account Name' => $application->bank_account_name,
                    'BSB' => $application->bank_bsb,
                    'Account Number' => $application->bank_account_number,
                    'Bank Name' => $application->bank_name,
                    'Note' => $application->bank_notes
                ];
                if($request->route()->getPrefix() == 'api') {
                    $bank_details['Note'] = [
                        '👉 We do bank deposit above 1 lakh (Any ABSS Charges will Be deducted from receiver amount)and paid within 1-3 working days.',
                        '👉 We do Local Remit below 1 Lakh (Remit charges will applies) and paid within next day.'
                    ];
                }
                $home['our_bank'] = $bank_details;

                $about_us = [
                    'header' => $application->description,
                    'address' => $application->state.', '.$application->street.', '.$application->postcode.', '.get_country($application->country_id),
                    'email' => $application->email,
                    'phone' => $application->phone_number,
                ];
                $home['about_us'] = $about_us;

                $months_date = Carbon::now()->subDays(7);
                $dates = $this->generateDateRange($months_date,Carbon::now());;
                $exchange_rate = [];
                foreach($dates as $date){
                    if(ExchangeRate::select('exchange_rate')->where('created_at',$date)->first()){
                        $exchange_rate[date('d M',strtotime($date))] = ExchangeRate::select('exchange_rate')->where('created_at',$date)->first()->exchange_rate;
                    } else {
                        $exchange_rate[date('d M',strtotime($date))] = ExchangeRate::select('exchange_rate')->where('created_at','<',$date)->latest('created_at')->first()->exchange_rate;
                    }
                }
                $home['exchange_rate'] = $exchange_rate;
                $home['id_type'] = IdentificationTypes::select('id','name')->get();
                $home['notices'] = $application->notices;

                $referral_points = (new ReferralPoints)->getReferralPoints($user->id);
                $home['referral_points']['total_claimed_points'] = $referral_points->total_claimed;
                $home['referral_points']['total_used_points'] = $referral_points->total_used;
                $home['referral_points']['total_remaining_points'] = $referral_points->remaining;
		$home['referralPoints']['totalRemainingPoints'] = $referral_points->remaining;
		$pt = $application->payment_info;
		$er = $home['today_rate']['rate'];
		if (isset($pt['local_remit'])) {
			if (!empty($pt['local_remit']['max'])) $pt['local_remit']['max'] = ($pt['local_remit']['max']/$er)+$serviceCharge;
			if (!empty($pt['local_remit']['min'])) $pt['local_remit']['min'] = ($pt['local_remit']['min']/$er)+$serviceCharge;
		}
		if (!empty($pt['bank_transfer'])) {
			if (!empty($pt['bank_transfer']['max'])) $pt['bank_transfer']['max'] = ($pt['bank_transfer']['max']/$er)+$serviceCharge;
			if (!empty($pt['bank_transfer']['min'])) $pt['bank_transfer']['min'] = ($pt['bank_transfer']['min']/$er)+$serviceCharge;
		}
                $home['payment_types'] = $application->payment_info;

               // $home['referral_discount'] = 0; //remove this later

                return response()->json(['response' => $home, 'message' => 'Success', 'status' => 200]);
            } else {
                return response()->json(['message' => 'Access token is not set', 'status' => 404]);
            }

        }

        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    public function profile(Request $request)
    {
        if ($request) {
            $access_token = $request->header('X-Access-Token');
            if($access_token) {
                $user = User::where('api_token', $access_token)->where('level_id',5)->first();
                if ($user) {
                    $transaction = $this->getTransaction()->where('transactions.added_by', $user->id)->count();
                    $successful_transaction = $this->getTransaction()->where('transactions.added_by', $user->id)->where('transactions.transaction_status_id',10)->count();

                    $user_details = $this->user->getDetails($user->id);
                    $user_details->image =  url('/') . '/identification/' . $user_details->image;
                    $user_details->image1 =  url('/') . '/identification/' . $user_details->image1;
                    $profie = $user_details;
                    $profie['total_transaction_count'] = $transaction;
                    $profie['successful_transaction_count'] = $successful_transaction;
                    $user_details = $user_details->toArray();
                    $user_details['identification_types_id'] = (string) $user_details['identification_types_id'];
                    return response()->json(['response' => $user_details, 'message' => 'Success', 'status' => 200]);
                } else {
                    return response()->json(['message' => 'User not found', 'status' => 404]);
                }
            } else {
                return response()->json(['message' => 'Access token is not set', 'status' => 404]);
            }

        }
    }

    public function about_us(Request $request){
        $pkg_name = $request->header('X-APP-PKG');
        $application = Application::where('package_name',$pkg_name)->first();
        $about_us = [
            'header' => strip_tags($application->description),//'We are who provides the best quality services and customer cate for our customers and potential customers.',
            'address' => $application->street.', '.$application->state.', '.get_country($application->country_id).' '.$application->postcode,//'14 Station Street Kogarah NSW 2217, Australia',
            'email' => $application->email,
            'phone' => $application->phone_number,
            'viber' => $application->viber_info,
            'terms_conditons' => getAppDetailsGeneral()->terms_and_conditions,
        ];
        return response()->json(['response'=>$about_us,'message'=>'success','status'=>200]);
    }

    public function pushNotification(Request $request){
        if ($request) {
            $pkg_name = $request->header('X-APP-PKG');
            if($pkg_name){
                $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'url' => 'sometimes|nullable|url',
                    'message' => 'required',
                    'channel_name' => 'required',
                ]);

                if($validator->fails()){
                    return response()->json(['response' => $validator->errors(), 'message'=>'Invalid entry.','status'=>422]);
                }
                $application = Application::where('package_name',$pkg_name)->first();
                if ($application) {
                    $message = json_decode(_sendPushNotification($request->title,$request->message,$request->url,'','/topics/'.$request->channel_name,$application->firebase_key),true);
                    return response()->json(['message' => $message, 'status' => 200]);
                } else {
                    return response()->json(['message' => 'User not found', 'status' => 404]);
                }
            } else {
                return response()->json(['message' => 'Access token is not set', 'status' => 404]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    public function socialMediaData(Request $request){
        if ($request) {
            $pkg_name = $request->header('X-APP-PKG');
            if($pkg_name){
                $application = Application::where('package_name',$pkg_name)->first();
                $details = [
                    'facebook_username' => $application->facebook_info,
                    'viber_number' => $application->viber_info,
                    'whatsapp_number' => $application->whatsapp_info,
                    'call_number' => $application->call_info,
                ];
                return response()->json(['response'=>$details,'message'=>'success','status'=>200]);
            }else{
                return response()->json(['response'=>'Failed','status'=>403]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    public function getExchangeRates(GetExchangeRatesRequest $request){
        try{
            $exchangeRates = $this->exchangeRateIntervalService->getExchangeRateByInterval($request->interval);
            return response()->json([
                'data' => $exchangeRates,
                'message' => 'Exchange rates retrieved for the interval ' . $request->interval,
            ]);
        }catch (ExchangeRateIntervalException $e){
            \Log::error("Something went wrong while fetching the exchange rates: " . $e->getMessage());
            return response()->json(['status' => 500, 'message' => 'Something went Wrong ! please try again later'], 500);
        }
    }
}
