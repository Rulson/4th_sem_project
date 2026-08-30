<?php

namespace App\Http\Controllers;

use App\Modules\Agent\Models\AgentTransaction;
use App\Modules\Application\Service\GetApplicationService;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Sender\Models\Sender;
use App\Modules\Transaction\Models\Note;
use App\Modules\Transaction\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Modules\User\Models\CountryList;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\UserLevel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use DB;

class BaseController extends Controller
{
    public $user;
    public $signed_in;
    public $extra_folder;

    function __construct(
    )
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->signed_in = Auth::check();
            $this->extra_folder = ($this->user->level_id == 5)?"Client/":"";

            View::share('signed_in', $this->signed_in);
            View::share('current_user', $this->current_user());
            View::share('controller', $this->getControllerDetails());
            View::share('current_url', Route::current());
            View::share('countries', $this->get_country_list());
            View::share('transaction_count', $this->get_transaction_count());
            View::share('notification_comment', $this->getTransactionComment());
            if($this->user->level_id ==5) View::share('transaction_notification', $this->transaction_notification());
            else View::share('transaction_notification', []);
            View::share('sender_verification_notification', $this->sender_verification_notification());
            return $next($request);
        });
    }


    public function getAgentAverageRate()
    {
        $average_rate = AgentTransaction::avg('exchange_rate');
        return $average_rate;
    }

    public function getClientAverageRate()
    {
        $average_rate = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->avg('exchange_rate');
        return $average_rate;

    }

    public function getClientAverageRate_agent()
    {
        $average_rate = Transaction::join('agent_transactions','agent_transactions.transactions_id','=','transactions.id')->join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->avg('transaction_details.exchange_rate');
        return $average_rate;

    }

    public function getTransactionComment()
    {
        $comment = Note::leftJoin('notes_assign', 'notes_assign.notes_id', '=', 'notes.id')
            ->leftJoin('transactions', 'transactions.id', '=', 'notes.transactions_id')
            ->where('notes_assign.user_id', Auth::user()->id)
            ->where('notes_assign.is_read', 0)
            ->select('transactions.id as Tid', 'notes.added_by as addedBy', 'notes.comment as comment', 'notes_assign.created_at as created_at', 'notes_assign.id as assignId')
            ->orderBy('notes.id', 'desc')->get();
        return $comment;

    }

    public function current_user()
    {
        $current_user = $this->user;

        if (!empty($current_user)) {
            $level = UserLevel::find($current_user->level_id);
            $profile = Person::find($current_user->person_id);
            $current_user->full_name = $profile->first_name . ' ' . $profile->last_name;
            $current_user->first_name = $profile->first_name;
            $current_user->last_name = $profile->last_name;
            $current_user->profile = $profile;
            $current_user->level_type = $level->name;
            $current_user->level_value = $level->value;
            $current_user->level = $level->user_level_id;
            $current_user->isAdmin = ($current_user->level == 1);
            $current_user->isStaff = ($current_user->role == 2);
            $current_user->isAgent = ($current_user->role == 3);
            $current_user->isDistributor = ($current_user->role == 4);
            $current_user->isClient = ($current_user->role == 5);
        }
        return $current_user;
    }

    /**
     * get country array
     * @return array
     */
    public function get_country_list()
    {
        $list = CountryList::orderBy('name', 'asc')->pluck('name', 'id');
        return $list;
    }

    /**
     * return success json data to view
     * @param array $data
     * @return mixed
     */
    function success(array $data = array())
    {
        $response = ['status' => 1, 'data' => $data];
        return Response::json($response);
    }

    /**
     * return failed json data to view
     * @param array $data
     * @return mixed
     */
    function fail(array $data = array())
    {
        $response = ['status' => 0, 'data' => $data];
        return Response::json($response);
    }

    function getControllerDetails()
    {
        $action = app('request')->route()->getAction();
        $controller = class_basename($action['controller']);
        list($controller, $action) = explode('@', $controller);
        return $controller;
    }

    public function get_transaction()
    {
        $transaction = Transaction::leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
            ->leftJoin('person', 'person.id', '=', 'senders.person_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->select('transaction_details.total_to_pay as totalAmount','transaction_details.cost_rate as cost_rate', 'person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', 'transactions.added_by as addedBy', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.transaction_date as transactionDate', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transaction_details.service_charge as serviceCharge', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'));
        if (Auth::user()->level_id == 3) {
            $transaction = $transaction->where('transactions.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            $transaction = $transaction->where('transactions.added_by', Auth::user()->id);
        }
        return $transaction;
    }

    public function transactionCount(){
            $count = $this->get_transaction()->count();
            return $count;
    }

    public function getAgentTransaction()
    {
        $transaction = AgentTransaction::leftJoin('transactions', 'transactions.id', '=', 'agent_transactions.transactions_id')
            ->leftJoin('agents', 'agents.id', '=', 'agent_transactions.agents_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
          ->where('transactions.transaction_status_id','!=',12);
        return $transaction;
    }

    public function get_admin_profit(){
        $todayTrans = $this->get_transaction()->where('transactions.transaction_status_id','!=',12)
            ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->get();
        $transaction_sum['today']=0;
       foreach($todayTrans as $today){
           $agent_transaction = AgentTransaction::where('transactions_id',$today->id)->first();
           if($agent_transaction){
               $profit = 3 + ((($today->totalAmount-$today->serviceCharge) * ($today->cost_rate - $agent_transaction->exchange_rate))/$today->cost_rate);
           }else{
            $profit = $today->serviceCharge + ((($today->totalAmount-$today->serviceCharge) * ($today->cost_rate -$today->exchangeRate))/$today->cost_rate);
           }
           $transaction_sum['today']=$transaction_sum['today']+$profit;
       }
       return $transaction_sum;
    }

    public function getAgentTotalTransactionCount(){
        $this_month = Carbon::now()->month;
        $a = format_date(Carbon::now()->startOfWeek());
        $startOfWeek = Carbon::parse($a)->format('Y-m-d');
        $b = format_date(Carbon::now()->endOfWeek());
        $endOfWeek = Carbon::parse($b)->format('Y-m-d');

        $transaction_count['today'] = $this->getAgentTransaction()
            ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->count();
        $transaction_count['this_week'] = $this->getAgentTransaction()
            ->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->count();
        $transaction_count['this_month'] = $this->getAgentTransaction()
            ->whereMonth('transaction_details.transaction_date', '=', $this_month)->count();
        $transaction_count['today_sending_amount'] = $this->getAgentTransaction()
            ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->sum('transaction_details.sending_amount');
        $transaction_count['this_week_sending_amount'] = $this->getAgentTransaction()
            ->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->sum('transaction_details.sending_amount');
        $transaction_count['this_month_sending_amount'] = $this->getAgentTransaction()
            ->whereMonth('transaction_details.transaction_date', '=', $this_month)->sum('transaction_details.sending_amount');
        $transaction_count['today_payment_amount'] = $this->getAgentTransaction()
            ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->sum('transaction_details.payment_amount');
        $transaction_count['this_week_payment_amount'] = $this->getAgentTransaction()
            ->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->sum('transaction_details.payment_amount');
        $transaction_count['this_month_payment_amount'] = $this->getAgentTransaction()
            ->whereMonth('transaction_details.transaction_date', '=', $this_month)->sum('transaction_details.payment_amount');
        $transaction_count['today_exchange_rate'] = $this->getAgentTransaction()
            ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->sum('agent_transactions.exchange_rate');
        if ($transaction_count['today'] == 0) {
            $transaction_count['today_average_exchange_rate'] = 0;
        } else {
            $transaction_count['today_average_exchange_rate'] = $transaction_count['today_exchange_rate'] / $transaction_count['today'];

        }
        return $transaction_count;
    }

    function get_total_transaction_count()
    {
        $this_month = Carbon::now()->month;
        $a = format_date(Carbon::now()->startOfWeek());
        $startOfWeek = Carbon::parse($a)->format('Y-m-d');
        $b = format_date(Carbon::now()->endOfWeek());
        $endOfWeek = Carbon::parse($b)->format('Y-m-d');

        $transaction_count['today'] = $this->get_transaction()
            ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->count();
        $transaction_count['this_week'] = $this->get_transaction()
            ->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->count();
        $transaction_count['this_month'] = $this->get_transaction()
            ->whereMonth('transaction_details.transaction_date', '=', $this_month)->count();;
        $transaction_count['today_sending_amount'] = $this->get_transaction()
            ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->sum('transaction_details.total_to_pay');
        $transaction_count['this_week_sending_amount'] = $this->get_transaction()
            ->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->sum('transaction_details.total_to_pay');
        $transaction_count['this_month_sending_amount'] = $this->get_transaction()
            ->whereMonth('transaction_details.transaction_date', '=', $this_month)->sum('transaction_details.total_to_pay');
        $transaction_count['today_payment_amount'] = $this->get_transaction()
            ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->sum('transaction_details.payment_amount');
        $transaction_count['this_week_payment_amount'] = $this->get_transaction()
            ->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->sum('transaction_details.payment_amount');
        $transaction_count['this_month_payment_amount'] = $this->get_transaction()
            ->whereMonth('transaction_details.transaction_date', '=', $this_month)->sum('transaction_details.payment_amount');
        $transaction_count['today_exchange_rate'] = $this->get_transaction()
            ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->sum('transaction_details.exchange_rate');
        if ($transaction_count['today'] == 0) {
            $transaction_count['today_average_exchange_rate'] = 0;
        } else {
            $transaction_count['today_average_exchange_rate'] = $transaction_count['today_exchange_rate'] / $transaction_count['today'];
        }
        return $transaction_count;
    }

    function get_transaction_count()
    {
        $transaction_count['unconfirmed'] = $this->get_transaction()
            ->where('transactions.transaction_status_id', 1)->count();
        $transaction_count['confirmed'] = $this->get_transaction()
            ->where('transactions.transaction_status_id', 2)->count();
        $transaction_count['send_for_collection'] = $this->get_transaction()
            ->where('transactions.transaction_status_id', 3)->count();
        $transaction_count['payment_in_progress'] = $this->get_transaction()
            ->where('transactions.transaction_status_id', 4)->count();
        $transaction_count['delivered'] = $this->get_transaction()
            ->where('transactions.transaction_status_id', 5)->count();
        $transaction_count['cancelled'] = $this->get_transaction()
            ->where('transactions.transaction_status_id', 6)->count();
        $transaction_count['hold_on'] = $this->get_transaction()
            ->where('transactions.transaction_status_id', 7)->count();
        return $transaction_count;
    }

    function totalSenders(){
        $senders = 0;
        if(in_array(Auth::user()->level_id, [1, 2, 6, 7, 8])){
            $senders = Sender::count();
        } else if (Auth::user()->level_id == 3) {
            $senders = Sender::where('added_by', Auth::user()->id)->count();
        }
        return $senders;
    }

    function totalBeneficiaries(){
        $beneficiaries = Beneficiary::count();
        if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2) {
            $beneficiaries = Beneficiary::count();
        }
        if (Auth::user()->level_id == 3 || Auth::user()->level_id == 5) {
            $beneficiaries = Beneficiary::where('added_by', Auth::user()->id)->count();
        }

        return $beneficiaries;
    }

    function transaction_notification(){
        $notifications = [];
        if(in_array(Auth::user()->level_id, [1,2,5,3])){
            $user = Auth::user();
            $notifications = $user->unreadNotifications;
        }
        return $notifications;
    }

    function sender_verification_notification(){
        $sender =Sender::where('sender_status_id',5)
            ->orderBy('id','desc')-> get();
        return $sender;
    }

}
