<?php

namespace App\Modules\Agent\Controllers;

use App\Http\Controllers\BaseController;

use App\Modules\Agent\Models\Agent;
use App\Modules\Agent\Models\AgentAccount;
use App\Modules\Agent\Models\AgentExchangeRate;
use App\Modules\Agent\Models\AgentPayment;
use App\Modules\Agent\Models\AgentTransaction;
use App\Modules\Sender\Models\Sender;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionDetails;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\CountryList;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;

class AgentController extends BaseController
{
    function __construct(Agent $agent)
    {
        $this->agent = $agent;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!in_array(Auth::user()->level_id ,[1,2,6,7])) {
            abort(403, 'Unauthorized action.');
        }
        $data = Agent::join('person', 'person.id', '=', 'agents.person_id')
            ->join('person_phones', 'person_phones.person_id', '=', 'person.id')
            ->join('phones', 'phones.id', '=', 'person_phones.phones_id')->select('agents.id as id','agents.agent_service_charge', 'person.email as email', DB::raw('CONCAT(person.first_name, " ", person.last_name) as name'), 'phones.number as number')
           ->orderBy('agents.id','asc')->get();
        return view("Agent::index", compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!(Auth::user()->level_id == 1)) {
            abort(403, 'Unauthorized action.');
        }
        return view("Agent::create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required | email | unique:person',
            'phone_number' => 'required',
            'street' => 'required',
            'suburb' => 'required',
            'postcode' => 'required',
            'service_charge' => 'required|regex:/^[0-9]+(\.[0-9]{1,2})?$/'

        ]);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $formData['first_name'] = $request['first_name'];
            $formData['last_name'] = $request['last_name'];
            $formData['dob'] = Carbon::createFromFormat('d/m/Y', $request['dob'])->format('Y-m-d');

            $formData['email'] = strtolower($request['email']);

            $person = Person::create($formData);
            $agentExchange['less_than_service_charge'] = 3;
            $agentExchange['more_than_service_charge'] = 0;
            $agentExchange['sending_amount_threshold'] = 0;
            $exchange = AgentExchangeRate::create($agentExchange);

            $phoneData['number'] = $request['phone_number'];
            $phone = Phone::create($phoneData);

            $personPhoneData['phones_id'] = $phone->id;
            $personPhoneData['person_id'] = $person->id;
            $personPhoneData['current'] = 1;

            PersonPhone::create($personPhoneData);

            $addressData['street'] = $request['street'];
            $addressData['suburb'] = $request['suburb'];
            $addressData['postcode'] = $request['postcode'];
            $addressData['state'] = $request['state'];
            $addressData['country_list_id'] = $request['country_list_id'];
            $address = Address::create($addressData);
            $personAddressData['address_id'] = $address->id;
            $personAddressData['person_id'] = $person->id;
            $personAddressData['address_status_id'] = 1;
            $personAddressData['current'] = 1;

            PersonAddress::create($personAddressData);

            $agent['person_id'] = $person->id;
            $agent['agent_exchange_rate_id'] = $exchange->id;
            $agent['user_id'] = null;
            $agent['agent_service_charge'] = $request->service_charge;

            $agent1 = Agent::create($agent);
            $notification1 = array(
                'message' => 'Agent has been created successfully!',
                'alert-type' => 'success',
            );
        } catch (\Exception $e) {
            DB::rollback();
            $message = 'Sorry agent could not be saved.';

            if (env('APP_DEBUG'))
                $message = $e->getMessage() . 'Line #' . $e->getLine();
            $notification1 = array(
                'message' => 'Sorry Agent could not be saved!',
                'alert-type' => 'error',
            );
        }
        DB::commit();
        return redirect()->route('agents.index')->with($notification1);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!in_array(Auth::user()->level_id ,[1,2,6,7])) {
            abort(403, 'Unauthorized action.');
        }
        $agent = $this->agent->getAgentById($id);

        $summary = $this->agent->getSumary($id);

        $payment_summary = [];
        $totalcommission = 0;
        $totalpayment = 0;
        $i = 0;
        $data=0;
        foreach ($summary as $summary) {
            if (isset($summary->transactions_id)) {
                $transaction = Transaction::join('transaction_details', 'transactions.transaction_details_id', '=', 'transaction_details.transaction_details_id')->where('transactions.id', $summary->transactions_id)->first();
               if ($transaction->transaction_status_id != 12){
                    $payment_summary[$i]['date'] = standard_date($summary->created_at);
                    $payment_summary[$i]['description'] = format_id($summary->transactions_id, 'T') . ' Sender : ' . getSenderName($transaction->sender_id) . ' Beneficiary : ' . getBeneficiaryName($transaction->beneficiary_id);
                    $payment_summary[$i]['commission'] = $summary->total_commission;
                    $payment_summary[$i]['payment_amount'] = '';
                    $data=0+$payment_summary[$i]['commission']+$data;
                    $payment_summary[$i]['balance'] = round($data,2);
                    $totalcommission = round(($totalcommission + $summary->total_commission),2);
                }
            } else {
                $payment_summary[$i]['date'] = standard_date($summary->created_at);
                $payment_summary[$i]['description'] = $summary->description;
                $payment_summary[$i]['commission'] = '';
                $payment_summary[$i]['payment_amount'] = $summary->amount;
                $totalpayment = $totalpayment + $summary->amount;
                $data=0-$payment_summary[$i]['payment_amount']+$data;
                $payment_summary[$i]['balance'] = round($data,2);
            }
            $i++;
        }

        $dueamount = round(($totalcommission - $totalpayment),2);
        $agent_transactions=$this->agent->getAgentTransaction($id);
        $agent_transaction= $agent_transactions->get();
        $totalsendingamount= $this->agent->totalSendingAmount($id);
        $totalpaymentamount= $this->agent->totalPaymentAmount($id);

        $totaltransaction= AgentTransaction::where('agents_id',$id)->count();
        $totalsender=DB::table('agent_senders')->where('agents_id',$id)->count();
        $agent_payments = AgentPayment::where('agent_id',$id)->get();

        $senders = $this->agent->getAgentSenders($id);
        return view("Agent::show",compact('agent_payments','agent','senders','totalsendingamount','totalpaymentamount','agent_transaction','totaltransaction','totalsender','dueamount','totalcommission','payment_summary','totalpayment'));
    }
    public function dashboard(){
        if(!in_array(Auth::user()->level_id ,[1,2,6,7])) {
            abort(403, 'Unauthorized action.');
        }
        $agents = Agent::orderBy('id','desc')->get();
        $agent_total_transaction_count = $this->getAgentTotalTransactionCount();
       $agent_average_rate = $this->getAgentAverageRate();
        $client_average_rate_for_agent = $this->getClientAverageRate_agent();

        return view("Agent::dashboard",compact('agents'))
            ->with('agent_total_transaction_count',$agent_total_transaction_count)
            ->with('agent_average_rate',$agent_average_rate)
            ->with('client_average_rate_for_agent',$client_average_rate_for_agent)
            ;
    }

    public function paymentSummary(){
        $agentId = Agent::where('user_id',Auth::user()->id)->first();

        $agentPaymentSummary = $this->agent->totalsum($agentId->id);

        return view("Agent::payment/summary",compact('agentPaymentSummary'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!in_array(Auth::user()->level_id, [1])) {
            abort(403, 'Unauthorized action.');
        }
        $data = Agent::join('person', 'person.id', '=', 'agents.person_id')
            ->leftJoin('person_phones', 'person_phones.person_id', '=', 'person.id')
            ->leftJoin('phones', 'phones.id', '=', 'person_phones.phones_id')
            ->leftJoin('person_address', 'person_address.person_id', '=', 'person.id')
            ->leftJoin('addresses', 'addresses.id', '=', 'person_address.address_id')
            ->leftJoin('agent_exchange_rate', 'agent_exchange_rate.id', 'agents.agent_exchange_rate_id')
            ->leftJoin('country_list', 'country_list.id', '=', 'addresses.country_list_id')
            ->where('agents.id', $id)
            ->select('agents.id as id','agents.agent_service_charge as service_charge', 'country_list.id as countryId', 'addresses.postcode as postcode', 'addresses.state as state','addresses.street as street', 'addresses.suburb as suburb', 'person.first_name as first_name', 'person.dob as dob', 'person.email as email', 'person.last_name as last_name', 'phones.number as phone_number'
                , 'agent_exchange_rate.sending_amount_threshold as threshold', 'agent_exchange_rate.less_than_service_charge as minCommission', 'agent_exchange_rate.more_than_service_charge as maxCommission')->first();
        return view("Agent::edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updatePayment(Request $request,$payment_id){
        $payment = AgentPayment::find($payment_id);
        $payment->date = $request->date;
        $payment->amount = $request->amount;
        $payment->description = $request->description;
        $payment->method = $request->payment_method;
        $payment->save();
        $notification = array(
            'message' => 'Payment Details has been updated successfully!',
            'alert-type' => 'success',
        );
        return redirect()->route('agent.show',$payment->agent_id)->with($notification);


    }
    public function editPayment($payment_id){
        $payments = AgentPayment::where('id',$payment_id)->first();

        return view("Agent::payment/edit",compact('payments'));
    }

    public function update(Request $request, $id)
    {
        $agent = Agent::find($id);

        $person = Person::find($agent->person_id);
        $person['first_name'] = $request['first_name'];
        $person['last_name'] = $request['last_name'];
        $person['dob'] = $request['dob'];
        $person['email'] = strtolower($request['email']);
        $person->save();

        $person_phones = PersonPhone::where('person_id', $person->id)->first();
        $phone = Phone::find($person_phones->phones_id);
        $phone['number'] = $request['phone_number'];
        $phone->save();
        $person_address = PersonAddress::where('person_id', $person->id)->first();
        $address = Address::find($person_address->address_id);
        $address['street'] = $request['street'];
        $address['suburb'] = $request['suburb'];
        $address['postcode'] = $request['post_code'];
        $address['state'] = $request['state'];
        $address['country_list_id'] = $request['country_list_id'];
        $address->save();
        $agent->update(['agent_service_charge'=>$request['service_charge']]);

        $notification1 = array(
            'message' => 'Agent has been updated successfully!',
            'alert-type' => 'success',
        );
        return redirect()->route('agents.index')->with($notification1);
    }

    public function createUser($id)
    {
        $agent = Agent::find($id);

        $person = Person::find($agent->person_id);
        $user_checkk = User::where('email',$person->email)->first();
        if(!$user_checkk){
            $user = User::create([
                'level_id' => 3,
                'user_status_id' => 2,
                'person_id' => $agent->person_id,
                'email' => strtolower($person->email),
                'password' => ''
            ]);
            $user->auth_code =  uniqid().md5($user->id);
            $user->save();
            $agent->user_id = $user->id;
            $agent->save();
            Flash::success('User has been created successfully.');
            return redirect()->route('users.index');
        }else{
            Flash::error('User with the same email already exists');
            return redirect()->route('agents.index');
        }
    }

    public function agent_transaction(){
        $transaction = AgentTransaction::join('transactions','transactions.id','=','agent_transactions.transactions_id')
            ->join('transaction_details','transaction_details.transaction_details_id','=','transactions.transaction_details_id')
        ->join('agents','agents.id','=','agent_transactions.agents_id')
            ->where(function ($query) {
                $query->where('transactions.transaction_status_id', 2);
                $query->orWhere('transactions.transaction_status_id', 3);
                $query->orWhere('transactions.transaction_status_id', 4);
                $query->orWhere('transactions.transaction_status_id', 5);
            });
        return $transaction;
    }

    public function particular_agent_transaction($agent_id){
        $transaction = AgentTransaction::leftJoin('transactions', 'transactions.id', '=', 'agent_transactions.transactions_id')
            ->leftJoin('agents', 'agents.id', '=', 'agent_transactions.agents_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->where(function ($query) {
                $query->where('transactions.transaction_status_id', 2);
                $query->orWhere('transactions.transaction_status_id', 3);
                $query->orWhere('transactions.transaction_status_id', 4);
                $query->orWhere('transactions.transaction_status_id', 5);
            })
            ->where('agents.id',$agent_id);
        return $transaction;
    }

    public function getAgentBarChartData(Request $request){
        if (request()->ajax()) {
            $agent_id = $request->get('id');
            $year = Carbon::now()->format('Y');
            for ($i = 1; $i <= 12; $i++) {
                $sendingAmountPerMonth[Carbon::createFromFormat('!m', $i)->format('M')] = $this->particular_agent_transaction($agent_id)->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->sum('transaction_details.sending_amount');
                $transactionCount[Carbon::createFromFormat('!m', $i)->format('M')] = $this->particular_agent_transaction($agent_id)->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->count();
            }
            $data = [
                'sendingAmountPerMonth' => $sendingAmountPerMonth,
                'transactionCount' => $transactionCount,
            ];
            echo json_encode($data);
        }
    }

    public function getLineChartData(Request $request)
    {
        if (request()->ajax()) {
            $query = $request->get('query');
            $year = Carbon::now()->format('Y');
            if ($query == 0) {
                for ($i = 1; $i <= 12; $i++) {
                    $sendingAmountPerMonth[Carbon::createFromFormat('!m', $i)->format('M')] = $this->agent_transaction()->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->sum('transaction_details.sending_amount');
                    $paymentAmountPerMonth[Carbon::createFromFormat('!m', $i)->format('M')] = $this->agent_transaction()->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->sum('transaction_details.payment_amount');
                }
            } elseif ($query == 1) {
                $start = Carbon::now()->startOfMonth()->format('d');
                $end=Carbon::now()->endOfMonth()->format('d');
                $month=Carbon::now()->format('m');
                $year=Carbon::now()->format('Y');
                for ($i = 1; $i <= $end; $i++) {
                    $sendingAmountPerMonth[Carbon::now()->format('M').' '.$i] = $this->agent_transaction()->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('DAY(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->sum('transaction_details.sending_amount');
                    $paymentAmountPerMonth[$i] = $this->agent_transaction()->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('DAY(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->sum('transaction_details.payment_amount');
                }
            } else {
                $start = Carbon::now()->startOfweek();
                $end=Carbon::now()->endOfweek();
                $dates = $this->generateDateRange($start,$end);
                foreach($dates as $date) {
                    $sendingAmountPerMonth[Carbon::parse($date)->format('M').' '.Carbon::parse($date)->format('d').' '.'('.Carbon::parse($date)->format('D').')'] = $this->agent_transaction()->whereRaw('MONTH(transaction_details.transaction_date)= ?',[Carbon::parse($date)->format('m')])->whereRaw('DAY(transaction_details.transaction_date) = ?', [Carbon::parse($date)->format('d')])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [Carbon::parse($date)->format('Y')])->sum('transaction_details.sending_amount');
                    $paymentAmountPerMonth[Carbon::parse($date)->format('M').' '.Carbon::parse($date)->format('d').' '.'('.Carbon::parse($date)->format('D').')'] = $this->agent_transaction()->whereRaw('MONTH(transaction_details.transaction_date)= ?',[Carbon::parse($date)->format('m')])->whereRaw('DAY(transaction_details.transaction_date) = ?', [Carbon::parse($date)->format('d')])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [Carbon::parse($date)->format('Y')])->sum('transaction_details.payment_amount');
                }
            }
            $data = [
                'sendingAmountPerMonth' => $sendingAmountPerMonth,
                'paymentAmountPerMonth' => $paymentAmountPerMonth,
            ];
            echo json_encode($data);
        }

    }

    public function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

}
