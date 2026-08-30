<?php

namespace App\Modules\Agent\Models;

use App\Modules\Sender\Models\Sender;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Agent extends Model
{

    protected $table = 'agents';
    protected $fillable = ['person_id', 'user_id', 'agent_exchange_rate_id','created_at','agent_service_charge'];
    public $timestamps = false;

    public function createUser($id)
    {
        $messages = [
            'email.unique' => 'User with the same email address already exists.'
        ];

        DB::beginTransaction();
        try {
            $agent = Agent::find($id);
            $person = Person::find($agent->person_id);
            $this->validate($person->email,['email'=>'email|min:5|max:55|unique:users'], $messages);
            $user = User::create([
                'level_id' => 3,
                'user_status_id' => 2,
                'person_id' => $agent->person_id,
                'email' => strtolower($person->email),
                'password' => ''
            ]);
            $agent->user_id = $user->id;
            $agent->save();

            $user->auth_code = uniqid().md5($user->id);
            $user->save();
            DB::commit();
            return $user->user_id;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function getAgentById($agent_id)
    {
        $agent = Agent::join('person', 'person.id', '=', 'agents.person_id')
            ->leftJoin('users', 'agents.user_id', '=', 'users.id')
            ->leftJoin('person_phones', 'person_phones.person_id', '=', 'person.id')
            ->leftJoin('phones', 'phones.id', '=', 'person_phones.phones_id')->where('agents.id', $agent_id)
            ->leftJoin('person_address', 'person_address.person_id', '=', 'person.id')
            ->leftJoin('addresses', 'addresses.id', '=', 'person_address.address_id')
            ->leftJoin('country_list', 'country_list.id', '=', 'addresses.country_list_id')
            ->select('country_list.name as country', 'addresses.street as street', 'addresses.suburb as suburb', 'addresses.state as state', 'addresses.postcode as postcode', 'agents.id as agent_id', 'person.first_name as first_name', 'person.last_name as last_name', 'phones.number as number', 'person.email as email', 'users.created_at')
            ->first();
        return $agent;
    }

    public function getAgents()
    {
        $agent = Agent::join('person', 'person.id', '=', 'agents.person_id')
            ->leftJoin('person_phones', 'person_phones.person_id', '=', 'person.id')
            ->leftJoin('phones', 'phones.id', '=', 'person_phones.phones_id')
            ->leftJoin('person_address', 'person_address.person_id', '=', 'person.id')
            ->leftJoin('addresses', 'addresses.id', '=', 'person_address.address_id')
            ->leftJoin('country_list', 'country_list.id', '=', 'addresses.country_list_id')
            ->select('country_list.name as country', 'addresses.street as street', 'addresses.suburb as suburb', 'addresses.state as state', 'addresses.postcode as postcode', 'agents.id as agent_id', 'person.first_name as first_name', 'person.last_name as last_name', 'phones.number as number', 'person.email as email')
            ->get();
        return $agent;
    }

    public function getAgentSenders($id)
    {
        $agent_senders = Sender::join('agent_senders', 'senders.id', '=', 'agent_senders.senders_sender_id')
            ->join('person', 'person.id', '=', 'senders.person_id')
            ->join('sender_status', 'senders.sender_status_id', '=', 'sender_status.id')
            ->leftJoin('agents','agents.id','=','agent_senders.agents_id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->where('agents.id',$id)
            ->select(['senders.id as sender_id', 'senders.added_by', 'person.first_name', 'person.last_name', 'person.email', 'senders.sender_status_id', 'phones.number', 'sender_status.name as status', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])->get();

        return $agent_senders;
    }

    public function getSumary($id)
    {
        $summary = AgentAccount::leftJoin('agent_payments', 'agent_payments.id', '=', 'agent_accounts.agent_payments_id')
            ->leftJoin('agent_transactions', 'agent_transactions.id', '=', 'agent_accounts.agent_transactions_id')
            ->where('agent_transactions.agents_id', $id)
            ->orwhere('agent_payments.agent_id', $id)
            ->select('agent_accounts.*', 'agent_payments.amount', 'agent_payments.description', 'agent_transactions.*')
            ->orderby('agent_accounts.id', 'asc')
            ->get();
        return $summary;
    }

    public function getAgentTransaction($id)
    {
        $agent_transactions = Transaction::join('transaction_details', 'transactions.transaction_details_id', '=', 'transaction_details.transaction_details_id')
            ->join('agent_transactions', 'agent_transactions.transactions_id', '=', 'transactions.id')
            ->join('status', 'status.id', '=', 'transactions.transaction_status_id')
            ->where('agent_transactions.agents_id', $id)
            ->select('transactions.*', 'transaction_details.*', 'status.name')
            ->orderby('transactions.id', 'desc');
        return $agent_transactions;
    }

    public function totalsum($id)
    {
        $summary = $this->getSumary($id);
        $totalcommission = 0;
        $totalpayment = 0;
        $payment_summary = [];
        $i = 0;
        $total = [];
        $data = 0;
        foreach ($summary as $summary) {
            if (isset($summary->transactions_id)) {
                $transaction = Transaction::join('transaction_details', 'transactions.transaction_details_id', '=', 'transaction_details.transaction_details_id')->where('transactions.id', $summary->transactions_id)->first();
                if ($transaction->transaction_status_id != 12) {
                    $payment_summary[$i]['date'] = standard_date($summary->created_at);
                    $payment_summary[$i]['description'] = format_id($summary->transactions_id, 'T') . ' Sender : ' . getSenderName($transaction->sender_id) . ' Beneficiary : ' . getBeneficiaryName($transaction->beneficiary_id);
                    $payment_summary[$i]['commission'] = $summary->total_commission;
                    $payment_summary[$i]['payment_amount'] = '';
                    $data = 0 + $payment_summary[$i]['commission'] + $data;
                    $payment_summary[$i]['balance'] = round($data, 2);
                    $totalcommission = round(($totalcommission + $summary->total_commission), 2);
                }
            } else {
                $payment_summary[$i]['date'] = standard_date($summary->created_at);
                $payment_summary[$i]['description'] = $summary->description;
                $payment_summary[$i]['commission'] = '';
                $payment_summary[$i]['payment_amount'] = $summary->amount;
                $totalpayment = $totalpayment + $summary->amount;
                $data = 0 - $payment_summary[$i]['payment_amount'] + $data;
                $payment_summary[$i]['balance'] = round($data, 2);
            }
            $i++;
        }
        $dueamount = round(($totalcommission - $totalpayment),2);
        $total['totalcommission'] = $totalcommission;
        $total['totalpayment'] = $totalpayment;
        $total['dueamount'] = $dueamount;
        $total['paymentSummary'] = $payment_summary;

        return $total;
    }

    public function totalSendingAmount($id)
    {
        $agent_transaction = $this->getAgentTransaction($id);
        $totalsendingamount = $agent_transaction->sum('transaction_details.sending_amount');
        return $totalsendingamount;
    }

    public function totalPaymentAmount($id)
    {
        $agent_transaction = $this->getAgentTransaction($id);
        $totalpaymentamount = $agent_transaction->sum('transaction_details.payment_amount');
        return $totalpaymentamount;
    }

    public function getAgentsEmail(){
        $agent = Agent::join('person', 'person.id', '=', 'agents.person_id')
            ->select('person.email')
            ->pluck('email','email');
        return $agent;
    }
}
