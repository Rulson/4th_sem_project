<?php

namespace App\Modules\Api\Controllers;

use App\Http\Controllers\BaseController;
use App\Modules\Agent\Models\Agent;
use App\Modules\Agent\Models\AgentAccount;
use App\Modules\Agent\Models\AgentTransaction;
use App\Modules\Application\Models\Application;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Beneficiary\Models\BeneficiaryBankDetails;
use App\Modules\Coupon\Models\CouponUsage;
use App\Modules\Distributor\Models\DistributorAccount;
use App\Modules\Distributor\Models\DistributorOffice;
use App\Modules\Distributor\Models\DistributorsAssign;
use App\Modules\Distributor\Models\DistributorTransaction;
use App\Modules\Referral\Models\FreeServiceCharge;
use App\Modules\Referral\Models\Referral;
use App\Modules\Referral\Models\ReferralPoints;
use App\Modules\Sender\Models\Document;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\Sender;
use App\Modules\Settings\Models\Settings;
use App\Modules\Transaction\Constants\FreeServiceChargeConstant;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionBeneficiary;
use App\Modules\Transaction\Models\TransactionDetails;
use App\Modules\Transaction\Models\TransactionDocument;
use App\Modules\Transaction\Models\TransactionHistory;
use App\Modules\User\Models\AusStates;
use App\Modules\User\Models\ExchangeRate;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TransactionController extends ApiController
{
    function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request) {
            $access_token = $request->header('X-Access-Token');
            if($access_token){
                $user = User::where('api_token', $access_token)->where('level_id',5)->first();
                if ($user) {
                    $transactions = Transaction::where('added_by',$user->id)->orderBy('id','DESC')->get();
                    foreach ($transactions as $transaction){
                        $tran_ben = TransactionBeneficiary::where('transaction_id',$transaction->id)->first();
                        if($tran_ben){
                            $tran = $this->getTransactionDetail($tran_ben->transaction_id);
                        } else {
                            $tran = $this->getTransactionList($transaction->id);
                        }
                        $transaction->transaction_id = $tran['transaction_id'];
                        $transaction->transactionDate = $tran->transactionDate;
                        $transaction->sender_name = $tran->sender_name;
                        $transaction->beneficiary_name = $tran->beneficiary_name != null ? $tran->beneficiary_name : 'N/A';
                        $transaction->totalAmount = $tran->totalAmount;
                        $transaction->serviceCharge = $tran->serviceCharge;
                        $transaction->sendingAmount = $tran->sendingAmount;
                        $transaction->paymentAmount = $tran->paymentAmount;
                        $transaction->exchangeRate = $tran->exchangeRate;
                        $transaction->added_by = $tran->added_by;
                        $transaction->addedBy = $tran->addedBy;
                        $transaction->status_id = $tran->status_id;
                        $transaction->companyName = $tran->companyName;
                        $transaction->distributor_office_id = $tran->distributor_office_id;
                        $transaction->assignedDistributorStaff = $tran->assignedDistributorStaff;
                        $transaction->assignedDistributor = $tran->assignedDistributor;
                        $transaction->beneficiary_phone = $tran->beneficiary_phone != null ? $tran->beneficiary_phone : 'N/A';
                        $transaction->account_name = $tran->account_name != null ? $tran->account_name : '';
                        $transaction->account_no = $tran->account_no != null ? $tran->account_no : '';
                        $transaction->bsb = $tran->bsb;
                        $transaction->bank_name = $tran->bank_name != null ?  $tran->bank_name : '';
                        $transaction->sender_id = $tran->sender_id;
                        $transaction->beneficiary_id = $tran->beneficiary_id;
                        $transaction->staff_notes = $tran->staff_notes;
                        $transaction->payment_type = $tran->payment_type;
                        $transaction->pickup_district = $tran->pickup_district != null ? $tran->pickup_district : '';
                        $transaction->status = getStatusName($tran->status_id);
                        $transaction->history = TransactionHistory::where('transaction_id',$transaction->transaction_id)->get();
                        $transaction->referrals = ReferralPoints::select('description','date','points','transaction_id')->where('transaction_id',$transaction->transaction_id)->get();
                        $transaction->coupon_usage = CouponUsage::with('coupon:name,discount_value,discount_unit,id')->where('transaction_id',$transaction->transaction_id)->get();
                    }
                    return response()->json(['response' => $transactions, 'message' => 'Success', 'status' => 200]);
                } else {
                    return response()->json(['message' => 'User not found', 'status' => 404]);
                }
            } else {
                return response()->json(['message' => 'Access token is not set', 'status' => 404]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request) {
            $access_token = $request->header('X-Access-Token');
            if($access_token) {
                $user = User::where('api_token', $access_token)->where('level_id', 5)->first();
                if ($user) {
                    if($user->user_status_id == 2){
                        $validator = Validator::make($request->all(), [
                            'sending_amount' => 'required | numeric',
                            'payment_amount' => 'required | numeric',
                            'exchange_rate' => 'required | numeric',
                            'beneficiary_id' => 'required',
                            'service_charge' => 'required',
                            'payment_type'=>'required',
                            'receipt' => 'max:10000',
                            'receipt1' => 'max:10000',
                        ]);
                        if($validator->fails()){
                            return response()->json(['response' => $validator->errors(), 'message'=>'Invalid entry.','status'=>422]);
                        }
                        if($request->payment_type != 'Bank Transfer' && empty($request->pickup_district)) {
                            return response()->json([ 'message'=>'Please provide pickup district.','status' => 422]);
                        }
                        if(!isset($request->receipt) && !isset($request->receipt1)){
                            return response()->json([ 'message'=>'The receipt field is required.','status' => 422]);
                        }
                        $person_id = $user->person_id;
                        DB::beginTransaction();
                        try {
                            $transactionDetail['transaction_date'] = get_today_date();
                            $transactionDetail['sending_amount'] = (float)$request->sending_amount;
                            $e_rate = ExchangeRate::orderBy('id', 'desc')->first();
                            $exchange_rate_list = ExchangeRate::whereDate('created_at',$e_rate->created_at)
                                ->orderBy('threshold_amount','desc')
                                ->pluck('exchange_rate','threshold_amount')
                                ->toArray();

                            foreach($exchange_rate_list as $key => $rate){
                                $difference = (float)$transactionDetail['sending_amount'] - (float)$key;
                                if($difference >= 0){
                                    $transactionDetail['exchange_rate'] = $rate;
                                    break;
                                }
                            }
//                            if ($user->level_id == 5) {
//                                $transactionDetail['exchange_rate'] = $e_rate->exchange_rate;
//                            }

                            $transactionDetail['purpose_of_transfer'] = 'Family or Friend Support';
                            $transactionDetail['payment_type'] = $request->payment_type;
                            $serviceCharge1 = Settings::first();
                            $agentServiceCharge = $serviceCharge1->service_charge;

                            $agent_id = getAgentOfClientUser($user);
//                            if(str_contains($request->header('X-APP-PKG') ,'nepalpaisa')){
//                                $agent_id = 15;
//                                $nepalpaisa = Agent::where('id',$agent_id)->first()->agent_service_charge;
//                                $serviceCharge = !empty($nepalpaisa) ? $nepalpaisa : $agentServiceCharge;
//                            } elseif (str_contains($request->header('X-APP-PKG') , 'dollarrupiya')){
//                                $agent_id = 17;
//                                $dollarrupiya = Agent::where('id',$agent_id)->first()->agent_service_charge;
//                                $serviceCharge = !empty($dollarrupiya) ? $dollarrupiya : $agentServiceCharge;
//                            } else {
//                                $serviceCharge = $agentServiceCharge;
//                            }


                            $freeServiceCharge = null;
                            if ($user->level_id == 5) {
                                $transactionDetail['service_charge'] = $request['service_charge'];
                                // new referral discount logic
                                $freeServiceCharge = FreeServiceCharge::where('referrer_user_id',$user->id)
                                    ->where('used',FreeServiceChargeConstant::NOT_USED)
                                    ->first();
                                if($freeServiceCharge != null) {
                                    $freeServiceCharge->used = FreeServiceChargeConstant::USED;
                                    $freeServiceCharge->save();
                                    $transactionDetail['service_charge'] = 0;
                                }
                                // end new referral discount logic
                            }


                            $transactionDetail['sending_amount'] = (float)$request->sending_amount;

                            $request->payment_amount =
                                (float)$transactionDetail['sending_amount']
                                * (float)$transactionDetail['exchange_rate'];

                            $transactionDetail['payment_amount'] = $request->payment_amount;

                            $sender = Sender::where('person_id',$person_id)->first();

                            $transactionDetail['total_to_pay'] =
                                (float)$transactionDetail['sending_amount']
                                + (float)$transactionDetail['service_charge'];

                            $transactionDetail['staff_notes'] = $request->staff_notes;
                            $transactionDetail['cost_rate'] = $e_rate->cost_rate;
                            $transactionDetails = TransactionDetails::create($transactionDetail);
                            $transaction['transaction_details_id'] = $transactionDetails->transaction_details_id;
                            $transaction['beneficiary_id'] = $request->beneficiary_id;
                            $transaction['sender_id'] = $sender->id;
                            $transaction['added_by'] = $user->id;
                            $transaction['transaction_status_id'] = 14;

                            $senderAddress = PersonAddress::where('person_id', $sender->person_id)->first();
                            $senderPhone = PersonPhone::where('person_id', $sender->person_id)->first();
                            $beneficiary = Beneficiary::find($request->beneficiary_id);
                            $beneficiaryAddress = PersonAddress::where('person_id', $beneficiary->person_id)->first();
                            $beneficiaryPhone = PersonPhone::where('person_id', $beneficiary->person_id)->first();
                            $transaction['sender_addresses_id'] = $senderAddress->address_id;
                            $transaction['sender_phones_id'] = $senderPhone->phones_id;
                            $transaction['beneficiary_phones_id'] = $beneficiaryPhone->phones_id;
                            $transaction['beneficiary_addresses_id'] = $beneficiaryAddress->address_id;
                            $sender_identification_id = Identification::where('senders_id', $sender->id)->first();


                            $transaction['sender_identification_id'] = $sender_identification_id->identification_id;
                            $beneficiaryBankDetail = BeneficiaryBankDetails::where('beneficiaries_beneficiary_id', $beneficiary->beneficiary_id)->where('current', 1)->first();

                            $transaction['beneficiaries_bank_details_id'] = $beneficiaryBankDetail->bank_details_id;
                            $transaction['pickup_district'] = $request->pickup_district;
                            if(is_numeric($transaction['pickup_district'])) {
                                $aus_states = AusStates::select('id','name')->where('id',$transaction['pickup_district'])->first();
                                $transaction['pickup_district'] = $aus_states->name;
                            }
                            $trans = Transaction::create($transaction);
                            //

                            if($freeServiceCharge != null) {
                                $freeServiceCharge->redeemed_transaction_id = $trans->id;
                                $freeServiceCharge->save();
                            }

                            $rate = ExchangeRate::orderBy('id', 'desc')->first();
                            if(!empty($agent_id)){
                                $commission = ($transactionDetails->sending_amount * ($rate->agent_rate - $transactionDetails->exchange_rate)) / $rate->agent_rate;
                                $agent_exchange_rate = Agent::leftJoin('agent_exchange_rate','agent_exchange_rate.id','=','agents.agent_exchange_rate_id')->where('agents.id',$agent_id)->select('agent_exchange_rate.less_than_service_charge')->first();
                                $agentTran = AgentTransaction::create([
                                    'transactions_id' => $trans->id,
                                    'agents_id' => $agent_id,
                                    'total_commission' => "" . (round($commission, 2) + ($transactionDetails->service_charge - $agent_exchange_rate->less_than_service_charge)),
                                    'exchange_rate' => $rate->agent_rate
                                ]);

                                AgentAccount::create([
                                    'agent_transactions_id' => $agentTran->id,
                                    'agent_payments_id' => null,
                                    'created_at' => get_today_date()
                                ]);
                            }

                            $receipt = $this->receiptUpload($request, $trans->id,$user->id);
                            if ($trans) {
                                // old referral system
//                                if(isset($request->referral_discount)){
//
//                                        ReferralPoints::create([
//                                            'date' => Carbon::now(),
//                                            'points' =>  -($request->usable_points),
//                                         //   'description' => 'Points redeemed for transaction '.format_id($trans->id,'T'),
//                                            'description' => 'Points redeemed',
//                                            'claimed_by' => $user->id,
//                                            'transaction_id' => $trans->id
//                                        ]);
//                                }
                                if(isset($request->coupon_id)){
                                    CouponUsage::create([
                                        'coupon_id'=>$request->coupon_id,
                                        'user_id'=>$user->id,
                                        'transaction_id'=>$trans->id,
                                    ]);
                                }
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
                                $application = Application::where('package_name',$request->header('X-APP-PKG'))->first();

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
                                // }
                                $not['title'] = 'New Transaction';
                                $not['notification_message'] ='Transaction ('.format_id($trans->id,'T').') created.';

                                $this->transaction->createNotification($trans->id,$not);
                                DB::commit();
                                $param = [
                                    'to' => strtolower(getSenderDetails($sender->id)->email),
                                    'toName' => getSenderName($sender->id),
                                    'body' => $body,
                                    'subject' => $subject,
                                    'fromEmail' => $application->email,
                                    'fromName' => $application->name
                                ];
                                Mail::send($view, $param, function ($message) use ($param) {
                                    $message->to($param['to'], $param['toName'])
                                        ->from($param['fromEmail'], $param['fromName'])
                                        ->subject($param['subject']);
                                });
                            }

                        } catch (\Exception $e) {
                            DB::rollback();
                            Log::error($e->getTraceAsString());
                            $message = 'Money could not be sent.';
                                $message = $e->getMessage() . 'Line #' . $e->getLine();
                            return response()->json(['message' => $message, 'status' => 404]);
                        }
                        return response()->json(['message' => 'Transaction request created successfully. The money will be send after verification. Thank you !!!', 'status' => 200]);
                    } else {
                        return response()->json(['message' => 'Your account is not activated or has been suspended.Please contact admin for approval.', 'status' => 205]);
                    }

                } else {
                    return response()->json(['message' => 'User not found.', 'status' => 404]);
                }
            } else {
                return response()->json(['message' => 'Access token is not defined', 'status'=>404]);
            }
        }
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
        $transaction = Transaction::find($id);
        $transactionDetail = TransactionDetails::find($transaction->transaction_details_id);
        $transactionDetail->sending_amount = $request->sending_amount;
        $transactionDetail->exchange_rate = $request->exchange_rate;
        $transactionDetail->payment_amount = $request->payment_amount;
        $transactionDetail->purpose_of_transfer = $request->purpose;
        $transactionDetail->payment_type = $request->payment_type;
        $transactionDetail->service_charge = $request->service_charge;
        $transactionDetail->total_to_pay = "" . ($request->sending_amount + $request->service_charge);
        $transactionDetail->staff_notes = $request->staff_notes;
        $transactionDetail->save();
        return response()->json(['message' => 'Success', 'status' => 200]);
    }

    public function receiptUpload($request,$transaction_id,$user_id){
        $destinationPath = 'TransactionIdentification';
        $uniqueid = uniqid();
        if(isset($_FILES['receipt'])){
            $fileName = date('Y-m-d-H-i-s') . '-' . $uniqueid . '.' . $_FILES['receipt']['name'];
            move_uploaded_file($_FILES['receipt']['tmp_name'],$destinationPath.'/'.$fileName);
            TransactionDocument::create([
                'file_name'=> $fileName,
                'transaction_document_type_id' => 1,
                'transactions_id' => $transaction_id,
                'added_by' => $user_id,
                'created_date' => Carbon::today()
            ]);
            Document::create([
                'type'=>'',
                'user_id' => $user_id,
                'name' => $fileName,
                'shelf_location'=>'',
            ]);
        } else {
            $count = count($_FILES);
            if ($count > 0) {
                foreach ($_FILES as $image) {
                    $fileName = date('Y-m-d-H-i-s') . '-' . $uniqueid . '.' . $image['name'];

                    move_uploaded_file($image['tmp_name'], $destinationPath . '/' . $fileName);
                    TransactionDocument::create([
                        'file_name'=> $fileName,
                        'transaction_document_type_id' => 1,
                        'transactions_id' => $transaction_id,
                        'added_by' => $user_id,
                        'created_date' => Carbon::today()
                    ]);
                    Document::create([
                        'type'=>'',
                        'user_id' => $user_id,
                        'name' => $fileName,
                        'shelf_location'=>'',
                    ]);
                }
            }
        }
    }
}
