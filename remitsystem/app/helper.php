<?php

use App\Modules\Agent\Models\Agent;
use App\Modules\Agent\Models\AgentAccount;
use App\Modules\Agent\Models\AgentTransaction;
use App\Modules\Application\Models\Application;
use App\Modules\Distributor\Models\DistributorAccount;
use App\Modules\Sender\Models\Sender;
use App\Modules\Transaction\Models\NoteAssign;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionStatus;
use App\Modules\User\Models\AusStates;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

function show_404()
{
    App()->abort('404');
}

function current_user_id()
{
    return \Auth::user()->id;
}

function insert_dateformat($date)
{
    if (empty($date)) {
        return null;
    }

    $formats = ['d/m/Y', 'm-d-Y', 'Y-m-d'];

    foreach ($formats as $format) {
        try {
            return Carbon::createFromFormat($format, $date)->format('Y-m-d');
        } catch (\Exception $e) {
            continue;
        }
    }

}

function format_date($date)
{
    if ($date == '' || $date == null || $date == '0000-00-00')
        return '';
    $formatted_date = date('d-M-Y', strtotime($date));
    return $formatted_date;
}

function format_date_order_page($date)
{
    if ($date == '' || $date == null || $date == '0000-00-00')
        return '';
    $date = (string) $date;
    $formatted_date =  str_replace("/", ".", $date);
    return $formatted_date;
}

function getDistributorOfficeName($company_id)
{
    $company = \App\Modules\Distributor\Models\Company::where('id', $company_id)->select('companies.company_name')->first();
    if (isset($company)) {
        return $company->company_name;
    }
}

function getStatusName($status_id){
    $status = DB::table('status')->where('id',$status_id)->first();
    return $status->name;
}

function getIdTypeName($id)
{
    $name = \App\Modules\Sender\Models\IdentificationTypes::where('id', $id)->first();
    if($name) {
        return $name->name;
    } else {
        return null;
    }
}

function getBeneficiaryDetails($beneficiary_id)
{
    $detail = \App\Modules\Beneficiary\Models\Beneficiary::join('person', 'person.id', '=', 'beneficiaries.person_id')
        ->join('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
        ->join('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
        ->join('person_address', 'person_address.person_id', '=', 'person.id')
        ->join('addresses', 'addresses.id', '=', 'person_address.address_id')
        ->leftJoin('country_list', 'country_list.id', '=', 'addresses.country_list_id')
        ->join('person_phones', 'person_phones.person_id', '=', 'person.id')
        ->join('phones', 'phones.id', '=', 'person_phones.phones_id')
        ->select('person.*', 'addresses.suburb', 'country_list.name as country', 'addresses.street','addresses.state', 'addresses.postcode', 'phones.number', 'bank_details.account_name', 'bank_details.account_no', 'bank_details.bsb', 'bank_details.bank_name')
        ->where('beneficiaries.beneficiary_id', $beneficiary_id)->first();

    return $detail;
}

function getSenderDetails($id)
{
    return Sender::join('person', 'person.id', '=', 'senders.person_id')
        ->join('sender_status', 'senders.sender_status_id', '=', 'sender_status.id')
        ->leftJoin('person_phones', function ($q) {
            $q->on('person_phones.person_id', '=', 'person.id');
            $q->where('person_phones.current', '=', 1);
        })->leftJoin('person_address', function ($q) {
            $q->on('person_address.person_id', '=', 'person.id');
            $q->where('person_address.current', '=', 1);
        })
        ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
        ->leftJoin('addresses', 'person_address.address_id', '=', 'addresses.id')
        ->leftJoin('country_list', 'country_list.id', '=', 'addresses.country_list_id')
        ->leftJoin('identifications', 'identifications.senders_id', '=', 'senders.id')
        ->leftJoin('identification_types', 'identification_types.id', '=', 'identifications.identification_types_id')
        ->select(['identifications.expiry_date', 'identifications.issued_by', 'identifications.id_number', 'identification_types.name', 'identifications.identification_types_id AS id_type', 'senders.id as sender_id', 'country_list.name as country', 'senders.added_by', 'person.first_name', 'person.dob', 'person.last_name', 'addresses.*', 'person.email', 'senders.sender_status_id', 'phones.number', 'sender_status.name as status', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
        ->where('senders.id', $id)->first();
}

function format_only_date($date)
{
    $formatted_date = date('d-M-Y', strtotime($date));
    return $formatted_date;
}

function standard_date($date){
    $formatted_date = date('d/m/Y', strtotime($date));
    return $formatted_date;
}

function getAgentAndSender()
{
    $list = [
        'Agents' => 'Agents',
        'Senders' => 'Senders',
    ];
    $applications = \App\Modules\Application\Models\Application::where('published', 1)->get();
    foreach($applications as $application) {
        $list['application-'.$application->id] = $application->name.' Users';
    }
    return $list;
}

function format_id($id = 0, $text = 'A', $zeros = 5)
{
    $id = sprintf("%0" . $zeros . "d", $id);
    return $text . $id;
}

function get_today_date()
{
    $today = Carbon::today();
    return $today->format('Y-m-d');
}

function get_notification_format($datetime)
{
    $difference = Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->diffForHumans();
    return $difference;
}

function get_country($country_id)
{
    $country = \App\Modules\User\Models\CountryList::find($country_id);
    $name = (!empty($country)) ? $country->name : '';
    return $name;
}

function get_user_name($user_id)
{
    $user = new \App\Modules\User\Models\User();
    $user_details = $user->getDetails($user_id);
    if($user_details){
        return $user_details->full_name;
    } else{
        return '';
    }
    return $user_details['full_name'];
}

function get_user_email($user_id){
    $user_email = User::where('id',$user_id)->first()->email;
    return $user_email;
}

function get_users_list(){
    $user = User::join('person', 'person.id', '=', 'users.person_id')
     ->select('users.id','person.first_name','person.last_name',DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name'))->pluck('full_name','users.id')->toArray();
    return $user;
}

function getUserNameById($user_id)
{
    $user = User::join('person', 'person.id', '=', 'users.person_id')
        ->select(DB::raw('CONCAT(person.first_name, " ", person.last_name) as fullName'))
        ->where('users.id', $user_id)->first();
    return $user->fullName;
}

function transactionStatusList(){
    $status = TransactionStatus::pluck('name','id')->toArray();
    return $status;
}

function australiaStateLists()
{
    $aus_states = AusStates::where('type','au_state')->pluck('name','name')->toArray();
    return $aus_states;
}

function BankList(){
    $banks = \App\Modules\Beneficiary\Models\BankList::where('active',1)->select('name')->pluck('name','name')->toArray();
    return $banks;
}

function identificationTypes()
{
    $types = \App\Modules\Sender\Models\IdentificationTypes::pluck('name', 'id')->toArray();
    return $types;
}

function getDistributorStaff()
{
    $distributorOffice = DB::table('distributor_users')->where('distributor_users.user_id', Auth::user()->id)->first();
    $data = User::leftjoin('distributor_users', 'distributor_users.user_id', '=', 'users.id')
        ->leftJoin('person', 'person.id', '=', 'users.person_id')
        ->where('distributor_users.distributor_office_id', $distributorOffice->distributor_office_id)
        ->where('distributor_users.role_id', 2)
        ->get();

    return $data;
}
function _generateAgentShareableLink($agent_id){
    $agentName = getAgentName($agent_id);
    $agentName = urlencode(str_replace(' ', '-', $agentName));
    $agentName = $agentName . '-' . $agent_id;
   return route('register',['agent' => $agentName]);
}
function _generateAgentShareableLinkQR($agent_id): string
{
    $link = _generateAgentShareableLink($agent_id);
    $data = urlencode($link);
    return "https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=$data";
}
function _getAgentIdFromShareableLink($agentShareableLink){
    $array = explode('-', $agentShareableLink);
    $agentId = end($array);
    $agent = Agent::find($agentId);
    if($agent){
        return $agent->id;
    }else{
        return null;
    }
}

function SenderTotalSendingAmount($sender_id)
{
    $data=[];
    $amount = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
        ->where(function ($query) {
            $query->where('transactions.transaction_status_id', 2);
            $query->orWhere('transactions.transaction_status_id', 3);
            $query->orWhere('transactions.transaction_status_id', 4);
            $query->orWhere('transactions.transaction_status_id', 5);
        })->where('transactions.sender_id', $sender_id);
    $data['sendingAmount'] = $amount->sum('transaction_details.sending_amount');
    $data['transaction_count'] = $amount->count();
    return $data;
}

function BeneficiaryTotalReceivedAmount($beneficiary_id)
{
    $amount = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
        ->where(function ($query) {
            $query->where('transactions.transaction_status_id', 2);
            $query->orWhere('transactions.transaction_status_id', 3);
            $query->orWhere('transactions.transaction_status_id', 4);
            $query->orWhere('transactions.transaction_status_id', 5);
        })->where('transactions.beneficiary_id', $beneficiary_id)
        ->sum('transaction_details.payment_amount');
    return $amount;
}

function getAgentCommission($id)
{
    $summary = AgentAccount::
    leftJoin('agent_transactions', 'agent_transactions.id', '=', 'agent_accounts.agent_transactions_id')
        ->leftJoin('transactions', 'transactions.id', '=', 'agent_transactions.transactions_id')
        ->leftJoin('agents', 'agents.id', '=', 'agent_transactions.agents_id')
        ->where(function ($query) {
            $query->where('transactions.transaction_status_id', 1);
            $query->orWhere('transactions.transaction_status_id', 2);
            $query->orWhere('transactions.transaction_status_id', 3);
            $query->orWhere('transactions.transaction_status_id', 4);
            $query->orWhere('transactions.transaction_status_id', 5);
        })
        ->where('agents.id', $id)
        ->groupBy('agent_transactions.agents_id')
        ->sum('agent_transactions.total_commission');
    return $summary;

}

function getAgentCommissionToday($id)
{
    $summary = AgentAccount::
    leftJoin('agent_transactions', 'agent_transactions.id', '=', 'agent_accounts.agent_transactions_id')
        ->leftJoin('transactions', 'transactions.id', '=', 'agent_transactions.transactions_id')
        ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
        ->leftJoin('agents', 'agents.id', '=', 'agent_transactions.agents_id')
        ->where(function ($query) {
            $query->where('transactions.transaction_status_id', 2);
            $query->orWhere('transactions.transaction_status_id', 3);
            $query->orWhere('transactions.transaction_status_id', 4);
            $query->orWhere('transactions.transaction_status_id', 5);
        })
        ->where('agents.id', $id)
        ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')
        ->groupBy('agent_transactions.agents_id')
        ->sum('agent_transactions.total_commission');
    return $summary;
}

function getPaidForTransaction($id)
{
    $summary = DistributorAccount::leftJoin('distributor_transactions', 'distributor_transactions.id', '=', 'distributor_accounts.distributor_transactions_id')
        ->leftJoin('transactions', 'transactions.id', '=', 'distributor_transactions.transaction_id')
        ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
        ->leftJoin('distributor_offices', 'distributor_offices.id', '=', 'distributor_transactions.distributor_office_id')
        ->where(function ($query) {
            $query->where('transactions.transaction_status_id', 3);
            $query->orWhere('transactions.transaction_status_id', 4);
            $query->orWhere('transactions.transaction_status_id', 5);
        })
        ->where('distributor_offices.companies_id', $id)
        ->groupBy('distributor_offices.id')
        ->sum('transaction_details.payment_amount');
    return $summary;
}

function getPaidForTransactionRecent($id)
{
    $summary = DistributorAccount::leftJoin('distributor_transactions', 'distributor_transactions.id', '=', 'distributor_accounts.distributor_transactions_id')
        ->leftJoin('transactions', 'transactions.id', '=', 'distributor_transactions.transaction_id')
        ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
        ->leftJoin('distributor_offices', 'distributor_offices.id', '=', 'distributor_transactions.distributor_office_id')

        ->where('distributor_offices.companies_id', $id)
        ->groupBy('distributor_offices.id')
        ->sum('transaction_details.sending_amount');
    return $summary;
}

function getAdminStaffNote($transaction_id)
{
    $note = \App\Modules\Transaction\Models\Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')->where('transactions.id', $transaction_id)->select('transaction_details.admin_staff_notes')->first();
    return $note->admin_staff_notes;
}

function getAgentName($id)
{
    $user = Agent::join('person', 'person.id', '=', 'agents.person_id')
        ->select(DB::raw('CONCAT(person.first_name, " ", person.last_name) as fullName'))
        ->where('agents.id', $id)->first();
    return $user->fullName;
}
function getAgentOfClientUser($user)
{
    $senderId = $user->getDetails($user->id)->sender_id;
    $sender = Sender::find($senderId);
    $agentUserId = $sender->added_by;
    return getAgentIdByUserId($agentUserId);
}

function getAgentNameByUserId($id)
{
    $user = User::join('person', 'person.id', '=', 'users.person_id')
        ->select(DB::raw('CONCAT(person.first_name, " ", person.last_name) as fullName'))
        ->where('users.id', $id)->first();
    return $user->fullName;
}

function getAgentId()
{
    $agent = Agent::join('person', 'person.id', '=', 'agents.person_id')
        ->select(DB::raw('CONCAT(person.first_name, " ", person.last_name) as fullName'),'agents.id as agent_id')
        ->where('agents.user_id',Auth::user()->id)->first();
        return $agent->agent_id;
}

function getAgentIdByUserId($user_id)
{
    $agent = Agent::join('person', 'person.id', '=', 'agents.person_id')
        ->select(DB::raw('CONCAT(person.first_name, " ", person.last_name) as fullName'),'agents.id as agent_id')
        ->where('agents.user_id',$user_id)->first();
    return  $agent ? $agent->agent_id : null ;
}

function getPersonName($person_id)
{
    $person = Person::select(DB::raw('CONCAT(person.first_name, " ", person.last_name) as fullName'))
        ->where('id', $person_id)->first();
    return $person->fullName;
}

function getAgentPayment($id)
{
    $summary = AgentAccount::leftJoin('agent_payments', 'agent_payments.id', '=', 'agent_accounts.agent_payments_id')
        ->leftJoin('agents', 'agents.id', '=', 'agent_payments.agent_id')
        ->where('agents.id', $id)
        ->orderby('agents.id', 'desc')
        ->sum('agent_payments.amount');

    return $summary;
}

function getApiToken(){
    $token =  Str::random(32);
    return $token;
}


function getIndividualAgentTransaction($agent_id)
{
    $this_month = Carbon::now()->month;
    $a = format_date(Carbon::now()->startOfWeek());
    $startOfWeek = Carbon::parse($a)->format('Y-m-d');
    $b = format_date(Carbon::now()->endOfWeek());
    $endOfWeek = Carbon::parse($b)->format('Y-m-d');

    $transaction = AgentTransaction::leftJoin('transactions', 'transactions.id', '=', 'agent_transactions.transactions_id')
        ->leftJoin('agents', 'agents.id', '=', 'agent_transactions.agents_id')
        ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
        ->where(function ($query) {
            $query->where('transactions.transaction_status_id', 2);
            $query->orWhere('transactions.transaction_status_id', 3);
            $query->orWhere('transactions.transaction_status_id', 4);
            $query->orWhere('transactions.transaction_status_id', 5);
        })
        ->where('agents.id', $agent_id);
    $data['total_count'] = $transaction->count();
    $data['total_sent'] = $transaction->sum('transaction_details.sending_amount');
    $data['this_month'] = $transaction->whereMonth('transaction_details.transaction_date', '=', $this_month)->sum('transaction_details.sending_amount');
    $data['this_month_transaction_count'] = $transaction->whereMonth('transaction_details.transaction_date', '=', $this_month)->count();
    $data['this_week'] = $transaction->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->sum('transaction_details.sending_amount');
    $data['this_week_transaction_count'] = $transaction->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->count();
    $data['today'] = $transaction->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->sum('transaction_details.sending_amount');
    $data['today_transaction_count'] = $transaction->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->count();
    return $data;
}

function getDistributorPayment($id)
{
    $summary = DistributorAccount::leftJoin('distributor_payments', 'distributor_payments.id', '=', 'distributor_accounts.distributor_payments_id')
        ->leftJoin('distributor_offices', 'distributor_offices.companies_id', '=', 'distributor_payments.distributor_company_id')
        ->where('distributor_payments.distributor_company_id', $id)
        ->orderby('distributor_offices.id', 'desc')
        ->sum('distributor_payments.amount');
    return $summary;
}

function getUnreadNotificationCount($transaction_id)
{
    $data = NoteAssign::join('notes', 'notes.id', '=', 'notes_assign.notes_id')->where('notes_assign.is_read', 0)
        ->where('notes_assign.user_id', Auth::user()->id)
        ->where('notes.transactions_id', $transaction_id)
        ->count();
    return $data;

}

function getCompanyName(){
    $companyName = App\Modules\Settings\Models\Settings::first();
      return $companyName->company_name;
}

function _sendPushNotification($title,$message,$url='',$image='',$channel='default',$firebase_key=''){
    $channel_url = $channel;
    $headers = array('Content-Type: application/json','Authorization: key='.$firebase_key);
    if($channel == 'default' || $channel == '') {
        $channel_url = '/topics/notify';
    }
    $fields = array(
        'notification' => array(
            'body'=>$message,
            'title'=>$title
        ),
        'priority'=> 'high',
        'to'=> $channel_url,
        'data'=>array(
            'click_action'=>'FLUTTER_NOTIFICATION_CLICK',
            'status' => 'done',
            'url'=>$url ? $url : '',
            'image'=> $image,
            'alert_body'=>$message,
            'alert_title'=>$title
        )
    );
    return _send_curl_json('https://fcm.googleapis.com/fcm/send',$fields,$headers);
}

function _unsubscribeNotification($firebase_key='',$user_token = '',$channel='default'){
    $headers = array('Content-Type: application/json','Authorization: key='.$firebase_key);

     return _send_curl_json('https://iid.googleapis.com/iid/v1/'.$user_token.'/rel/topics/'.$channel,[],$headers,'DELETE');
}

function _send_curl_json($url = null,$fields = array(),$headers = array(),$type = 'POST',$toArray = false){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    if($type == 'POST'){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
    }

    if($type == 'DELETE'){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_DELETE, true);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    if($toArray) {
        return json_decode($result,true);
    } else {
        return $result;
    }
}

function getApplicationDetail($key, $value){
    $application = \App\Modules\Application\Models\Application::where($key,$value)->first();
    return $application;
}

function getAppDetails(){
    $application = \App\Modules\Application\Models\Application::where('package_name',request()->header('X-APP-PKG'))->first();
    if(!$application){
        $application = \App\Modules\Application\Models\Application::first();
    }
    return $application;
}

function getAppDetailsForWeb(){
    $application = \App\Modules\Application\Models\Application::where('domain_url',request()->getHttpHost())->first();
    if(!$application){
        $application = \App\Modules\Application\Models\Application::first();
    }
    return $application;
}

function getApplicationList(){
    $applications = \App\Modules\Application\Models\Application::pluck('name','id')->toArray();
    return $applications;
}

function getEmailTemplate($key,$value,$application){
    $email_template = \App\Modules\EmailTemplate\Models\EmailTemplate::where($key,'=',$value)->where(function($q) use ($application){
        $q->where('application_id','=',0)
            ->orWhere('application_id','=',$application->id);

    })->first();
    return $email_template;
}

function format_template_array($application,array $data = array())
{
    $login_url = "https://".$application->domain_url."/login";
    $data['APPLICATION_NAME'] = ucfirst($application->name);
    $data['APPLICATION_CONTACT_EMAIL'] = $application->contact_email;
    $data['APPLICATION_CONTACT_NUMBER'] = $application->phone_number;
    $data['APPLICATION_ADDRESS'] = $application->street.','.$application->state.','.$application->postcode.','.get_country($application->country_id);
    $data['LOGIN_URL'] = '<a href="'.$login_url.'">'.$login_url.'</a>';
    $data['COMPANY_NAME'] = ucfirst(getCompanyName());
    return $data;
}

function parseTemplate($email_msg_dtls,$token_array)
{
    $pattern = '[%s]';
    foreach($token_array as $key=>$val){
        $varMap[sprintf($pattern,$key)] = $val;
    }
    //return strtr($email_msg_dtls,$varMap);
    return str_ireplace(array_keys($varMap), array_values($varMap),$email_msg_dtls);
}

function buildTemplate($email_msg_dtls,$application)
{
    $custom_email_msg = "";
    if(!empty($application->playstore_url) || !empty($application->appstore_url)){
        $custom_email_msg .= '<div>';
        $custom_email_msg .= $email_msg_dtls;
        $custom_email_msg .= 'You can also download our application from ';
        if(!empty($application->playstore_url))
        $custom_email_msg .= '<a href="'.$application->playstore_url.'">Google Play Store</a> and ';
        if(!empty($application->appstore_url))
        $custom_email_msg .= '<a href="'.$application->appstore_url.'">App Store</a> and ';
        $custom_email_msg .= 'send money from app easily.';
        $custom_email_msg .= '</div>';
    }

    return $custom_email_msg;
}

function generateReferralCode($application_id,$length=6){

    $characters = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, $charactersLength - 1)];
    }

        $validator = \Validator::make(['referral_code'=>$code], [
        'referral_code' => [
            Rule::unique('users')->where(function ($q) use($application_id,$code) {
                return $q->where('application_id', $application_id)
                    ->where('referral_code', $code);
            }),
        ],
    ]);

    if($validator->fails()){
          return generateReferralCode($application_id);
    }

    return $code;
}

function validateReferralCode($code,$application_id){
    $validator = \Validator::make(['referral_code'=>$code], [
        'referral_code' => [
            Rule::exists('users')->where(function ($q) use($application_id,$code) {

                return $q->where('application_id', $application_id)
                    ->where('referral_code', $code);
            }),
        ]
    ],
        [
            'referral_code.exists' => 'The referral code is invalid.',
        ]);

    if($validator->fails()){
        return $validator->errors();
    }

    return 'true';
}


function getAppDetailsGeneral(): Application{
    return app(\App\Modules\Application\Service\GetApplicationService::class)->getApplication();
}
