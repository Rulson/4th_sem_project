<?php

namespace App\Modules\Transaction\Models;

use App\Modules\Agent\Models\AgentTransaction;
use App\Modules\Application\Constants\TransactionStatusConstant;
use App\Modules\Application\Models\Application;
use App\Modules\Distributor\Models\DistributorTransaction;
use App\Modules\Distributor\Models\DistributorUser;
use App\Modules\Email\Constants\EmailStatusConstant;
use App\Modules\Sender\Models\Sender;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use App\Notifications\TransactionNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class Transaction extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_details_id','transaction_status_id', 'sender_identification_id', 'beneficiary_id', 'sender_id', 'added_by', 'sender_addresses_id', 'beneficiary_addresses_id', 'sender_phones_id', 'beneficiary_phones_id', 'beneficiaries_bank_details_id', 'sender_companies_id','created_at','updated_at', 'pickup_district','is_verified'
    ];


    public function getAll($status_id)
    {
        $transaction = [];
        $transactionAndSender = Transaction::leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
            ->leftJoin('person', 'person.id', '=', 'senders.person_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->where('transactions.transaction_status_id', $status_id)
        ->orderBy('transactions.id','desc');
        if (Auth::user()->level_id == 3) {
            $transactionAndSender = $transactionAndSender->select('transaction_details.total_to_pay as totalAmount', 'person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', 'transactions.added_by as addedBy', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.transaction_date as transactionDate', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transaction_details.service_charge as serviceCharge','transactions.sender_id', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'))
                ->where('transactions.added_by', Auth::user()->id);
        }
 if (Auth::user()->level_id == 5) {
            $transactionAndSender = $transactionAndSender->select('transaction_details.total_to_pay as totalAmount', 'person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', 'transactions.added_by as addedBy', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.transaction_date as transactionDate', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transaction_details.service_charge as serviceCharge','transactions.sender_id', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'))
                ->where('transactions.added_by', Auth::user()->id);
        }

        if (Auth::user()->level_id == 4) {
            $distributorOffice = DB::table('distributor_users')->where('distributor_users.user_id', Auth::user()->id)->first();
            if($distributorOffice->role_id == 2){
                    $transactionAndSender = $transactionAndSender->join('distributor_transactions','transactions.id','=','distributor_transactions.transaction_id')
                ->where('distributor_transactions.distributor_office_id', $distributorOffice->distributor_office_id)
                ->where('distributor_transactions.assigned_to',Auth::user()->id)
            ->select('transaction_details.total_to_pay as totalAmount', 'person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', 'transactions.added_by as addedBy', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.transaction_date as transactionDate', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transaction_details.service_charge as serviceCharge','transactions.sender_id', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'), 'distributor_transactions.assigned_by', 'distributor_transactions.assigned_to');
                }else{
                    $transactionAndSender = $transactionAndSender->join('distributor_transactions', 'transactions.id', '=', 'distributor_transactions.transaction_id')->where('distributor_transactions.distributor_office_id', $distributorOffice->distributor_office_id)
                    ->select('transactions.beneficiary_id as beneficiaryId','transaction_details.total_to_pay as totalAmount', 'person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', 'transactions.added_by as addedBy', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.transaction_date as transactionDate', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transaction_details.service_charge as serviceCharge','transactions.sender_id', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'), 'distributor_transactions.assigned_by', 'distributor_transactions.assigned_to');
                }
        } else {
            $transactionAndSender = $transactionAndSender->select('transaction_details.total_to_pay as totalAmount', 'person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', 'transactions.added_by as addedBy', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.transaction_date as transactionDate', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate','transactions.sender_id', 'transaction_details.service_charge as serviceCharge', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'));
        }
        $transactionAndSender = $transactionAndSender->get();


        $beneficiary = Transaction::leftJoin('beneficiaries', 'beneficiaries.beneficiary_id', '=', 'transactions.beneficiary_id')
            ->leftJoin('person', 'person.id', '=', 'beneficiaries.person_id')
            ->select('transactions.beneficiary_id as beneficiary_id','person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS beneficiary_full_name'))
            ->where('transactions.transaction_status_id', $status_id)
            ->get();

        $i = 0;
        $transactionData['sumSendingAmount'] = 0;
        $transactionData['sumTotalAmount'] = 0;
        $transactionData['sumPaymentAmount'] = 0;
        $transactionData['sumServiceCharge'] = 0;
        foreach ($transactionAndSender as $datas) {

            $distributorOffice = DistributorTransaction::leftJoin('distributor_offices', 'distributor_offices.id', '=', 'distributor_transactions.distributor_office_id')->where('distributor_transactions.transaction_id', $datas->id)->first();
            if (isset($distributorOffice)) {
                $transaction[$i]['company_id'] = $distributorOffice->companies_id;
            } else {
                $transaction[$i]['company_id'] = '';
            }
            $agentTransaction = AgentTransaction::where('transactions_id', $datas->id)->first();
            if (isset($agentTransaction)) {
                $transaction[$i]['agent_rate'] = $agentTransaction->exchange_rate;
                $transaction[$i]['total_commission'] = $agentTransaction->total_commission;
            } else {
                $transaction[$i]['agent_rate'] = '';
                $transaction[$i]['total_commission'] = '';
            }
            $transaction[$i]['transaction_id'] = $datas->id;
            $transaction[$i]['sender_id'] = $datas->sender_id;

            $transaction[$i]['sendingAmount'] = $datas->sendingAmount;
            $transaction[$i]['transactionDate'] = $datas->transactionDate;
            $transaction[$i]['paymentAmount'] = $datas->paymentAmount;
            $transaction[$i]['exchangeRate'] = $datas->exchangeRate;
            $transaction[$i]['serviceCharge'] = $datas->serviceCharge;
            $transaction[$i]['sender_full_name'] = $datas->sender_full_name;
            $transaction[$i]['addedBy'] = $datas->addedBy;
            $transaction[$i]['totalAmount'] = $datas->totalAmount;
            if (Auth::user()->level_id == 4) {

                $transaction[$i]['assigned_by'] = $datas->assigned_by;
                $transaction[$i]['assigned_to'] = $datas->assigned_to;
            }
            $transactionData['sumSendingAmount'] = $transactionData['sumSendingAmount'] + $datas->sendingAmount;
            $transactionData['sumTotalAmount'] = $transactionData['sumTotalAmount'] + $datas->totalAmount;
            $transactionData['sumPaymentAmount'] = $transactionData['sumPaymentAmount'] + $datas->paymentAmount;
            $transactionData['sumServiceCharge'] = $transactionData['sumServiceCharge'] + $datas->serviceCharge;
            foreach ($beneficiary as $datas1) {
                if ($datas->id == $datas1->id) {
                    $transaction[$i]['beneficiary_full_name'] = $datas1->beneficiary_full_name;
                    $transaction[$i]['beneficiary_id'] = $datas1->beneficiary_id;
                }
            }
            $i++;
        }
              return $transaction;
    }

    public function getDetailsbyid($id)
    {
        $transactiondetail = Transaction::
        leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->where('transactions.id', $id)->first();


        /*$transactionsenderdetail = Transaction::leftJoin('senders','senders.id','=','transactions.sender_id')
            ->leftJoin('person','person.id','=','senders.person_id')
            ->leftJoin('person_address','person.id','=','person_address.person_id')
            ->leftJoin('addresses','addresses.id','=','person_address.address_id')
            ->leftJoin('person_phones','person.id','=','person_phones.person_id')
            ->leftJoin('phones','phones.id','=','person_phones.phones_id')
            ->leftJoin('transaction_details','transaction_details.transaction_details_id','=','transactions.transaction_details_id')
            ->select('phones.*','addresses.*','transaction_details.total_to_pay','transaction_details.transaction_date','transaction_details.sending_amount','transaction_details.exchange_rate','transaction_details.payment_amount','transaction_details.purpose_of_transfer','transaction_details.payment_type','transaction_details.service_charge','person.first_name','person.last_name','person.middle_name','transactions.id as transaction_id','transactions.added_by','transactions.sender_id',DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'))

            ->where('transactions.id',$id)->get();


        $beneficiarydetail = Transaction::leftJoin('beneficiaries','beneficiaries.beneficiary_id','=','transactions.beneficiary_id')
            ->leftJoin('person','person.id','=','beneficiaries.person_id')
            ->leftJoin('person_address','person.id','=','person_address.person_id')
            ->leftJoin('addresses','addresses.id','=','person_address.address_id')
            ->leftJoin('person_phones','person.id','=','person_phones.person_id')
            ->leftJoin('phones','phones.id','=','person_phones.phones_id')
            ->select('phones.*','addresses.*','person.first_name','person.last_name','person.middle_name',DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS beneficiary_full_name'))
            ->where('transactions.id',$id)
            ->get();
*/

        return $transactiondetail;


    }

    public function getSum($status_id)
    {

        $transaction = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->leftJoin('agent_transactions','agent_transactions.transactions_id','=','transactions.id')
            ->where('transactions.transaction_status_id', $status_id);
        if (Auth::user()->level_id == 3) {
            $transaction = $transaction->where('transactions.added_by', Auth::user()->id);
        }if (Auth::user()->level_id == 5) {
            $transaction = $transaction->where('transactions.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 4) {

            $distributorOffice = DB::table('distributor_users')->where('distributor_users.user_id', Auth::user()->id)->first();
            if($distributorOffice->role_id == 2){
                $transaction = $transaction->join('distributor_transactions', 'transactions.id', '=', 'distributor_transactions.transaction_id')->where('distributor_transactions.distributor_office_id', $distributorOffice->distributor_office_id)
                ->where('distributor_transactions.assigned_to',Auth::user()->id);
            }else{
                $transaction = $transaction->join('distributor_transactions', 'transactions.id', '=', 'distributor_transactions.transaction_id')->where('distributor_transactions.distributor_office_id', $distributorOffice->distributor_office_id);

            }
              }
        $transactionData['totalSendingAmount'] = $transaction->sum('transaction_details.sending_amount');
        $transactionData['totalPaymentAmount'] = $transaction->sum('transaction_details.payment_amount');
        $transactionData['totalAmount'] = $transaction->sum('transaction_details.total_to_pay');
        $transactionData['totalServiceCharge'] = $transaction->sum('transaction_details.service_charge');
        $transactionData['totalAgentCommission'] = $transaction->sum('agent_transactions.total_commission');
        return $transactionData;
    }

    public function add(array $request)
    {
        DB::beginTransaction();

        try {
            // Saving client profile
            $person = Person::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'dob' => insert_dateformat($request['dob']),
                'email' => strtolower($request['email'])
            ]);

            $sender = Sender::create([
                'person_id' => $person->id,
                'added_by' => current_user_id(),
                'sender_status_id' => 2 //Change this later
            ]);

            // Add address
            $address = Address::create([
                'street' => $request['street'],
                'suburb' => $request['suburb'],
                'postcode' => $request['postcode'],
                'state' => $request['state'],
                'country_list_id' => $request['country_list_id'],
            ]);

            PersonAddress::create([
                'address_id' => $address->id,
                'person_id' => $person->id,
                'current' => 1,
                'address_status_id' => 1
            ]);

            // Add Phone Number
            $phone = new Phone();
            $phone_id = $phone->add($request['number']);
            PersonPhone::create([
                'phones_id' => $phone_id,
                'person_id' => $person->id,
                'current' => 1
            ]);

            /* Later Where does notes go */
            // Add identification
            Identification::create([
                'issued_by' => $request['issued_by'],
                'id_number' => $request['id_number'],
                'identification_status_id' => 2, //later
                'identification_types_id' => 1, //later ID type text field??
                'state' => $request['state'],
                'expiry_date' => insert_dateformat($request['expiry_date']),
                'senders_id' => $sender->id,
                'document_name' => '', //Later No field in the form
                'current' => 1
            ]);

            /* Only if company */
            if ($request['company_name'] != '') {
                $company = Company::create([
                    'company_name' => $request['company_name'],
                    'abn' => $request['abn'],
                    'addresses_id' => $address->id
                ]);

                SenderCompany::create([
                    'senders_sender_id' => $sender->id,
                    'companies_id' => $company->id,
                    'active' => 1
                ]);
            }

            DB::commit();
            return $sender->id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function getDetails($user_id)
    {
        $user = User::join('person', 'person.id', '=', 'users.person_id')
            ->join('user_status', 'users.user_status_id', '=', 'user_status.id')
            ->join('levels', 'users.level_id', '=', 'levels.id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->leftJoin('person_address', function ($q) {
                $q->on('person_address.person_id', '=', 'person.id');
                $q->where('person_address.current', '=', 1);
            })
            ->leftJoin('addresses', 'person_address.address_id', '=', 'addresses.id')
            ->select(['users.id as user_id', 'person.first_name', 'person.last_name', 'person.dob', 'addresses.*', 'users.email', 'phones.number', 'levels.name as role', 'levels.name as level_id', 'user_status.name as status', 'users.created_at', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->find($user_id);
        return $user;
    }

    public function edit(array $request, $user_id)
    {
        DB::beginTransaction();

        try {
            $user = User::find($user_id);
            $user->level_id = $request['level_id'];
            $user->email = strtolower($request['email']);

            if ($request['password'] != '')
                $user->password = Hash::make($request['password']);
            $user->save();

            // Saving client profile
            $person = Person::find($user->person_id);
            $person->first_name = $request['first_name'];
            $person->last_name = $request['last_name'];
            $person->dob = insert_dateformat($request['dob']);
            $person->save();

            // Edit address section
            $person_address = PersonAddress::firstOrCreate([
                'person_id' => $person->id,
                'current' => 1,
                'address_status_id' => 1
            ]);

            // Edit old record when exists
            if ($person_address->address_id != 0) {
                $address = Address::find($person_address->address_id);
                $address->street = $request['street'];
                $address->suburb = $request['suburb'];
                $address->postcode = $request['postcode'];
                $address->state = $request['state'];
                $address->country_list_id = $request['country_list_id'];
                $address->save();

            } else { //remove this one
                // Add address
                $address = Address::create([
                    'street' => $request['street'],
                    'suburb' => $request['suburb'],
                    'postcode' => $request['postcode'],
                    'state' => $request['state'],
                    'country_list_id' => $request['country_list_id'],
                ]);
                $person_address->address_id = $address->id;
                $person_address->save();
            }

            // Edit Phone Number Section
            $person_phone = PersonPhone::where('person_id', $person->id)->where('current', 1)->first();

            //Edit old record when exists
            if (!empty($person_phone)) {
                $phone = Phone::find($person_phone->phones_id);
                $phone->number = $request['number'];
                $phone->save();

            } else {
                $phone = new Phone();
                $phone_id = $phone->add($request['number']);

                PersonPhone::create([
                    'person_id' => $person->id,
                    'current' => 1,
                    'phones_id' => $phone_id
                ]);
            }

            DB::commit();
            return $user->id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }
    public function sendDeliveredEmail($tran){

      /*  $beneficiaryName = getBeneficiaryName($tran->beneficiary_id);
        $beneficiaryDetails = getBeneficiaryDetails($tran->beneficiary_id);*/
        $senderDetails = getSenderDetails($tran->sender_id);
        $senderName = getSenderName($tran->sender_id);
      /*  $transactionDetail = Transaction::join('transaction_details','transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->where('transactions.id', $tran->id)->first();*/

       /* $body = ' We have successfully transferred the payment to the Beneficiary. Here are the transaction details below.<br><br> Beneficiary Details:<br>
                    Name : ' . $beneficiaryName . '<br>
                    Phone No : ' . $beneficiaryDetails['number'] . '<br>
                    Payment Amount : NPR  ' . $transactionDetail->payment_amount . '<br>
                    Payment Type : ' . $transactionDetail->payment_type . ' <br>
                    Account Name : ' . $beneficiaryDetails['account_name'] . '<br>
                    Account No : ' . $beneficiaryDetails['account_no'] . ' <br>
                    BSB : ' . $beneficiaryDetails['bsb'] . ' <br>
                    Bank Name : ' . $beneficiaryDetails['bank_name'] . '<br>';*/
        $application = getAppDetailsForWeb();
        $email_template = getEmailTemplate('type','transaction delivered',$application);

        if($email_template){
            $subject = $email_template->subject;
            $body = $email_template->message;
            $data_array_parse = array(
                'FULL_NAME'  => $senderName,
                'SENDER_NAME'  => $senderName,
                'TRANSACTION_ID'  => 'Order ID : '.$tran->id,
            );
            $data_array_parse = format_template_array($application,$data_array_parse);
            $subject = parseTemplate($subject,$data_array_parse);
            $body = parseTemplate($body,$data_array_parse);
            $view = 'EmailTemplate::Email/email';
        }
        else{
            $subject = 'Transaction Paid';
            $body = ' Your Order ID : '.$tran->id.' with us has been successfully paid in Nepal.<br>
 Thank you for your business with us. Please recommend your friends and families to use our service. And hope you had a great day.';
            $view = 'Transaction::Email/transactionDelivered';
        }

        $params = [
            'to' => strtolower($senderDetails['email']),
            'toName' => $senderName,
            'body' => $body,
            'subject' => $subject,
            'fromEmail' => $application->email,
            'fromName' => $application->name,
        ];
        Mail::send($view, $params, function ($message) use ($params) {
            $message->to($params['to'], $params['toName'])
                ->from($params['fromEmail'], $params['fromName'])
                ->subject($params['subject']);
        });

    }

    public function createNotification($transaction_id,$not){

        $transaction_detail =Transaction::leftjoin('senders','senders.id','=','transactions.sender_id')->leftjoin('person', 'person.id', '=', 'senders.person_id')
            ->where('transactions.id',$transaction_id)->select(['transactions.added_by','transactions.id','transactions.transaction_status_id','senders.id as sender_id','person.email',DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS name')])->first();

        $users = User::whereIn('level_id',[1,2])->get();
        Notification::send($users,new TransactionNotification($transaction_detail));

        $user = User::find($transaction_detail->added_by);
        if(isset($user) && ($user->level_id == 5 || $user->level_id == 3)){
            $user->notify(new TransactionNotification($transaction_detail));
            $sender_detail = Sender::leftJoin('person','person.id','=','senders.person_id')->
            leftJoin('users','users.person_id','person.id')->select('users.firebase_token','users.application_id','senders.added_by')->where('senders.id',$transaction_detail->sender_id)->first();
            if($sender_detail)
                if(isset($sender_detail) && ($sender_detail->firebase_token != '' || $sender_detail->firebase_token != null)){
                    $application = Application::where('agent_id',$sender_detail->added_by)->first();
                    if(!$application){
                        if($sender_detail->application_id != '' || $sender_detail->application_id != null){
                            $application = Application::find($sender_detail->application_id);
                        }
                    }

                    if($application){
                        _sendPushNotification($not['title'],$not['notification_message'],'','',$sender_detail->firebase_token,$application->firebase_key);

                    }
                }
        }
    }
    public function getApplication(int $transaction_id) : Application{
        $person = Transaction::join('senders','senders.id','=','transactions.sender_id')
            ->join('person','person.id','=','senders.person_id')
            ->where('transactions.id',$transaction_id)
            ->select('person.id as person_id')
            ->first();
        if(!$person){
            return Application::find(1);
        }
        $application = User::join('person','person.id','=','users.person_id')
            ->where('person.id',$person->person_id)
            ->select('users.application_id as application_id')
            ->first();
        if($application) {
            return Application::find($application->application_id);
        }
        return Application::find(1);
    }
    public function sendTransactionInReviewMail($transaction){
        $application = $this->getApplication($transaction->id);
        $email_template = getEmailTemplate('type','Transaction In Review',$application);
        $senderDetails = getSenderDetails($transaction->sender_id);
        $senderName = getSenderName($transaction->sender_id);
        if(!$email_template){
            return false;
        }
        if($email_template->active === EmailStatusConstant::INACTIVE){
            return false;
        }
        $subject = $email_template->subject;
        $body = $email_template->message;
        $data_array_parse = array(
                "FULL_NAME" => $senderName,
                "SENDER_NAME" => $senderName,
                "TRANSACTION_ID" => 'Order ID : '.$transaction->id,
            );
        $data_array_parse = format_template_array($application,$data_array_parse);
        $subject = parseTemplate($subject,$data_array_parse);
        $body = parseTemplate($body,$data_array_parse);
        $view = 'EmailTemplate::Email/email';
        $params = [
            'to' => strtolower($senderDetails['email']),
            'toName' => $senderName,
            'body' => $body,
            'subject' => $subject,
            'fromEmail' => $application->email,
            'fromName' => $application->name,
        ];
        Mail::send($view, $params, function ($message) use ($params) {
            $message->to($params['to'], $params['toName'])
                ->from($params['fromEmail'], $params['fromName'])
                ->subject($params['subject']);
        });
    }
}

