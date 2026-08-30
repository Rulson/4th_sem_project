<?php

namespace App\Modules\Transaction\Controllers;

use App\Http\Controllers\BaseController;
use App\Modules\Agent\Models\Agent;
use App\Modules\Agent\Models\AgentAccount;
use App\Modules\Agent\Models\AgentExchangeRate;
use App\Modules\Agent\Models\AgentTransaction;
use App\Modules\Application\Models\Application;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Beneficiary\Models\BeneficiaryBankDetails;
use App\Modules\Coupon\Models\Coupon;
use App\Modules\Coupon\Models\CouponUsage;
use App\Modules\Distributor\Models\DistributorAccount;
use App\Modules\Distributor\Models\DistributorOffice;
use App\Modules\Distributor\Models\DistributorsAssign;
use App\Modules\Distributor\Models\DistributorTransaction;
use App\Modules\Notification\Models\SendNotification;
use App\Modules\Referral\Models\FreeServiceCharge;
use App\Modules\Referral\Models\Referral;
use App\Modules\Referral\Models\ReferralPoints;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\Sender;
use App\Modules\Sender\Models\SenderStatus;
use App\Modules\Settings\Models\Settings;
use App\Modules\Transaction\Constants\FreeServiceChargeConstant;
use App\Modules\Transaction\Constants\TransactionConstant;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionBeneficiary;
use App\Modules\Transaction\Models\TransactionDetails;
use App\Modules\Transaction\Models\TransactionDocument;
use App\Modules\Transaction\Models\TransactionHistory;
use App\Modules\Transaction\Service\TransactionCalculationService;
use App\Modules\User\Models\ExchangeRate;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\User;
use App\Notifications\TransactionNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;

class SendMoneyController extends BaseController
{

    /* Validation rules for sender create and edit */
    protected $rules = [
        'first_name' => 'required|min:2|max:55',
        'last_name' => 'required|min:2|max:55',
        'number' => 'required'
    ];

    protected $sender;
    protected $beneficiary;
    protected $transaction;
    protected $send_notification;
    private $transactionCalculationService;

    function __construct(SendNotification $send_notification,Sender $sender, Beneficiary $beneficiary, TransactionDocument $transactionDocument, Transaction $transaction, TransactionCalculationService $transactionCalculationService)
    {
        $this->sender = $sender;
        $this->send_notification = $send_notification;
        $this->beneficiary = $beneficiary;
        $this->transactionDocument = $transactionDocument;
        $this->transaction = $transaction;
        $this->transactionCalculationService = $transactionCalculationService;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->level_id == 5) {

            //  $sender = Sender::where('added_by',Auth::user()->id)->first();
            $sender = Sender::leftJoin('person', 'person.id', '=', 'senders.person_id')->leftJoin('users', 'users.person_id', '=', 'senders.person_id')->where('users.id', Auth::user()->id)->first();
            if(isset($sender) && $sender->sender_status_id != 2){
                $client_notification = array(
                    'message' => 'Admin needs to confirm your status before you can start sending money !!!',
                    'alert-type' => 'error',
                );
                return redirect()->route('user.dashboard')->with($client_notification);
            }
        }
        // limit calculation
        $remainingLimitAmount = null;
        $totalAmountSentToday = null;
        $totalLimitAmount = TransactionConstant::DAILY_LIMIT;
        if(Auth::user()->level_id == 5 || Auth::user()->level_id == 3)
        {
            if(Auth::user()->level_id == 5){
                $sender_id = Sender::leftJoin('person', 'person.id', '=', 'senders.person_id')->leftJoin('users', 'users.person_id', '=', 'senders.person_id')->where('users.id', Auth::user()->id)->select('senders.id as sender_id')->first()->sender_id;
                $remainingLimitAmount = $this->transactionCalculationService->getRemainingLimitAmountForClient($sender_id);
                $transactionsToday = $this->transactionCalculationService->getTodaysTransactionForClient($sender_id);
                $totalAmountSentToday = $this->transactionCalculationService->totalSentAmount($transactionsToday);
            }
            if(Auth::user()->level_id == 3){
                $remainingLimitAmount = $this->transactionCalculationService->getRemainingLimitAmountForAgent(getAgentId());
                $transactionsToday = $this->transactionCalculationService->getTodaysTransactionForAgent(getAgentId());
                $totalAmountSentToday = $this->transactionCalculationService->totalSentAmount($transactionsToday);
            }
        }
        if (!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3 || Auth::user()->level_id == 5)) {
            abort(403, 'Unauthorized action.');
        }
        $data['exchangerate'] = ExchangeRate::orderBy('id', 'desc')->first();
       $data['exchangerate1'] = ExchangeRate::whereDate('created_at',$data['exchangerate']->created_at)->orderBy('threshold_amount','desc')->pluck('exchange_rate','threshold_amount')->toArray();

        if (Auth::user()->level_id == 5) {
            $data['senders'] = $this->sender->getAll();
        }
        $application = \App\Modules\Application\Models\Application::where('domain_url',request()->getHttpHost())->first();

        $serviceCharge1 = Settings::first();
        $serviceCharge = $serviceCharge1->service_charge;

        if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 ||  Auth::user()->level_id == 3 ||  Auth::user()->level_id == 5){
            if(isset($application) && ($application->service_charge != '' || $application->service_charge != null)){
                $serviceCharge = $application->service_charge;
            }
        }

        if(Auth::user()->level_id == 3){
            $agent_detail = Agent::where('user_id',current_user_id())->first();
            if(isset($agent_detail) && ($agent_detail->agent_service_charge != '' || $agent_detail->agent_service_charge != null)){
                $serviceCharge = $agent_detail->agent_service_charge;
            }
        }

        $referral = false;
        $referral_points = 0;
        $usable_points = 0;
        $points_rate = 10;
        if(Auth::user()->level_id == 5 ){
            $sender_detail = Sender::where('person_id',Auth::user()->person_id)->first();
            if(isset($sender_detail) ){
                if($sender_detail->service_charge != '' || $sender_detail->service_charge != null){
                    $serviceCharge = $sender_detail->service_charge;
                }
                else{
                    $user = User::find($sender_detail->added_by);
                    if($user && $user->level_id == 3){
                        $agent_detail = Agent::where('user_id',$user->id)->first();
                        if(isset($agent_detail) && ($agent_detail->agent_service_charge != '' || $agent_detail->agent_service_charge != null)){
                            $serviceCharge = $agent_detail->agent_service_charge;
                        }
                    }
                }
            }

            $referral_points = (new ReferralPoints)->getRemainingReferralPoints(Auth::user()->id);
            // turn off point referral
//            if($referral_points > 0){
//                $referral = true;
//                $price_equivalent_to_points = $referral_points/$points_rate;
//                $difference = $serviceCharge - $price_equivalent_to_points;
//
//                if($difference < 0){
//                    $usable_points = $serviceCharge * 10;
//                }else{
//                    $usable_points = $price_equivalent_to_points * 10;
//                }
//            }
        }

        $data['beneficiaries'] = $this->beneficiary->getAll();
        $data['payment_types'] = $application->payment_info;
        $freeServiceCharge = FreeServiceCharge::where('referrer_user_id', Auth::user()->id)
            ->where('used',FreeServiceChargeConstant::NOT_USED)
            ->first();
        if($freeServiceCharge){
            $data['can_use_free_service_charge'] = true;
            $serviceCharge = 0.00;
        }else{
            $data['can_use_free_service_charge'] = false;
        }
        //dd($data['payment_types']['bank_transfer']);
        $agentIsRestricted = false;
        if(Auth::user()->level_id == 3){
            $agentObj = Agent::find(getAgentId());
            $agentIsRestricted = $agentObj->is_restricted;
        }

        return view("Transaction::".$this->extra_folder."sendmoney",compact('points_rate','usable_points','referral_points','referral','serviceCharge','serviceCharge1','application', 'remainingLimitAmount','totalAmountSentToday','totalLimitAmount','agentIsRestricted'), $data);
    }

    public function getSendersDropDownDataByAjax(Request $request)
    {


        $senders = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->join('sender_status', 'senders.sender_status_id', '=', 'sender_status.id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->select(['senders.id', 'senders.created_at', 'senders.added_by', 'person.first_name', 'person.last_name', 'person.email', 'senders.sender_status_id', 'phones.number', 'sender_status.name as status', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS full_name')])
            ->orderBy('senders.id', 'desc');
        /*temporary added for loading issue staff and admin case*/
        /* if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2){
             $senders = $senders->leftJoin('users','users.id','=','senders.added_by')->where('users.level_id','!=',3);
         }*/
        /*temporary code*/
        if (Auth::user()->level_id == 3) {
            $senders = $senders->where('senders.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            $senders = $senders->where('senders.added_by', Auth::user()->id);
        }
        if ($keyword = $request->q) {
            //$keyword =  str_replace(' ', '', $keyword);

            $senders = $senders->where(function ($q) use ($keyword) {
                $q->where('phones.number', 'like', '%' . $keyword . '%')
                    ->orWhere('person.email', 'like', '%' . $keyword . '%')
                    ->orWhere(DB::raw('CONCAT_WS(" ", person.first_name, person.last_name)'), 'like', '%' . $keyword . '%');

            });
        }
        $senders = $senders->get();

        $tag = [];
        foreach ($senders as $sender) {
            $tag[] = ['id' => $sender->id, 'text' => $sender->full_name . ' | ' . $sender->email . ' | ' . $sender->number . ' | + ' . get_user_name($sender->added_by)];
        }

        echo json_encode($tag);
    }

    public function fetchSenderServiceCharge(Request $request)
    {
        if (request()->ajax()) {

            $id = $request->get('Id');
            $sender = Sender::where('id', $id)->first();
            if ($sender->service_charge != '' || $sender->service_charge != null) {
                echo json_encode($sender->service_charge);
            } else {
                echo 0;
            }
        }
    }
    public function fetchSenderData(Request $request)
    {
        if (request()->ajax()) {

            $id = $request->get('Id');
            $senderdata = $this->sender->getSenderDetailById($id);
            $senderdata->added_by = get_user_name($senderdata->added_by);
//            $senderdata->dob = standard_date($senderdata->dob);
//            $senderdata->dob = Carbon::createFromFormat('Y-m-d',$senderdata->dob)->format('d/m/Y');
//            $senderdata->dob = $senderdata->dob->format('d/m/Y');
            $today = Carbon::today();
//            $senderdata->expiry_date = standard_date($senderdata->expiry_date);
//            $to = Carbon::createFromFormat('d/m/Y', $senderdata->expiry_date);
            $to = $senderdata->expiry_date;
            $senderdata->diff_in_days = $today->diffInDays($to, false);
            $senderdata->expiry_date = standard_date($senderdata->expiry_date);
            $senderdata->formattedId = format_id($id, "S");
            echo json_encode($senderdata);
        }
    }

    public function fetchBeneficiaryData(Request $request)
    {
        if (request()->ajax()) {
            $id = $request->get('Id');
            $beneficiarydata = $this->beneficiary->getBeneficiaryDetailById($id);
            $beneficiarydata->added_by = get_user_name($beneficiarydata->added_by);
            $beneficiarydata->formattedId = format_id($id, "B");
            echo json_encode($beneficiarydata);
        }
    }

    public function fetchBeneficiaries(Request $request)
    {
        if (request()->ajax()) {
            $id = $request->get('Id');
            $beneficiarydata = $this->beneficiary->getBeneficiaryAccordingToSender($id);
            echo json_encode($beneficiarydata);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Sender::add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->level_id == 5 || Auth::user()->level_id == 3) {
            $this->validate($request, [
                'sending_amount' => 'required | numeric',
                'payment_amount' => 'required | numeric',
                'exchange_rate' => 'required | numeric',
                'sender_id' => 'required',
                'beneficiary_id' => 'required',
                'service_charge' => 'required',
//                'quick_notes' => 'required',
                'payment_type'=>'required',
                'receipt' => 'required | max:10000',
            ]);
        } else {
            $this->validate($request, [
                'sending_amount' => 'required | numeric',
                'payment_amount' => 'required | numeric',
                'exchange_rate' => 'required | numeric',
                'receipt' => 'max:10000',
                'sender_id' => 'required',
                'beneficiary_id' => 'required',
                'service_charge' => 'required',
                'payment_type'=>'required',
                'quick_notes' => 'required'
            ]);
        }
        DB::beginTransaction();
        try {
            $transactionDetail['transaction_date'] = get_today_date();
            $transactionDetail['sending_amount'] = $request->sending_amount;
            $e_rate = ExchangeRate::orderBy('id', 'desc')->first();
            $exchange_rate_list = ExchangeRate::whereDate('created_at',$e_rate->created_at)
                ->orderBy('threshold_amount','desc')
                ->pluck('exchange_rate','threshold_amount')
                ->toArray();
            if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3) {
                $transactionDetail['exchange_rate'] = $request->exchange_rate;
            } else {
                foreach($exchange_rate_list as $key => $rate){
                    $difference = (float)$transactionDetail['sending_amount'] - (float)$key;
                    if($difference >= 0){
                        $transactionDetail['exchange_rate'] = $rate;
                        break;
                    }
                }
            }
           // dd($transactionDetail['exchange_rate']);

//            if (Auth::user()->level_id == 5) {
//                $transactionDetail['exchange_rate'] = $e_rate->exchange_rate;
//            }
//            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3) {
//                $transactionDetail['exchange_rate'] = $request->exchange_rate;
//            }
            $transactionDetail['payment_amount'] = $request->payment_amount;
            $transactionDetail['purpose_of_transfer'] = 'Family or Friend Support';
            $transactionDetail['payment_type'] = $request->payment_type;
            $application = \App\Modules\Application\Models\Application::where('domain_url',request()->getHttpHost())->first();

            $serviceCharge1 = Settings::first();
            $serviceCharge = $serviceCharge1->service_charge;

            if(isset($application) && ($application->service_charge != '' || $application->service_charge != null)){
                $serviceCharge = $application->service_charge;
            }

            if (Auth::user()->level_id == 5) {
                $sender_detail = Sender::where('person_id',Auth::user()->person_id)->first();
                if(isset($sender_detail) ){
                    if($sender_detail->service_charge != '' || $sender_detail->service_charge != null){

                        $serviceCharge = $sender_detail->service_charge;
                    }
                    else{
                        $user = User::find($sender_detail->added_by);
                        if($user && $user->level_id == 3){
                            $agent_detail = Agent::where('user_id',$user->id)->first();
                            if(isset($agent_detail) && ($agent_detail->agent_service_charge != '' || $agent_detail->agent_service_charge != null)){
                                $serviceCharge = $agent_detail->agent_service_charge;
                            }
                        }
                    }
                }
            }
            //referral check
            if(!isset($request->coupon_code) && isset($request->referral_discount) && $request->referral_discount == 1){
                $referral_points = (new ReferralPoints)->getRemainingReferralPoints(Auth::user()->id);
                $points_rate = 10;
                if($referral_points >0) {
                    $price_equivalent_to_points = ($referral_points / $points_rate);
                    $difference = $serviceCharge - $price_equivalent_to_points;

                    if($difference < 0){
                        $new_service_charge = 0;
                        $points_to_reduce = $serviceCharge * $points_rate;
                    }else{

                        $new_service_charge = $difference;
                        $points_to_reduce = $price_equivalent_to_points * $points_rate;
                    }

                    $referral_points_instance = ReferralPoints::create([
                        'date' => Carbon::now(),
                        'points' => -$points_to_reduce,
                        'claimed_by' => Auth::user()->id,
                        'description' => ''
                    ]);
                    $serviceCharge = $new_service_charge;
                }
            }
            //coupon check
            if(!isset($request->referral_discount) && isset($request->coupon_code) && !empty($request->coupon_code)) {

                $coupon = Coupon::where('code','=',$request->coupon_code)
                    ->where('start_date','<=',date('Y-m-d H:i:s'))
                    ->where(function($q){
                        $q->where('end_date','=',null)
                            ->orWhere('end_date','>=',date('Y-m-d H:i:s'));
                    })
                    ->where(function($q1){
                        $q1->where('application_id','=',0)
                            ->orWhere('application_id','=',getAppDetailsForWeb()->id);
                    })
                    ->where(function($q2){
                        $q2->where('user_type','=',0)
                            ->orWhere(function ($q3){
                                $q3->where('user_type','=',1)
                                    ->where('start_date','>',Auth::user()->created_at);
                            })->orWhere(function ($q4){
                                $q4->where('user_type','=',2)
                                    ->where('start_date','<=',Auth::user()->created_at);
                            });
                    })
                    ->where('published' , 1)
                    ->first();
                if($coupon){
                    $total_coupon_usages = CouponUsage::where('coupon_id', '=', $coupon->id)->get();
                    if (($coupon->uses_total != 0 && ($total_coupon_usages->count() < $coupon->uses_total)) || $coupon->uses_total == 0) {
                        $coupon_usage = CouponUsage::where('coupon_id','=',$coupon->id)->where('user_id','=',Auth::user()->id)->first();
                        if(!$coupon_usage){
                            if($coupon->discount_unit == 'f') {
                                $serviceCharge = $serviceCharge - $coupon->discount_value;
                            } else {
                                $serviceCharge = $serviceCharge - (($serviceCharge * $coupon->discount_value)/100);
                            }
                            if($serviceCharge < 0) $serviceCharge = 0;
                        }
                    }
                }
            }

            $freeServiceCharge = null;
            if (Auth::user()->level_id == 5) {
                $transactionDetail['service_charge'] = "".$serviceCharge;
                // new referral discount logic
                $freeServiceCharge = FreeServiceCharge::where('referrer_user_id',Auth::user()->id)
                    ->where('used',FreeServiceChargeConstant::NOT_USED)
                    ->first();
                if($freeServiceCharge) {
                    $transactionDetail['service_charge'] = 0;
                }
            }
            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3) {
                $transactionDetail['service_charge'] = $request->service_charge;
            }

            //  $transactionDetail['total_to_pay'] = number_format(($request->sending_amount + $transactionDetail['service_charge']),2);
            $transactionDetail['total_to_pay'] = "" . ($request->sending_amount + $transactionDetail['service_charge']);
            $transactionDetail['staff_notes'] = $request->staff_notes;
            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3) {
                $transactionDetail['admin_staff_notes'] = $request->quick_notes;
            }
            $transactionDetail['cost_rate'] = $e_rate->cost_rate;
            $transactionDetails = TransactionDetails::create($transactionDetail);
            $transaction['transaction_details_id'] = $transactionDetails->transaction_details_id;
            $transaction['beneficiary_id'] = $request->beneficiary_id;
            $transaction['sender_id'] = $request->sender_id;
            $transaction['added_by'] = Auth::user()->id;
            $transaction['transaction_status_id'] = 14;

            $sender = Sender::find($request->sender_id);
            $senderAddress = PersonAddress::where('person_id', $sender->person_id)->first();
            $senderPhone = PersonPhone::where('person_id', $sender->person_id)->first();

            $beneficiary = Beneficiary::find($request->beneficiary_id);
            $beneficiaryAddress = PersonAddress::where('person_id', $beneficiary->person_id)->first();
            $beneficiaryPhone = PersonPhone::where('person_id', $beneficiary->person_id)->first();

            $transaction['sender_addresses_id'] = $senderAddress->address_id;
            $transaction['sender_phones_id'] = $senderPhone->phones_id;
            $transaction['beneficiary_phones_id'] = $beneficiaryPhone->phones_id;
            $transaction['beneficiary_addresses_id'] = $beneficiaryAddress->address_id;
            $sender_identification_id = Identification::where('senders_id', $request->sender_id)->first();


            $transaction['sender_identification_id'] = $sender_identification_id->identification_id;

            $beneficiaryBankDetail = BeneficiaryBankDetails::where('beneficiaries_beneficiary_id', $beneficiary->beneficiary_id)->where('current', 1)->first();

            $transaction['beneficiaries_bank_details_id'] = $beneficiaryBankDetail->bank_details_id;
            $transaction['pickup_district'] = $request->pickup_district;

            $trans = Transaction::create($transaction);
            if($freeServiceCharge){
                $freeServiceCharge->used = FreeServiceChargeConstant::USED;
                $freeServiceCharge->redeemed_transaction_id = $trans->id;
                $freeServiceCharge->save();
            }

            if(isset($request->coupon_code) && !empty($request->coupon_code) && empty($coupon_usage)) {
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => Auth::user()->id,
                    'transaction_id' => $trans->id
                ]);
            }
            //update transaction_id to referral point
            if(!isset($request->coupon_code) && isset($request->referral_discount) && $request->referral_discount == 1){
                ReferralPoints::where('id',$referral_points_instance->id)->update([
                    'transaction_id' => $trans->id,
                    'description' => 'Points redeemed',
                ]);
            }
            $receipt = $this->transactionDocument->multipleReceiptUpload($request->all(), $trans->id);
            $rate = ExchangeRate::orderBy('id', 'desc')->first();
            $application = Application::where('domain_url',request()->getHttpHost())->first();
            if ($request->agent_id) {
                $commission = ($transactionDetail['sending_amount'] * ($rate->agent_rate - $transactionDetail['exchange_rate'])) / $rate->agent_rate;
                $agent_exchange_rate = Agent::leftJoin('agent_exchange_rate','agent_exchange_rate.id','=','agents.agent_exchange_rate_id')->where('agents.id',$request->agent_id)->select('agent_exchange_rate.less_than_service_charge')->first();

                $agentTran = AgentTransaction::create([
                    'transactions_id' => $trans->id,
                    'agents_id' => $request->agent_id,
                    'total_commission' => "" . (round($commission, 2) + ($transactionDetail['service_charge'] - $agent_exchange_rate->less_than_service_charge)),
                    'exchange_rate' => $rate->agent_rate
                ]);
                AgentAccount::create([
                    'agent_transactions_id' => $agentTran->id,
                    'agent_payments_id' => null,
                    'created_at' => get_today_date()
                ]);
            }
            if (Auth::user()->level_id == 3) {
                $commission1 = ($transactionDetail['sending_amount'] * ($rate->agent_rate - $transactionDetail['exchange_rate'])) / $rate->agent_rate;
                $agent_exchange_rate = Agent::leftJoin('agent_exchange_rate','agent_exchange_rate.id','=','agents.agent_exchange_rate_id')->where('agents.id',getAgentId())->select('agent_exchange_rate.less_than_service_charge')->first();

                $agentTran = AgentTransaction::create([
                    'transactions_id' => $trans->id,
                    'agents_id' => getAgentId(),
                    'total_commission' => "" . (round($commission1, 2) + ($transactionDetail['service_charge'] - $agent_exchange_rate->less_than_service_charge)),
                    'exchange_rate' => $rate->agent_rate
                ]);
                AgentAccount::create([
                    'agent_transactions_id' => $agentTran->id,
                    'agent_payments_id' => null,
                    'created_at' => get_today_date()
                ]);
            }

            if(Auth::user()->level_id == 5){
                $sender = Sender::find($request->sender_id);
//                $agent = getAgentIdByUserId($application->agent_id);
                $agent = getAgentIdByUserId($sender->added_by);
                if(!$agent) {
                    $agent = getAgentIdByUserId($application->agent_id);
                }
                $commission1 = ($transactionDetail['sending_amount'] * ($rate->agent_rate - $transactionDetail['exchange_rate'])) / $rate->agent_rate;
                $agent_exchange_rate = Agent::leftJoin('agent_exchange_rate','agent_exchange_rate.id','=','agents.agent_exchange_rate_id')->where('agents.id',$agent)->select('agent_exchange_rate.less_than_service_charge')->first();
                $agentTran = AgentTransaction::create([
                    'transactions_id' => $trans->id,
                    'agents_id' => $agent,
                    'total_commission' => "" . (round($commission1, 2) + ($transactionDetail['service_charge'] - $agent_exchange_rate->less_than_service_charge)),
                    'exchange_rate' => $rate->agent_rate
                ]);
                AgentAccount::create([
                    'agent_transactions_id' => $agentTran->id,
                    'agent_payments_id' => null,
                    'created_at' => get_today_date()
                ]);
            }
            if ($request->distributor_id) {
                $distributor_office_ide = DistributorOffice::where('companies_id', $request->distributor_id)->first();
                DistributorsAssign::create([
                    'transactions_id' => $trans->id,
                    'distributor_office_id' => $distributor_office_ide->id,
                    'amount' => $transactionDetail['sending_amount'],
                ]);

                $distributorTransaction['transaction_id'] = $trans->id;
                $distributorTransaction['distributor_office_id'] = $distributor_office_ide->id;
                $tra = DistributorTransaction::create($distributorTransaction);
                $distributors_accounts = new DistributorAccount();
                $distributors_accounts->distributor_transactions_id = $tra->id;
                $distributors_accounts->created_at = standard_date($trans->created_at);
                $distributors_accounts->save();

            }
            if ($trans) {

                $tran_ben['transaction_id'] = $trans->id;
                $beneficiary_detail = getBeneficiaryDetails($trans->beneficiary_id);
                $tran_ben['name'] = $beneficiary_detail->first_name.' '.$beneficiary_detail->last_name;
                $tran_ben['address'] = $beneficiary_detail->street.' '.$beneficiary_detail->suburb.' '.$beneficiary_detail->post_code.' '.$beneficiary_detail->state.' '.$beneficiary_detail->country;
                $tran_ben['phone_number'] = $beneficiary_detail->number;
                $tran_ben['bank_name'] = $beneficiary_detail->bank_name;
                $tran_ben['account_number'] = $beneficiary_detail->account_no;
                $tran_ben['branch_name'] = $beneficiary_detail->bsb;
                $tran_ben['account_name'] = $beneficiary_detail->account_name;
                $tran_ben['pickup_district'] = $request->pickup_district;
                TransactionBeneficiary::create($tran_ben);
                TransactionHistory::create(['transaction_id'=>$trans->id,'status'=>'Order Placed']);
                if (isset($request->email_confirmation)) {

                    $bank = Settings::first();
                    $account_name = $bank->account_name;
                    $account_no = $bank->account_no;
                    $bsb = $bank->bsb;
                    $bank_name = $bank->bank_name;

                    $email_template = getEmailTemplate('type','sendmoney',$application);

                    if($email_template){
                        if ($transactionDetail['payment_type'] == 'Bank Transfer') {
                            $beneficiary_detail = '<br> Beneficiary Details: <br> Name : ' . getBeneficiaryName($beneficiary->beneficiary_id) . ' <br>  Phone : ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->number . ' <br> Payment Amount : NPR ' . $request->payment_amount . ' <br> Account Name : ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->account_name . ' <br> Account No : A/C ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->account_no . ' <br> Branch : ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->bsb . ' <br> Bank Name : ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->bank_name . ' <br>';

                        } else {
                            $beneficiary_detail ='<br> Beneficiary Details: <br> Name : ' . getBeneficiaryName($beneficiary->beneficiary_id) . ' <br>  Phone : ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->number . ' <br> Payment Amount : NPR ' . $request->payment_amount . ' <br> Pickup Place : ' . $trans->pickup_district . ' <br>';
                        }
                        $subject = $email_template->subject;
                        $body = $email_template->message;
                        $data_array_parse = array(
                            'FULL_NAME'  => getSenderName($sender->id),
                            'SENDER_NAME'  => getSenderName($sender->id),
                            'TRANSACTION_ID'  => $trans->id,
                            'BENEFICIARY_DETAIL'  => $beneficiary_detail,
                        );
                        $data_array_parse = format_template_array($application,$data_array_parse);
                        $subject = parseTemplate($subject,$data_array_parse);
                        $body = parseTemplate($body,$data_array_parse);
                        $view = 'EmailTemplate::Email/email';

                    }
                    else{
                        $subject = 'Transaction Created';
                        if ($transactionDetail['payment_type'] == 'Bank Transfer') {
                            $body = 'Thank you for sending money with us. This mail is confirmation that we have received  your order.<br> Order Id : ' . $trans->id . ' <br> Beneficiary Details: <br> Name : ' . getBeneficiaryName($beneficiary->beneficiary_id) . ' <br>  Phone : ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->number . ' <br> Payment Amount : NPR ' . $request->payment_amount . ' <br> Account Name : ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->account_name . ' <br> Account No : A/C ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->account_no . ' <br> Branch : ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->bsb . ' <br> Bank Name : ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->bank_name . ' <br> <br> Note : <br> # All amount below NPR 1,00,000 will be local Remitted and Remit Charge will be deducted from the receivable amount. <br>
 # All amount above NPR 1,00,000 will be bank deposit and any charges by Bank like ABBS charge will be deducted from receivable amount. <br>
 # Transaction will be completed within 1-2 working days.<br>';
                        } else {
                            $body = 'Thank you for sending money with us. This mail is confirmation that we have received  your order.<br> Order Id : ' . $trans->id . ' <br> Beneficiary Details: <br> Name : ' . getBeneficiaryName($beneficiary->beneficiary_id) . ' <br>  Phone : ' . getBeneficiaryDetails($beneficiary->beneficiary_id)->number . ' <br> Payment Amount : NPR ' . $request->payment_amount . ' <br> Pickup Place : ' . $trans->pickup_district . ' <br> <br> Note : <br> # All amount below NPR 1,00,000 will be local Remitted and Remit Charge will be deducted from the receivable amount. <br>
 # All amount above NPR 1,00,000 will be bank deposit and any charges by Bank like ABBS charge will be deducted from receivable amount. <br>
 # Transaction will be completed within 1-2 working days.<br>';

                        }
                        $view = 'Transaction::Email/transactionDelivered';
                    }

                }

                $not['title'] = 'New Transaction';
                $not['notification_message'] ='Transaction ('.format_id($trans->id,'T').') created.';

                $this->transaction->createNotification($trans->id,$not);

            }
            DB::commit();

            $param = [
                'to' => strtolower(getSenderDetails($sender->id)->email),
                'toName' => getSenderName($sender->id),
                'body' => $body,
                'subject' => $subject,
                'fromEmail' => env('MAIL_FROM_ADDRESS'),
                'fromName' => $application->name
            ];
            Mail::send($view, $param, function ($message) use ($param) {
                $message->to($param['to'], $param['toName'])
                    ->from($param['fromEmail'], $param['fromName'])
                    ->subject($param['subject']);
            });
            $message = 'Transaction has been added successfully!';
            $alertType = 'success';

            //  flash::success('Transaction has been addded successfully.');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            $message = 'Sorry Transaction could not be saved.';
            $alertType = 'error';

            if (env('APP_DEBUG'))

                $message = $e->getMessage() . 'Line #' . $e->getLine();
            /*Flash::error()->important();
            */

            /* flash($message)->error()->important();*/

        }
        $notification = array(
            'message' => $message,
            'alert-type' => $alertType,
        );

        return redirect()->route('transactions.orders')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['sender'] = $this->sender->getDetails($id);
        $data['sender_roles'] = SenderStatus::orderBy('name', 'asc')->pluck('name', 'id');
        return view('Sender::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /* Additional validations for creating sender */
        $this->rules['email'] = 'email|min:5|max:55|unique:senders,email,' . $id;
        //$this->rules['password'] = 'min:6';
        $this->rules['confirm_password'] = 'required_with:password|same:password';

        $messages = [
            'email.unique' => 'Sender with the same email address already exists.'
        ];

        $this->validate($request, $this->rules, $messages);

        // if validates
        $updated = $this->sender->edit($request->all(), $id);
        if ($updated) {
            Flash::success('Sender has been updated successfully.');
        }
        return redirect()->route('senders.index');
    }

    public function changeStatus($sender_id)
    {
        $sender = new Sender();
        $data = $sender->find($sender_id);
        if ($data->sender_status_id == 2)
            $data->sender_status_id = 3;
        else
            $data->sender_status_id = 2;
        $data->save();
        Flash::success('Sender status has been updated successfully.');

        return redirect()->route('senders.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
