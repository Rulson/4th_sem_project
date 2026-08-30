<?php

namespace App\Modules\Transaction\Controllers;

use App\Exports\AustracExport;
use App\Exports\TransactionExport;
use App\Exports\TransactionExport2;
use App\Http\Controllers\BaseController;
use App\Modules\Agent\Models\Agent;
use App\Modules\Agent\Models\AgentAccount;
use App\Modules\Agent\Models\AgentTransaction;
use App\Modules\Application\Service\GetApplicationService;
use App\Modules\Beneficiary\Constants\StateTypeConstant;
use App\Modules\Application\Constants\TransactionStatusConstant;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Beneficiary\Models\BeneficiaryBankDetails;
use App\Modules\Coupon\Models\CouponUsage;
use App\Modules\Distributor\Models\Company;
use App\Modules\Distributor\Models\Application;
use App\Modules\Distributor\Models\DistributorAccount;
use App\Modules\Distributor\Models\DistributorOffice;
use App\Modules\Distributor\Models\DistributorsAssign;
use App\Modules\Distributor\Models\DistributorTransaction;
use App\Modules\Distributor\Models\DistributorUser;
use App\Modules\Notification\Models\SendNotification;
use App\Modules\Referral\Constant\ReferralSystemConstant;
use App\Modules\Referral\Models\FreeServiceCharge;
use App\Modules\Referral\Models\Referral;
use App\Modules\Referral\Models\ReferralPoints;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\Sender;
use App\Modules\Sender\Models\SenderStatus;
use App\Modules\Settings\Models\Settings;
use App\Modules\SMS\Models\sms;
use App\Modules\Transaction\Constants\FreeServiceChargeConstant;
use App\Modules\Transaction\Models\Note;
use App\Modules\Transaction\Models\NoteAssign;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionBeneficiary;
use App\Modules\Transaction\Models\TransactionDetails;
use App\Modules\Transaction\Models\TransactionDocument;
use App\Modules\Transaction\Models\TransactionHistory;
use App\Modules\Transaction\Models\TransactionStatus;
use App\Modules\User\Models\AusStates;
use App\Modules\User\Models\ExchangeRate;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\User;
use App\Notifications\TransactionEditNotification;
use Illuminate\Support\Facades\Session;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;


class TransactionController extends BaseController
{

    /* Validation rules for sender create and edit */
    protected $rules = [
        'first_name' => 'required|min:2|max:55',
        'last_name' => 'required|min:2|max:55',
        'number' => 'required'
    ];

    protected $transaction;
    protected $beneficiary;
    protected $sender;
    protected $send_notification;

    function __construct(
        SendNotification $send_notification, Transaction $transaction, DistributorOffice $distributorOffice, Agent $agent, Sender $sender, Beneficiary $beneficiary, Request $request, TransactionDocument $transactionDocument,
        private GetApplicationService $getApplicationService,
    )
    {
        $this->send_notification = $send_notification;
        $this->beneficiary = $beneficiary;
        $this->sender = $sender;
        $this->agent = $agent;
        $this->transaction = $transaction;
        $this->request = $request;
        $this->transactionDocument = $transactionDocument;
        $this->distributorOffice = $distributorOffice;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStatusSumByAjax()
    {

        $status_id = $_GET['status'];

        $orders = DB::table('transaction_list1')->select('transaction_id', 'transactionDate', 'sender_name', 'beneficiary_name',
            'payment_type', 'account_name', 'account_no', 'bsb', 'bank_name', 'beneficiary_phone', 'totalAmount', 'serviceCharge', 'sendingAmount',
            'paymentAmount', 'exchangeRate', 'agentRate', 'agentCommission', 'addedBy', 'added_by', 'sender_id', 'beneficiary_id', 'status_id');
        if (Auth::user()->level_id == 3) {
            $orders = $orders->leftJoin('agent_transactions', 'agent_transactions.transactions_id', '=', 'transaction_id')
                ->where(function ($q) {
                    $q->Where('agent_transactions.agents_id', getAgentId());
                    $q->orWhere('added_by', Auth::user()->id);
                });
        }
        if (Auth::user()->level_id == 5) {
            $orders = $orders->where('transactions.added_by', Auth::user()->id);
        }
        if ($_GET['status'] == '') {
            $orders_aud = $orders->sum('totalAmount');
            $orders_npr = $orders->sum('paymentAmount');
            $status_name = 'All';
        } else {
            $orders_aud = $orders->where('status_id', $status_id)->sum('totalAmount');
            $orders_npr = $orders->where('status_id', $status_id)->sum('paymentAmount');
            $status_name = TransactionStatus::where('id', $status_id)->first();
            $status_name = $status_name->name;
        }
        /*    $orders_aud = $orders->where('status_id', $status_id)->sum('totalAmount');
            $orders_npr = $orders->where('status_id', $status_id)->sum('paymentAmount');
            $status_name = TransactionStatus::where('id', $status_id)->first();
        */
        return $this->success(['sum_aud' => $orders_aud, 'sum_npr' => $orders_npr, 'status_name' => $status_name, 'filteredStatus' => $status_id]);
    }

    public function orders(Request $request)
    {
        $reqarray = $this->request->all();
        $num = !empty($reqarray['num']) ? $reqarray['num'] : 10;

        //   $num = !empty($_GET['num']) ? $_GET['num'] : 10;
        $search = !empty($reqarray['search']) ? $reqarray['search'] : '';
        $getStatus = (isset($reqarray['status']) && ($reqarray['status'] != " ")) ? $reqarray['status'] : null;
        /*if(isset($reqarray['transaction_status'])){
            $transaction_status_id = $reqarray['transaction_status'];
        }elseif(isset()){

        }*/
        $transaction_status_id = (isset($reqarray['transactionStatusId'])) ? $reqarray['transactionStatusId'] : '';
        $added_by = isset($reqarray['addedBy']) ? $reqarray['addedBy'] : '';
        $from = isset($reqarray['from']) ? $reqarray['from'] : '';
        $to = isset($reqarray['to']) ? $reqarray['to'] : '';
        $distributor_id = isset($reqarray['distributorId']) ? $reqarray['distributorId'] : '';
        $sending_amount = isset($reqarray['sendingAmnt']) ? $reqarray['sendingAmnt'] : '';
        $senderName = isset($reqarray['senderName']) ? $reqarray['senderName'] : '';
        $beneficiaryName = isset($reqarray['beneficiaryName']) ? $reqarray['beneficiaryName'] : '';
        $paymentType = isset($reqarray['payment_type']) ? $reqarray['payment_type'] : null;
        $id_from = isset($reqarray['id_from']) ? $reqarray['id_from'] : '';
        $id_to = isset($reqarray['id_to']) ? $reqarray['id_to'] : '';
        $pgNum = $request->query('page');
        $status_list = TransactionStatus::pluck('id', 'name');
        return view("Transaction::" . $this->extra_folder . "orders", compact(/*'orders',*/
            'getStatus', 'num', 'search', 'pgNum', 'status_list', 'transaction_status_id', 'added_by', 'from', 'to', 'distributor_id', 'sending_amount', 'senderName', 'beneficiaryName', 'paymentType', 'id_from', 'id_to'));
    }

    public function getOrdersByAjax()
    {
        $transactionStatusId = !empty($_GET['transactionStatusId']) ? $_GET['transactionStatusId'] : '';

        //   $transaction_status = !empty($_GET['transactionStatusId'])?$_GET['transactionStatusId']:'';
        $addedBy = !empty($_GET['addedBy']) ? $_GET['addedBy'] : '';
        $from = !empty($_GET['from']) ? $_GET['from'] : '';
        $to = !empty($_GET['to']) ? $_GET['to'] : '';
        $distributorId = !empty($_GET['distributorId']) ? $_GET['distributorId'] : '';
        $sendingAmnt = !empty($_GET['sendingAmnt']) ? $_GET['sendingAmnt'] : '';
        $senderName = !empty($_GET['senderName']) ? $_GET['senderName'] : '';
        $beneficiaryName = !empty($_GET['beneficiaryName']) ? $_GET['beneficiaryName'] : '';
        $num = !empty($_GET['num']) ? $_GET['num'] : 10;
        $search = !empty($_GET['search']) ? $_GET['search'] : '';
        $getStatus = isset($_GET['status']) ? $_GET['status'] : null;
        $paymentType = isset($_GET['payment_type']) ? $_GET['payment_type'] : null;
        $id_from = !empty($_GET['id_from']) ? $_GET['id_from'] : '';
        $id_to = !empty($_GET['id_to']) ? $_GET['id_to'] : '';

        $orders = DB::table('transaction_list1')->select('transaction_id', 'transactionDate', 'transactionDate1', 'sender_phone', 'sender_name', 'beneficiary_name',
            'payment_type', 'account_name', 'account_no', 'bsb', 'bank_name', 'beneficiary_phone', 'totalAmount', 'serviceCharge', 'sendingAmount',
            'paymentAmount', 'exchangeRate', 'agentRate', 'agentCommission', 'addedBy', 'added_by', 'sender_id', 'beneficiary_id', 'status_id', 'pickup_district', 'is_verified');
//        if ($to == '' && $from == '') {
//            $orders = $orders->where('transactionDate', 'like', '%' . Date('Y') . '%');
//        }

        if (Auth::user()->level_id == 3) {
            $orders = $orders->where('agent_id', '=', getAgentId());
            /*$orders = $orders->leftJoin('agent_transactions', 'agent_transactions.transactions_id', '=', 'transaction_id')
                ->where(function ($q) {
                    $q->where('agent_transactions.agents_id', '=', getAgentId());
                    //$q->orWhere('added_by', Auth::user()->id);
                });*/
        }
        if (!empty($search)) {
            $orders = $orders->where(function ($q1) use ($search) {
                $q1->orWhere('sender_name', 'like', '%' . $search . '%');
                $q1->orWhere('beneficiary_name', 'like', '%' . $search . '%');
                $q1->orWhere('transaction_id', 'like', '%' . $search . '%');
            });
        }
        if (Auth::user()->level_id == 5) {
            $orders = $orders->where('added_by', Auth::user()->id);
        }
        if (isset($transactionStatusId) && $transactionStatusId != '') {

            $transactionStatusId = explode(',', $transactionStatusId);

            $orders = $orders->whereIn('status_id', $transactionStatusId);
        }

        if (isset($sendingAmnt) && $sendingAmnt != '') {

            $orders = $orders->where('sendingAmount', '=', $sendingAmnt);
        }
        if (isset($senderName) && $senderName != '') {

            $orders = $orders->where('sender_name', 'like', '%' . $senderName . '%');
        }
        if (isset($beneficiaryName) && $beneficiaryName != '') {

            $orders = $orders->where('beneficiary_name', 'like', '%' . $beneficiaryName . '%');
        }
        if (isset($paymentType) && $paymentType != '') {

            $orders = $orders->where('payment_type', 'like', $paymentType);
        }
        if ($addedBy != 0 || $addedBy != '') {
            $user_addedBy = User::where('id', $addedBy)->first();
            if ($user_addedBy?->level_id == 3) {
                $agent_ID = getAgentIdByUserId($user_addedBy->id);
                $orders = $orders->where('agent_id', $agent_ID);
                /*$orders = $orders->leftJoin('agent_transactions','agent_transactions.transactions_id','=','transaction_id')
                    ->where('agent_transactions.agents_id',$agent_ID);*/
            }
        }
        if ($distributorId != '') {
            $orders = $orders->leftJoin('assign_distributors', 'assign_distributors.transactions_id', '=', 'transaction_id')
                ->leftJoin('distributor_offices', 'distributor_offices.id', '=', 'assign_distributors.distributor_office_id')
                ->leftJoin('companies', 'companies.id', '=', 'distributor_offices.companies_id')
                ->where('companies.id', $distributorId);
        }
        if ((isset($to) && $to != '') && (isset($from) && $from != '')) {

            $from_date = Carbon::createFromFormat('d/m/Y', $from)->toDateTimeString();
            $to_date = Carbon::createFromFormat('d/m/Y', $to)->toDateTimeString();
            $date[0] = date('Y-m-d', strtotime($from_date));
            $date[1] = date('Y-m-d', strtotime($to_date));

            //  $orders = $orders->whereBetween('transactionDate1', array((string)$date[0], (string)$date[1]));
            $orders = $orders->whereBetween('transactionDate1', array($date[0], $date[1]));
        }
        if ((isset($from) && $from != '') && $to == '') {
            $from_date = Carbon::createFromFormat('d/m/Y', $from)->toDateTimeString();
            $date[0] = date('Y-m-d', strtotime($from_date));
            // $orders = $orders->where('transactionDate', (string)$date[0]);
            $orders = $orders->where('transactionDate1', $date[0]);
        }
        if ((isset($to) && $to != '') && $from == '') {
            $to_date = Carbon::createFromFormat('d/m/Y', $to)->toDateTimeString();
            $date[0] = date('Y-m-d', strtotime($to_date));
            // $orders = $orders->where('transactionDate', (string)$date[0]);
            $orders = $orders->where('transactionDate1', $date[0]);
        }
        if ((isset($id_to) && $id_to != '') && (isset($id_from) && $id_from != '')) {
            $id[0] = $id_from;
            $id[1] = $id_to;
            $orders = $orders->whereBetween('transaction_id', array($id[0], $id[1]));
        }

        if ((isset($id_to) && $id_to != '') && $id_from == '') {
            $id[0] = $id_to;
            $orders = $orders->where('transaction_id', '<=', $id[0]);
        }

        if ((isset($id_from) && $id_from != '') && $id_to == '') {
            $id[0] = $id_from;
            $orders = $orders->where('transaction_id', '>=', $id[0]);
        }

        $orders = $orders->orderBy('transaction_id', 'desc');
        /*if($_GET['status'] == ''){
            $orders_aud = $orders->sum('totalAmount');
            $orders_npr = $orders->sum('paymentAmount');
            $status_name='All';
        }else{
            $orders_aud = $orders->where('status_id', $status_id)->sum('totalAmount');
            $orders_npr = $orders->where('status_id', $status_id)->sum('paymentAmount');
            $status_name = TransactionStatus::where('id', $status_id)->first();
            $status_name = $status_name->name;
        }*/


        if (!empty($getStatus)) {

            $orders_aud = $orders->where('status_id', $_GET['status'])->sum('totalAmount');
            $orders_npr = $orders->where('status_id', $_GET['status'])->sum('paymentAmount');
            $status_name = TransactionStatus::where('id', $_GET['status'])->first();
            $status_name = $status_name->name;
            $orders = $orders->where('status_id', $_GET['status'])->paginate($num)->withPath(route('transactions.orders'));
        } else {

            $orders_aud = $orders->sum('totalAmount');
            $orders_npr = $orders->sum('paymentAmount');
            $status_name = 'All';

            $orders = $orders->paginate($num)->withPath(route('transactions.orders'));
        }
        $view = view("Transaction::" . $this->extra_folder . "ordersAjax", compact('orders'))->render();

        return response()->json(['html' => $view, 'orders_aud' => $orders_aud, 'orders_npr' => $orders_npr, 'status_name' => $status_name]);
        //  return view( compact('','orders_aud','orders_npr','status_name'));
    }

    public function dashboard()
    {
        if (!in_array(Auth::user()->level_id, [1, 6, 7, 8])) {
            abort(403, 'Unauthorized action.');
        }

        return view('Transaction::tracker/dashboard')->with('total_transaction_count', $this->get_total_transaction_count())
            ->with('today_admin_profit', $this->get_admin_profit())
            ->with('agent_average_rate', $this->getAgentAverageRate())
            ->with('client_average_rate', $this->getClientAverageRate());
    }

    public function rates()
    {
        if (!in_array(Auth::user()->level_id, [1, 2, 3, 6, 8])) {
            abort(403, 'Unauthorized action.');
        }
        $rates = ExchangeRate::orderBy("id", 'desc')->paginate(10);
        return view("Transaction::Rates/index", compact('rates'));
    }

    public function addRate()
    {
        if (!in_array(Auth::user()->level_id, [1, 8])) {
            abort(403, 'Unauthorized action.');
        }
        return view("Transaction::Rates/add");
    }

    public function editRate($id)
    {
        if (!in_array(Auth::user()->level_id, [1, 8])) {
            abort(403, 'Unauthorized action.');
        }
        $rates = ExchangeRate::where('id', $id)->first();
        return view("Transaction::Rates/edit", compact('rates'));
    }

    public function storeRate(Request $request)
    {

        $this->validate($request, [
            'exchange_rate' => 'required | numeric | gt:0',
            'cost_rate' => 'required | numeric | gt:0',
            'agent_rate' => 'required | numeric | gt:0',
            'threshold_amount' => 'required | numeric | gt:0'
        ]);
        ExchangeRate::create([
            'created_at' => get_today_date(),
            'exchange_rate' => $request->exchange_rate,
            'cost_rate' => $request->cost_rate,
            'agent_rate' => $request->agent_rate,
            'threshold_amount' => $request->threshold_amount,
        ]);
        $notification = array(
            'message' => 'Rate has been added successfully!',
            'alert-type' => 'success'
        );
        _sendPushNotification('Today rate', $request->exchange_rate);
        return redirect()->route('transactions.rates')->with($notification);

    }

    public function updateRate(Request $request, $id)
    {
        $request->validate([
            'exchange_rate' => 'required | numeric  | gt:0',
            'cost_rate' => 'required | numeric  | gt:0',
            'agent_rate' => 'required | numeric  | gt:0',
            'threshold_amount' => 'required | numeric  | gt:0'
        ]);
        ExchangeRate::where('id', $id)->update([
            'exchange_rate' => $request->exchange_rate,
            'cost_rate' => $request->cost_rate,
            'agent_rate' => $request->agent_rate,
            'threshold_amount' => $request->threshold_amount

        ]);

        $notification = array(
            'message' => 'Rate has been updated successfully!',
            'alert-type' => 'success'
        );
        _sendPushNotification('Today rate', $request->exchange_rate);
        return redirect()->route('transactions.rates')->with($notification);


    }

    public function transactionDataByAjax($status_id)
    {
        if (\request()->ajax()) {

            $result = DB::table('transaction_list')->select('transaction_id', 'transactionDate', 'sender_name', 'beneficiary_name'
                , 'totalAmount', 'serviceCharge', 'sendingAmount', 'paymentAmount', 'exchangeRate', 'agentRate', 'agentCommission', 'addedBy', 'companyName', 'added_by', 'sender_id', 'beneficiary_id');

            /* $result->where('status_id',$id);*/
            if (Auth::user()->level_id == 3) {
                $result = DB::table('transaction_list')->select('transaction_id', 'transactionDate', 'sender_name', 'beneficiary_name'
                    , 'totalAmount', 'serviceCharge', 'sendingAmount', 'paymentAmount', 'exchangeRate', 'agentRate', 'agentCommission', 'addedBy', 'added_by', 'sender_id', 'beneficiary_id')
                    ->where('added_by', Auth::user()->id);
            }
            if (Auth::user()->level_id == 5) {
                $result = DB::table('transaction_list')->select('transaction_id', 'transactionDate', 'sender_name', 'beneficiary_name'
                    , 'totalAmount', 'serviceCharge', 'sendingAmount', 'paymentAmount', 'exchangeRate', 'addedBy', 'added_by', 'sender_id', 'beneficiary_id')
                    ->where('added_by', Auth::user()->id);
            }
            if (Auth::user()->level_id == 4) {
                $distributorOffice = DB::table('distributor_users')->where('distributor_users.user_id', Auth::user()->id)->first();

                if ($distributorOffice->role_id == 2) {
                    $result = DB::table('transaction_list')->select('transaction_id', 'transactionDate', 'sender_name', 'beneficiary_name'
                        , 'assignedDistributor', 'paymentAmount', 'addedBy', 'beneficiary_phone', 'account_name', 'account_no', 'bsb', 'bank_name', 'assignedDistributorStaff', 'distributor_office_id', 'sender_id')
                        ->where('distributor_office_id', $distributorOffice->distributor_office_id)->where('assignedDistributorStaff', Auth::user()->id);
                } else {

                    $result = DB::table('transaction_list')->select('transaction_id', 'transactionDate', 'sender_name', 'beneficiary_name'
                        , 'assignedDistributor', 'paymentAmount', 'addedBy', 'beneficiary_phone', 'account_name', 'account_no', 'bsb', 'bank_name', 'assignedDistributorStaff', 'distributor_office_id', 'sender_id')
                        ->where('distributor_office_id', $distributorOffice->distributor_office_id);
                }
            }
            $result = $result->where('status_id', $status_id);
            return DataTables::of($result)
                ->addColumn('checkbox', function ($data) {
                    return '<input type="checkbox" value="' . $data->transaction_id . '" class="checks" />';
                })
                ->addColumn('action', function ($data) {

                    if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3 || Auth::user()->level_id == 5) {
                        $agent = Agent::where('user_id', $data->added_by)->first();
                        $user = User::leftJoin('person', 'person.id', '=', 'users.person_id')->where('users.id', '=', $data->added_by)->select('person.first_name', 'person.last_name', 'person.email')->first();

                        $client = User::where('level_id', 5)->where('id', $data->added_by)->first();
                        if (isset($agent)) {
                            $comment_button = ' <span style="position:relative;" data-toggle="tooltip" title="Add Comment"  data-placement="left"><button data-transaction="' . $data->transaction_id . '"  data-agent="' . $agent['user_id'] . '" data-user="' . $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')' . '"  data-toggle="modal" data-target="#comment-modal" class="btn btn-sm btn-success"><i
                                                                    class="fa fa-comment"></i></button><span class="label-comment alert-danger text-white" style="background: red!important;">' . getUnreadNotificationCount($data->transaction_id) . '</span></span>';
                        } elseif (isset($client)) {
                            $comment_button = ' <span style="position:relative;" data-toggle="tooltip" title="Add Comment"  data-placement="left"><button data-transaction="' . $data->transaction_id . '"  data-client="' . $client['id'] . '" data-user="' . $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')' . '"  data-toggle="modal" data-target="#comment-modal" class="btn btn-sm btn-success"><i
                                                                    class="fa fa-comment"></i></button><span class="label-comment alert-danger text-white" style="background: red!important;">' . getUnreadNotificationCount($data->transaction_id) . '</span></span>';
                        } else {
                            $comment_button = ' <span style="position:relative;" data-toggle="tooltip" title="Add Comment" data-placement="left"><button data-transaction="' . $data->transaction_id . '" data-agent="" data-client="" data-toggle="modal" data-target="#comment-modal" class="btn btn-sm btn-success"><i
                                                                    class="fa fa-comment"></i></button><span class="label-comment alert-danger text-white" style="background: red!important;">' . getUnreadNotificationCount($data->transaction_id) . '</span></span>';
                        }
                    } else {
                        $comment_button = '';
                    }
                    return '<a href="' . route('transactions.show', [$data->transaction_id]) . '" data-toggle="tooltip" data-placement="bottom" title="View"
                                   class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>' . $comment_button;
                })
                /*id="staff-notes-form" data-txn = "'. $data->transaction_id.'"
                action="' . route('transaction.adminStaffNotes.store', $data->transaction_id).'"*/
                ->addColumn('staff_notes', function ($data) {
                    $staff_notes = '<form  id="add-staffnote-form-' . $data->transaction_id . '" action="' . route('transaction.adminStaffNotes.store', $data->transaction_id) . '">'
                        . csrf_field() .
                        '<span style="min-width:100px;max-width:120px;">' .
                        /* '<div class="row col-md-12"><div class="col-md-9">'.*/
                        /*'<div class="form-group" style="margin-bottom:0;padding:4px;min-width:100px; max-width: 120px;">' .*/
                        '<textarea style="color:red;height: 35px; width:120px!important;" name="admin_staff_notes"  class="form-control"  placeholder="Enter ..." >' . getAdminStaffNote($data->transaction_id) . '</textarea>' .
                        /* '</div>' .*/
                        /* '</div>'.*/
                        '</span>' .
                        '<span>' .
                        /* '<div class="col-md-3">'.*/
                        '<button type="button" class="btn btn-primary btn-sm btn-flat add-staff-notes" data-txn="' . $data->transaction_id . '" style="margin-top: 10px;">Update</button>' .
                        /* '</div></div>'.*/
                        '</span>' .
                        '</form>';
                    return $staff_notes;
                })
                ->editColumn("transaction_id", function ($data) {
                    return format_id($data->transaction_id, "T");
                })
                ->editColumn("sender_name", function ($data) {
                    $sender_name = '<a href="' . route('sender.show', $data->sender_id) . '">' . $data->sender_name . '</a>';
                    return $sender_name;
                })->rawColumns(['action', 'checkbox', 'staff_notes', 'sender_name'])->make(true);
        }
    }

    public function allOrders()
    {

        if (\request()->ajax()) {
            $result = DB::table('transaction_list1')->select('transaction_id', 'transactionDate', 'sender_name', 'beneficiary_name'
                , 'totalAmount', 'serviceCharge', 'sendingAmount', 'paymentAmount', 'exchangeRate', 'agentRate', 'agentCommission', 'addedBy', 'added_by', 'sender_id', 'beneficiary_id', 'status_id');
            return DataTables::of($result)
                ->addColumn('checkbox', function ($data) {
                    return '<input type="checkbox" value="' . $data->transaction_id . '" class="checks" />';
                })
                ->addColumn('status', function ($data) {
                    return '<span><button class="btn-primary">' . getStatusName($data->status_id) . '</button></span>';
                })
                ->addColumn('action', function ($data) {
                    if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3 || Auth::user()->level_id == 5) {
                        $agent = Agent::where('user_id', $data->added_by)->first();
                        $user = User::leftJoin('person', 'person.id', '=', 'users.person_id')->where('users.id', '=', $data->added_by)->select('person.first_name', 'person.last_name', 'person.email')->first();

                        $client = User::where('level_id', 5)->where('id', $data->added_by)->first();
                        if (isset($agent)) {
                            $comment_button = ' <span style="position:relative;" data-toggle="tooltip" title="Add Comment"  data-placement="left"><button data-transaction="' . $data->transaction_id . '"  data-agent="' . $agent['user_id'] . '" data-user="' . $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')' . '"  data-toggle="modal" data-target="#comment-modal" class="btn btn-sm btn-success"><i
                                                                    class="fa fa-comment"></i></button><span class="label-comment alert-danger text-white" style="background: red!important;">' . getUnreadNotificationCount($data->transaction_id) . '</span></span>';
                        } elseif (isset($client)) {
                            $comment_button = ' <span style="position:relative;" data-toggle="tooltip" title="Add Comment"  data-placement="left"><button data-transaction="' . $data->transaction_id . '"  data-client="' . $client['id'] . '" data-user="' . $user->first_name . ' ' . $user->last_name . '(' . $user->email . ')' . '"  data-toggle="modal" data-target="#comment-modal" class="btn btn-sm btn-success"><i
                                                                    class="fa fa-comment"></i></button><span class="label-comment alert-danger text-white" style="background: red!important;">' . getUnreadNotificationCount($data->transaction_id) . '</span></span>';
                        } else {
                            $comment_button = ' <span style="position:relative;" data-toggle="tooltip" title="Add Comment" data-placement="left"><button data-transaction="' . $data->transaction_id . '" data-agent="" data-client="" data-toggle="modal" data-target="#comment-modal" class="btn btn-sm btn-success"><i
                                                                    class="fa fa-comment"></i></button><span class="label-comment alert-danger text-white" style="background: red!important;">' . getUnreadNotificationCount($data->transaction_id) . '</span></span>';
                        }
                    } else {
                        $comment_button = '';
                    }
                    return '<a href="' . route('transactions.show', [$data->transaction_id]) . '" data-toggle="tooltip" data-placement="bottom" title="View"
                                   class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>' . $comment_button;
                })
                ->addColumn('staff_notes', function ($data) {
                    $staff_notes = '<form  id="add-staffnote-form-' . $data->transaction_id . '" action="' . route('transaction.adminStaffNotes.store', $data->transaction_id) . '">'
                        . csrf_field() .
                        '<span style="min-width:100px;max-width:120px;">' .
                        '<textarea style="color:red;height: 35px; width:120px!important;" name="admin_staff_notes"  class="form-control"  placeholder="Enter ..." >' . getAdminStaffNote($data->transaction_id) . '</textarea>' .
                        '</span>' .
                        '<span>' .
                        '<button type="button" class="btn btn-primary btn-sm btn-flat add-staff-notes" data-txn="' . $data->transaction_id . '" style="margin-top: 10px;">Update</button>' .
                        '</span>' .
                        '</form>';
                    return $staff_notes;
                })->addColumn('distributor_id', function ($data) {
                    $assign_distributor = DistributorsAssign::where('transactions_id', $data->transaction_id);
                    if ($assign_distributor->count() == 1) {
                        $assigned_name = $assign_distributor->first();
                        $company = DistributorOffice::where('id', $assigned_name->distributor_office_id)->first();

                        $company_name = getDistributorOfficeName($company->companies_id);
                        $distributor = '<a data-toggle="modal" data-target="#sendmoney-modal" data-url="' . route('transactions.assign.distributors.edit', [$data->transaction_id]) . '" class="btn btn-xs btn-warning" title="Distributor">' . $company_name . '</a>';
                    } elseif ($assign_distributor->count() > 1) {

                        $distributor = '<a data-toggle="modal" data-target="#sendmoney-modal" data-url="' . route('transactions.assign.distributors.edit', [$data->transaction_id]) . '" class="btn btn-xs btn-warning" title="Distributor">Multiple</a>';
                    } else {
                        $distributor = ' <a data-transaction-id="' . $data->transaction_id . '" data-sending-amount="' . $data->sendingAmount . '" data-toggle="modal" data-target="#assignDistributor" href="#" class="btn btn-xs btn-warning assign-distributor" title="Assign Distributor">Assign Distributor</a>';
                    }

                    return $distributor;

                })
                ->editColumn("transaction_id", function ($data) {
                    return format_id($data->transaction_id, "T");
                })
                ->editColumn("sender_name", function ($data) {
                    $sender_name = '<a href="' . route('sender.show', $data->sender_id) . '">' . $data->sender_name . '</a>';
                    return $sender_name;
                })->rawColumns(['action', 'checkbox', 'staff_notes', 'sender_name', 'status', 'distributor_id'])->make(true);
        }
    }

    public function editAssignDistributors($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);
        $transactionDetail = TransactionDetails::find($transaction->transaction_details_id);
        $assigned = DistributorsAssign::where('transactions_id', $transaction_id)->get();
        $transaction_total = DistributorsAssign::where('transactions_id', $transaction_id)->sum('amount');
        return view('Transaction::modals/editDistributorAssignModal', compact('assigned', 'transaction_total', 'transactionDetail', 'transaction'));
    }

    public function unconfirmed()
    {
        abort(403, 'Unauthorized action.');

        /*view query in eloquent form*/

        /* $transaction = Transaction::leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
             ->leftJoin('person as a', 'a.id', '=', 'senders.person_id')
             ->leftJoin('beneficiaries','beneficiaries.beneficiary_id','=','transactions.beneficiary_id')
             ->leftJoin('person as b', 'b.id', '=', 'beneficiaries.person_id')
             ->leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.bank_details_id', '=', 'transactions.beneficiaries_bank_details_id')
             ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
             ->leftJoin('person_phones', 'person_phones.person_id', '=', 'b.id')
             ->leftJoin('phones', 'phones.id', '=', 'person_phones.phones_id')
             ->leftJoin('users as u','u.id','=','transactions.added_by')
             ->leftJoin('person as w', 'w.id', '=', 'u.person_id')
             ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
             ->leftJoin('agent_transactions','agent_transactions.transactions_id','=','transactions.id')
             ->where('beneficiary_bank_details.current','=', 1)
             ->orderBy('transactions.id','desc')
             ->select('transaction_details.payment_type as payment_type',
                 'transactions.id as transaction_id',
                 DB::raw('DATE_FORMAT(transaction_details.transaction_date, "%d/%m/%Y") as transactionDate'),
                 DB::raw('CONCAT_WS(" ", a.first_name, NULLIF(a.middle_name,""), a.last_name) AS sender_name'),
                 DB::raw('CONCAT_WS(" ", b.first_name, NULLIF(b.middle_name,""), b.last_name) AS beneficiary_name'),
                 'transaction_details.total_to_pay as totalAmount','transaction_details.service_charge as serviceCharge',
                 'transaction_details.sending_amount as sendingAmount','transaction_details.payment_amount as paymentAmount',
                 'transaction_details.exchange_rate as exchangeRate','transactions.added_by as added_by',
                 DB::raw('CONCAT_WS(" ", w.first_name, NULLIF(w.middle_name,""), w.last_name) AS addedBy'),
                 'transactions.transaction_status_id as status_id',
                 'agent_transactions.exchange_rate as agentRate',
                 'agent_transactions.total_commission as agentCommission',
                 'phones.number as beneficiary_phone', 'bank_details.account_name as account_name',
                 'bank_details.account_no as account_no', 'bank_details.bsb as bsb',
                 'bank_details.bank_name as bank_name','transactions.sender_id as sender_id',
                 'transactions.beneficiary_id as beneficiary_id');
            */


    }

    public function confirmed()
    {
        abort(403, 'Unauthorized action.');

        $data['sum'] = $this->transaction->getSum(2);
        return view("Transaction::tracker/confirmed", $data);
    }

    public function sendForCollection()
    {
        abort(403, 'Unauthorized action.');

        // $data['sendForCollection'] = $this->transaction->getAll(3);
        $data['sum'] = $this->transaction->getSum(3);
        $data['distributors'] = $this->distributorOffice->getDistributorOffice();
        if (Auth::user()->level_id == 4) {
            $data['distributors_staffs'] = getDistributorStaff();
        }


        return view("Transaction::tracker/sentForCollection", $data);
    }

    public function paymentInProgress()
    {
        abort(403, 'Unauthorized action.');

        $data['sum'] = $this->transaction->getSum(4);
        $data['distributors'] = $this->distributorOffice->getDistributorOffice();
        if (Auth::user()->level_id == 4) {
            $data['distributors_staffs'] = getDistributorStaff();
        }

        return view("Transaction::tracker/paymentInProgress", $data);
    }

    public function delivered()
    {
        abort(403, 'Unauthorized action.');

        /* $data['delivered'] = $this->transaction->getAll(5);*/
        $data['sum'] = $this->transaction->getSum(5);
        $data['distributors'] = $this->distributorOffice->getDistributorOffice();
        if (Auth::user()->level_id == 4) {
            $data['distributors_staffs'] = getDistributorStaff();
        }

        return view("Transaction::tracker/delivered", $data);
    }

    public function cancelled()
    {
        abort(403, 'Unauthorized action.');

        $data['cancelled'] = $this->transaction->getAll(6);
        $data['sum'] = $this->transaction->getSum(6);
        $data['distributors'] = $this->distributorOffice->getDistributorOffice();
        if (Auth::user()->level_id == 4) {
            $data['distributors_staffs'] = getDistributorStaff();
        }

        return view("Transaction::tracker/cancelled", $data);
    }

    public function onHold()
    {
        abort(403, 'Unauthorized action.');

        $data['onHold'] = $this->transaction->getAll(7);
        $data['sum'] = $this->transaction->getSum(7);
        $data['distributors'] = $this->distributorOffice->getDistributorOffice();
        if (Auth::user()->level_id == 4) {
            $data['distributors_staffs'] = getDistributorStaff();
        }

        return view("Transaction::tracker/onHold", $data);
    }

    public function edittransaction($transaction_id)
    {

        $data['transaction'] = $this->transaction->getDetailsbyid($transaction_id);
        $sender_details = getSenderDetails($data['transaction']->sender_id);
        $data['sender_block'] = [$sender_details->sender_id => $sender_details->full_name . ' | ' . $sender_details->email . ' | ' . $sender_details->number . ' | + ' . get_user_name($sender_details->added_by)];


        return view("Transaction::modals/editTransactionModal", $data);
    }

    /*public function viewReceiptByTransactionId($id)
    {
        $documents = TransactionDocument::where('transactions_id', $id)->orderBy('id', 'desc')->get();

        return view("Transaction::modals/viewReceipt",compact('documents'));
    }*/
    public function saveedittransaction($transaction_id, Request $request)
    {
        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $transaction = Transaction::find($transaction_id);
                $transaction->pickup_district = $request->pickup_district;
                $transaction->sender_id = $request->sender_id;
                $transaction->beneficiary_id = $request->beneficiary_id;
                $sender = Sender::find($request->sender_id);
                $senderAddress = PersonAddress::where('person_id', $sender->person_id)->first();
                $senderPhone = PersonPhone::where('person_id', $sender->person_id)->first();

                $beneficiary = Beneficiary::find($request->beneficiary_id);
                $beneficiaryAddress = PersonAddress::where('person_id', $beneficiary->person_id)->first();
                $beneficiaryPhone = PersonPhone::where('person_id', $beneficiary->person_id)->first();

                $transaction->sender_addresses_id = $senderAddress->address_id;
                $transaction->sender_phones_id = $senderPhone->phones_id;
                $transaction->beneficiary_phones_id = $beneficiaryPhone->phones_id;
                $transaction->beneficiary_addresses_id = $beneficiaryAddress->address_id;
                $sender_identification_id = Identification::where('senders_id', $request->sender_id)->first();


                $transaction->sender_identification_id = $sender_identification_id->identification_id;

                $beneficiaryBankDetail = BeneficiaryBankDetails::where('beneficiaries_beneficiary_id', $beneficiary->beneficiary_id)->where('current', 1)->first();

                $transaction->beneficiaries_bank_details_id = $beneficiaryBankDetail->bank_details_id;

                $transaction->save();

                $transactionDetail = TransactionDetails::find($transaction->transaction_details_id);


                $transactionDetail->sending_amount = $request->sending_amount;
                $transactionDetail->exchange_rate = $request->exchange_rate;
                $transactionDetail->payment_amount = $request->payment_amount;
                //  $transactionDetail->purpose_of_transfer = $request->purpose;
                $transactionDetail->payment_type = $request->payment_type;
                $transactionDetail->service_charge = $request->service_charge;
                $transactionDetail->total_to_pay = "" . ($request->sending_amount + $request->service_charge);
                // $transactionDetail->staff_notes = $request->staff_notes;


                $transactionDetail->save();


                if ($request->receipt) {
                    $receipt = $this->transactionDocument->multipleReceiptUpload($request->all(), $transaction_id);
                }
                $agentTransaction = AgentTransaction::where('transactions_id', $transaction_id)->first();
                if ($agentTransaction) {
                    //  $rate = ExchangeRate::orderBy('id', 'desc')->first();
                    $agent_exchange_rate = Agent::leftJoin('agent_exchange_rate', 'agent_exchange_rate.id', '=', 'agents.agent_exchange_rate_id')->where('agents.id', $agentTransaction->agents_id)->select('agent_exchange_rate.less_than_service_charge')->first();

                    $commission = ($request->sending_amount * ($agentTransaction->exchange_rate - $request->exchange_rate)) / $agentTransaction->exchange_rate;
                    $agentTransaction->total_commission = "" . (round($commission, 2) + ($request->service_charge - $agent_exchange_rate->less_than_service_charge));
                    $agentTransaction->save();
                }

                $user = User::find($transaction->added_by);
                if ($user) {
                    $message = 'Admin updated your Transaction( ' . format_id($transaction_id, "T") . ' )';
                    $user->notify(new TransactionEditNotification($message, $transaction_id));
                }

                DB::commit();

                return $this->success(['message' => '1']);
            } catch (\Exception $e) {
                DB::rollback();
                $message = 'Sorry Transaction could not be updated.';

                if (env('APP_DEBUG'))

                    $message = $e->getMessage() . 'Line #' . $e->getLine();
                /*Flash::error()->important();
                */
                return $this->success(['message' => '2']);


                /* flash($message)->error()->important();*/

            }

        } else {
            DB::beginTransaction();
            try {
                $transaction = Transaction::find($transaction_id);
                $transaction->pickup_district = $request->pickup_district;
                $transaction->sender_id = $request->sender_id;
                $transaction->beneficiary_id = $request->beneficiary_id;
                $sender = Sender::find($request->sender_id);
                $senderAddress = PersonAddress::where('person_id', $sender->person_id)->first();
                $senderPhone = PersonPhone::where('person_id', $sender->person_id)->first();

                $beneficiary = Beneficiary::find($request->beneficiary_id);
                $beneficiaryAddress = PersonAddress::where('person_id', $beneficiary->person_id)->first();
                $beneficiaryPhone = PersonPhone::where('person_id', $beneficiary->person_id)->first();

                $transaction->sender_addresses_id = $senderAddress->address_id;
                $transaction->sender_phones_id = $senderPhone->phones_id;
                $transaction->beneficiary_phones_id = $beneficiaryPhone->phones_id;
                $transaction->beneficiary_addresses_id = $beneficiaryAddress->address_id;
                $sender_identification_id = Identification::where('senders_id', $request->sender_id)->first();


                $transaction->sender_identification_id = $sender_identification_id->identification_id;

                $beneficiaryBankDetail = BeneficiaryBankDetails::where('beneficiaries_beneficiary_id', $beneficiary->beneficiary_id)->where('current', 1)->first();

                $transaction->beneficiaries_bank_details_id = $beneficiaryBankDetail->bank_details_id;

                $transaction->save();

                $transactionDetail = TransactionDetails::find($transaction->transaction_details_id);


                $transactionDetail->sending_amount = $request->sending_amount;
                $transactionDetail->exchange_rate = $request->exchange_rate;
                $transactionDetail->payment_amount = $request->payment_amount;
                //  $transactionDetail->purpose_of_transfer = $request->purpose;
                $transactionDetail->payment_type = $request->payment_type;
                $transactionDetail->service_charge = $request->service_charge;
                $transactionDetail->total_to_pay = "" . ($request->sending_amount + $request->service_charge);
                // $transactionDetail->staff_notes = $request->staff_notes;


                $transactionDetail->save();


                if ($request->receipt) {
                    $receipt = $this->transactionDocument->multipleReceiptUpload($request->all(), $transaction_id);
                }
                $agentTransaction = AgentTransaction::where('transactions_id', $transaction_id)->first();
                if ($agentTransaction) {
                    //  $rate = ExchangeRate::orderBy('id', 'desc')->first();
                    $agent_exchange_rate = Agent::leftJoin('agent_exchange_rate', 'agent_exchange_rate.id', '=', 'agents.agent_exchange_rate_id')->where('agents.id', $agentTransaction->agents_id)->select('agent_exchange_rate.less_than_service_charge')->first();

                    $commission = ($request->sending_amount * ($agentTransaction->exchange_rate - $request->exchange_rate)) / $agentTransaction->exchange_rate;
                    $agentTransaction->total_commission = "" . (round($commission, 2) + ($request->service_charge - $agent_exchange_rate->less_than_service_charge));
                    $agentTransaction->save();
                }
                DB::commit();

                $notification = array(
                    'message' => 'Transaction has been updated successfully!',
                    'alert-type' => 'success',
                );


            } catch (\Exception $e) {
                DB::rollback();
                $message = 'Sorry Transaction could not be updated.';

                if (env('APP_DEBUG'))

                    $message = $e->getMessage() . 'Line #' . $e->getLine();
                /*Flash::error()->important();
                */
                $notification = array(
                    'message' => 'Sorry Transaction could not be updated!',
                    'alert-type' => 'error',
                );


                /* flash($message)->error()->important();*/

            }
            return redirect()->back()->with($notification);

        }
    }

    public function MultipleDistributorAssignModal()
    {

        $ids = $_GET['ids'];
        return view("Transaction::modals/assignDistributorMultiple", compact('ids'));
    }

    public function StoreMultipleDistributorAssign(Request $request)
    {
        if ($request->ids == null) {

            return $this->success(['message' => 'error']);
        } else {
            $ids = explode(',', $request->ids);
            foreach ($ids as $key => $value) {
                $tran = Transaction::where('id', $value)->first();
                if ($tran) {
                    $t_details = TransactionDetails::where('transaction_details_id', $tran->transaction_details_id)->first();
                }
                $assigned_distributor = DistributorsAssign::where('transactions_id', $value);
                if ($assigned_distributor) {
                    $assigned_distributor->delete();
                }
                $distributor_tran = DistributorTransaction::where('transaction_id', $value)->get();
                if ($distributor_tran) {
                    foreach ($distributor_tran as $dis) {
                        DistributorAccount::where('distributor_transactions_id', $dis->id)->delete();
                        DistributorTransaction::where('transaction_id', $dis->transaction_id)->delete();
                    }
                }
                if ($request->distributor_id != 0) {


                    $distributor_office_ide = DistributorOffice::where('companies_id', $request->distributor_id)->first();
                    DistributorsAssign::create([
                        'transactions_id' => $value,
                        'distributor_office_id' => $distributor_office_ide->id,
                        'amount' => "" . $t_details->payment_amount
                    ]);

                    $distributorTransaction['transaction_id'] = $value;
                    $distributorTransaction['distributor_office_id'] = $distributor_office_ide->id;
                    $distributorTransaction['cost_rate'] = $t_details->cost_rate;
                    $tra = DistributorTransaction::create($distributorTransaction);
                    $distributors_accounts = new DistributorAccount();
                    $distributors_accounts->distributor_transactions_id = $tra->id;
                    $distributors_accounts->created_at = $tran->created_at;
                    $distributors_accounts->save();
                }
            }

            return $this->success(['message' => 'success']);
        }
    }

    public function CostRateModal()
    {

        $ids = $_GET['ids'];
        return view("Transaction::modals/updateCostRate", compact('ids'));
    }

    public function StoreCostRateModal(Request $request)
    {
        if ($request->ids == null) {

            return $this->success(['message' => 'error']);
            //return redirect()->back()->with($notification);
        } else {
            $ids = explode(',', $request->ids);

            foreach ($ids as $key => $value) {
                $transaction = Transaction::where('id', $value)->first();
                $transaction_details = TransactionDetails::where('transaction_details_id', $transaction->transaction_details_id)->first();
                $transaction_details->cost_rate = $request->cost_rate;


                $transaction_details->save();
                $distributor_transaction = DistributorTransaction::where('transaction_id', $value)->first();
                if ($distributor_transaction) {
                    $distributor_transaction->cost_rate = $request->cost_rate;
                    $distributor_transaction->save();
                }/*else{
                    $notification = array(
                        'message' => 'Something went wrong !',
                        'alert-type' => 'error',
                    );
                    return redirect()->back()->with($notification);
                }*/
            }

            return $this->success(['message' => 'success']);
            //return redirect()->back()->with($notification);
        }

    }


    public function AgentRateModal()
    {
        $ids = $_GET['ids'];
        return view("Transaction::modals/updateAgentRate", compact('ids'));
    }

    public function StoreAgentRateModal(Request $request)
    {
        if ($request->ids == null) {

            return $this->success(['message' => 'no transaction selected']);
        } else {
            $ids = explode(',', $request->ids);
            foreach ($ids as $key => $value) {
                $transaction = Transaction::where('id', $value)->first();
                $transaction_details = TransactionDetails::where('transaction_details_id', $transaction->transaction_details_id)->first();
                $agent_transaction = AgentTransaction::where('transactions_id', $value)->first();
                if ($agent_transaction) {
                    $agent_transaction->exchange_rate = $request->agent_rate;
                    $commission = ($transaction_details->sending_amount * ($request->agent_rate - $transaction_details->exchange_rate)) / $request->agent_rate;

                    $agent_transaction->total_commission = "" . (round($commission, 2) + ($transaction_details->service_charge - 3));
                    $agent_transaction->save();
                } else {
                    $notification = array(
                        'message' => 'Cannot update. No agent were assigned to this transaction!',
                        'alert-type' => 'error',
                    );

                    return $this->success(['message' => 'error']);
                }

            }
            return $this->success(['message' => '1']);
        }

    }

    public function orderStatusModal()
    {
        $ids = $_GET['ids'];
        return view("Transaction::modals/updateOrderStatus", compact('ids'));
    }

    public function updateOrderStatusModal(Request $request)
    {

        if ($request->ids == null) {
            $notification = array(
                'message' => 'No transaction selected!',
                'alert-type' => 'error',
            );
            return $this->success(['message' => 'error']);
        } else {
            $ids = explode(',', $request->ids);

            foreach ($ids as $key => $value) {

                $transaction = Transaction::where('id', $value)->first();
                if ($request->status_id == 10) {
                    $referral = Referral::where('user_id', $transaction->added_by)->where('status', 0)->first();
                    $freeServiceChargeAlreadyExists = FreeServiceCharge::where('transaction_id', $transaction->id)->first();
                    if (isset($referral) && !$freeServiceChargeAlreadyExists) {
                        // claim the referral
                        $referral->status = 1;
                        $referral->save();
                        if ($referral->referral_system === ReferralSystemConstant::NEW) {
                            //new referral reward
                            FreeServiceCharge::create([
                                'referrer_user_id' => $referral->referrer_id,
                                'referred_user_id' => $referral->user_id,
                                'transaction_id' => $transaction->id,
                                'used' => FreeServiceChargeConstant::NOT_USED
                            ]);
                        } else {
                            // claim all old referrals to a single free referral charge
                            $referrerId = $referral->referrer_id;
                            Referral::where(
                                [
                                    'referrer_id' => $referrerId,
                                    'status' => 0,
                                    'referral_system' => ReferralSystemConstant::OLD
                                ]
                            )->update(['status' => 1]);
                            FreeServiceCharge::create([
                                'referrer_user_id' => $referrerId,
                                'referred_user_id' => $referral->user_id,
                                'transaction_id' => $transaction->id,
                                'used' => FreeServiceChargeConstant::NOT_USED
                            ]);


//                            // old referral reward.
//                            $application = getAppDetailsForWeb();
//                            ReferralPoints::create([
//                                'date' => Carbon::now(),
//                                'points' => $application->discount_percent,
//                                'description' => 'Client Referral Points',
//                                'claimed_by' => $referral->referrer_id,
//                                'transaction_id' => $transaction->id,
//                            ]);
                        }


                    }
                    //$this->transaction->sendDeliveredEmail($transaction);
                }
                if($request->status_id == TransactionStatusConstant::TRANSACTION_IN_REVIEW){
                    $transaction->sendTransactionInReviewMail($transaction);
                }
                if ($transaction) {
                    $transaction->transaction_status_id = $request->status_id;
                    $transaction->save();
                }
                if ($request->status_id == 10 || $request->status_id == 7 || $request->status_id == 5) {
                    $not['title'] = 'Transaction status changed';
                    $not['notification_message'] = 'Transaction (' . format_id($value, 'T') . ') status changed to ' . getStatusName($request->status_id) . '.';

                    $this->transaction->createNotification($value, $not);
                }


            }

            return $this->success(['message' => 'success']);
        }

    }

    public function changeStatusModal($transaction_id)
    {
        return view("Transaction::modals/changeTransactionStatus", compact('transaction_id'));
    }

    public function changeStatusModalStore(Request $request)
    {
        $tran = Transaction::find($request->transaction_id);
        $tran->transaction_status_id = $request->status_id;
        $tran->save();
        if ($request->status_id == 10) {
            //  $this->transaction->sendDeliveredEmail($tran);
            $referral = Referral::where('user_id', $tran->added_by)->where('status', 0)->first();
            $freeServiceChargeAlreadyExists = FreeServiceCharge::where('transaction_id', $tran->id)->first();
            if (isset($referral) && !$freeServiceChargeAlreadyExists) {
                $referral->status = 1;
                $referral->save();
                if ($referral->referral_system === ReferralSystemConstant::NEW) {
                    //new referral reward
                    FreeServiceCharge::create([
                        'referrer_user_id' => $referral->referrer_id,
                        'referred_user_id' => $referral->user_id,
                        'transaction_id' => $tran->id,
                        'used' => FreeServiceChargeConstant::NOT_USED
                    ]);
                } else {
                    // claim all old referrals to a single free referral charge
                    $referrerId = $referral->referrer_id;
                    Referral::where(
                        [
                            'referrer_id' => $referrerId,
                            'status' => 0,
                            'referral_system' => ReferralSystemConstant::OLD
                        ]
                    )->update(['status' => 1]);
                    FreeServiceCharge::create([
                        'referrer_user_id' => $referrerId,
                        'referred_user_id' => $referral->user_id,
                        'transaction_id' => $tran->id,
                        'used' => FreeServiceChargeConstant::NOT_USED
                    ]);


                    // old referral
//                $application = getAppDetailsForWeb();
//                ReferralPoints::create([
//                    'date' => Carbon::now(),
//                    'points' => $application->discount_percent,
//                    'description' => 'Client Referral Points',
//                    'claimed_by' => $referral->referrer_id,
//                    'transaction_id' => $tran->id,
//                ]);
                }


            }

        }
        if($request->status_id == TransactionStatusConstant::TRANSACTION_IN_REVIEW){
            $tran->sendTransactionInReviewMail($tran);
        }
        if ($request->status_id != 14 && $request->status_id != 10 && $request->status_id != 12) {
            TransactionHistory::create(['transaction_id' => $request->transaction_id, 'status' => 'Processed in Nepal']);
        } elseif ($request->status_id != 14 && $request->status_id == 10 && $request->status_id != 12) {
            TransactionHistory::create(['transaction_id' => $request->transaction_id, 'status' => 'Paid in Nepal']);
        } elseif ($request->status_id == 12) {
            TransactionHistory::create(['transaction_id' => $request->transaction_id, 'status' => 'Cancelled']);
        }
        if ($request->status_id == 10 || $request->status_id == 7) {
            $not['title'] = 'Transaction status changed';
            $not['notification_message'] = 'Transaction (' . format_id($request->transaction_id, 'T') . ') status changed to ' . getStatusName($request->status_id) . '.';
            $this->transaction->createNotification($request->transaction_id, $not);
        }
        return $this->success();
    }

    public function changeStatus($transaction_id, $status_id)
    {
        $tran = Transaction::find($transaction_id);
        $tran->transaction_status_id = $status_id;
        $tran->save();

        if ($status_id == 1) {
            return redirect()->route('transactions.tracker.unconfirmed');
        }
        if ($status_id == 2) {
            return redirect()->route('transactions.tracker.confirmed');
        }
        if ($status_id == 3) {
            return redirect()->route('transactions.tracker.sendForCollection');
        }
        if ($status_id == 4) {
            return redirect()->route('transactions.tracker.paymentInProgress');
        }
        if ($status_id == 5) {
            $beneficiaryName = getBeneficiaryName($tran->beneficiary_id);
            $beneficiaryDetails = getBeneficiaryDetails($tran->beneficiary_id);
            $senderDetails = getSenderDetails($tran->sender_id);
            $senderName = getSenderName($tran->sender_id);
            $transactionDetail = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transactions.id', $transaction_id)->first();
            $body = ' We have successfully transferred the payment to the Beneficiary. Here are the transaction details below.<br><br> Beneficiary Details:<br>
                    Name : ' . $beneficiaryName . '<br>
                    Phone No : ' . $beneficiaryDetails['number'] . '<br>
                    Payment Amount : NPR  ' . $transactionDetail->payment_amount . '<br>
                    Payment Type : ' . $transactionDetail->payment_type . ' <br>
                    Account Name : ' . $beneficiaryDetails['account_name'] . '<br>
                    Account No : ' . $beneficiaryDetails['account_no'] . ' <br>
                    BSB : ' . $beneficiaryDetails['bsb'] . ' <br>
                    Bank Name : ' . $beneficiaryDetails['bank_name'] . '<br>';

            $params = [
                'to' => strtolower($senderDetails['email']),
                'toName' => $senderName,
                'body' => $body,
                'subject' => 'Transaction Delivered',
                'fromEmail' => env('MAIL_FROM_ADDRESS'),
                'fromName' => env('FROM_NAME', $this->getApplicationService->getApplication()->name),
            ];
            Mail::send('Transaction::Email/transactionDelivered', $params, function ($message) use ($params) {
                $message->to($params['to'], $params['toName'])
                    ->from($params['fromEmail'], $params['fromName'])
                    ->subject($params['subject']);
            });

            $notification = array(
                'message' => 'Transaction has been Delivered successfully!',
                'alert-type' => 'success',
            );

            return redirect()->route('transactions.tracker.delivered')->with($notification);
        }
        if ($status_id == 6) {
            return redirect()->route('transactions.tracker.cancelled');
        }
        if ($status_id == 7) {
            return redirect()->route('transactions.tracker.onHold');
        }
    }

    public function sendSMS($content)
    {
        $ch = curl_init('https://api.smsbroadcast.com.au/api-adv.php');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function addSMSLog($transaction_id, $sender_id, $message, $credit_value)
    {
        $sender_details = Sender::where('id', $sender_id)->first();
        $person = Person::where('id', $sender_details->person_id)->first();
        $person_id = $person->id;
        sms::create([
            'transaction_id' => $transaction_id,
            'receiver_id' => $person_id,
            'message' => $message,
            'credit_value' => $credit_value,
            'send_from' => Auth::user()->id,
        ]);

    }

    public function changeStatusMultiple($status_id, Request $request)
    {
        $formData = $request->all();
        $tran = Transaction::whereIn('id', $formData)->get();


        foreach ($tran as $tran) {

            $tran->transaction_status_id = $status_id;
            $tran->save();
            if ($status_id == 5) {
                //send delivered email
                $beneficiaryName = getBeneficiaryName($tran->beneficiary_id);
                $beneficiaryDetails = getBeneficiaryDetails($tran->beneficiary_id);
                $senderDetails = getSenderDetails($tran->sender_id);
                $senderName = getSenderName($tran->sender_id);
                $transactionDetail = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                    ->where('transactions.id', $tran->id)->first();
                $body = ' We have successfully transferred the payment to the Beneficiary. Here are the transaction details below.<br><br> Beneficiary Details:<br>
                    Name : ' . $beneficiaryName . '<br>
                    Phone No : ' . $beneficiaryDetails['number'] . '<br>
                    Payment Amount : NPR  ' . $transactionDetail->payment_amount . '<br>
                    Payment Type : ' . $transactionDetail->payment_type . ' <br>
                    Account Name : ' . $beneficiaryDetails['account_name'] . '<br>
                    Account No : ' . $beneficiaryDetails['account_no'] . ' <br>
                    BSB : ' . $beneficiaryDetails['bsb'] . ' <br>
                    Bank Name : ' . $beneficiaryDetails['bank_name'] . '<br>';

                $params = [
                    'to' => strtolower($senderDetails['email']),
                    'toName' => $senderName,
                    'body' => $body,
                    'subject' => 'Transaction Delivered',
                    'fromEmail' => env('MAIL_FROM_ADDRESS'),
                    'fromName' => env('FROM_NAME', $this->getApplicationService->getApplication()->name),
                ];

                Mail::send('Transaction::Email/transactionDelivered', $params, function ($message) use ($params) {
                    $message->to($params['to'], $params['toName'])
                        ->from($params['fromEmail'], $params['fromName'])
                        ->subject($params['subject']);
                });


            }
        }
        $notification = array(
            'message' => 'Transaction status has been changed successfully!',
            'alert-type' => 'success',
        );
        // Flash::success('Status has been changed successfully.');
        if ($status_id == 1) {
            return redirect()->route('transactions.tracker.unconfirmed')->with($notification);
        }
        if ($status_id == 2) {
            return redirect()->route('transactions.tracker.confirmed')->with($notification);
        }
        if ($status_id == 3) {
            return redirect()->route('transactions.tracker.sendForCollection')->with($notification);
        }
        if ($status_id == 4) {
            return redirect()->route('transactions.tracker.paymentInProgress')->with($notification);
        }
        if ($status_id == 5) {
            return redirect()->route('transactions.tracker.delivered')->with($notification);
        }
        if ($status_id == 6) {
            return redirect()->route('transactions.tracker.cancelled')->with($notification);
        }
        if ($status_id == 7) {
            return redirect()->route('transactions.tracker.onHold')->with($notification);
        }

    }

    public function assignDistributors(Request $request)
    {
        if (request()->ajax()) {
            $distributor_id = $request->distributor_id;
            $amount_assigned = $request->amount;
            $transactionId = $request->transactionId;
            $totalAmt = $request->total_amount;
            if (count($distributor_id) != count(array_unique($distributor_id))) {
                return $this->fail(['message' => 'Cannot assign same distributor multiple times!']);
            } else if (array_sum($amount_assigned) != $totalAmt) {
                return $this->fail(['message' => 'Transaction amount does not match']);
            } else {
                DistributorsAssign::where('transactions_id', $transactionId)->delete();
                $tran = Transaction::where('id', $transactionId)->first();
                if ($tran) {
                    $t_details = TransactionDetails::where('transaction_details_id', $tran->transaction_details_id)->first();
                    $distributor_tran = DistributorTransaction::where('transaction_id', $transactionId)->pluck('id');
                    if ($distributor_tran) {
                        DistributorAccount::whereIn('distributor_transactions_id', $distributor_tran)->delete();
                        DistributorTransaction::where('transaction_id', $transactionId)->delete();
                    }
                    foreach ($distributor_id as $key => $value) {
                        if ($value != 0) {
                            $distributor_office_ide = DistributorOffice::where('companies_id', $value)->first();
                            if ($distributor_office_ide) {
                                DistributorsAssign::create([
                                    'transactions_id' => $transactionId,
                                    'distributor_office_id' => $distributor_office_ide->id,
                                    'amount' => $amount_assigned[$key],
                                ]);
                                $tra = DistributorTransaction::create([
                                    'transaction_id' => $transactionId,
                                    'distributor_office_id' => $distributor_office_ide->id,
                                    'cost_rate' => $t_details->cost_rate
                                ]);
                                $distributors_accounts = DistributorAccount::create([
                                    'distributor_transactions_id' => $tra->id,
                                    'created_at' => $tran->created_at
                                ]);
                            }
                        }
                    }
                    return $this->success(['message' => 'Distributor assigned!']);
                }
            }
        }
    }

    public function assignAgent($transaction_id)
    {
        $data = AgentTransaction::where('transactions_id', $transaction_id)->first();

        return view("Transaction::modals/assignAgent", compact('transaction_id', 'data'));
    }

    public function storeAssignAgent(Request $request)
    {

        if (request()->ajax()) {

            $agent_id = $request->agent_id;
            $transactionId = $request->transaction_id;
            $transaction = Transaction::where('id', $transactionId)->first();
            $transaction_details = TransactionDetails::where('transaction_details_id', $transaction->transaction_details_id)->first();
            $agent_transaction = AgentTransaction::where('transactions_id', $transactionId)->first();
            if ($agent_transaction) {
                $agent_tran = AgentAccount::where('agent_transactions_id', $agent_transaction->id)->first();
                if ($agent_tran) {
                    $agent_tran->delete();

                }
                $agent_transaction->delete();
            }
            $rate = ExchangeRate::orderBy('id', 'desc')->first();
            $commission = ($transaction_details->sending_amount * ($rate->agent_rate - $transaction_details->exchange_rate)) / $rate->agent_rate;
            $agent_exchange_rate = Agent::leftJoin('agent_exchange_rate', 'agent_exchange_rate.id', '=', 'agents.agent_exchange_rate_id')->where('agents.id', $agent_id)->select('agent_exchange_rate.less_than_service_charge')->first();
            if ($request->agent_id != 0) {
                $agentTran = AgentTransaction::create([
                    'transactions_id' => $transactionId,
                    'agents_id' => $agent_id,
                    'total_commission' => "" . (round($commission, 2) + ($transaction_details->service_charge - $agent_exchange_rate->less_than_service_charge)),
                    'exchange_rate' => $rate->agent_rate
                ]);

                AgentAccount::create([
                    'agent_transactions_id' => $agentTran->id,
                    'agent_payments_id' => null,
                    'created_at' => get_today_date()
                ]);
            }
        }
        return $this->success();
    }


    public function assignDistributorMultiple($company_id, Request $request)
    {
        $formData = $request->all();
        $tran = Transaction::whereIn('id', $formData)->get();

        foreach ($tran as $tran) {
            $distributorOffice = DistributorOffice::where('companies_id', $company_id)->first();

            $distributor_transaction = DistributorTransaction::where('transaction_id', $tran->id)->first();


            if (isset($distributor_transaction)) {

                $distributor_transaction->distributor_office_id = $distributorOffice->id;
                $distributor_transaction->save();
                $check = DistributorAccount::where('distributor_transactions_id', $distributor_transaction->id)->first();
                if (isset($check)) {
                    $check->distributor_transactions_id = $distributor_transaction->id;
                    $check->save();
                } else {

                    $distributors_accounts = new DistributorAccount();
                    $distributors_accounts->distributor_transactions_id = $distributor_transaction->id;
                    $distributors_accounts->created_at = format_date($tran->created_at);
                    $distributors_accounts->save();
                }
            } else {
                $distributorTransaction['transaction_id'] = $tran->id;
                $distributorTransaction['distributor_office_id'] = $distributorOffice->id;
                $tra = DistributorTransaction::create($distributorTransaction);
                $distributors_accounts = new DistributorAccount();
                $distributors_accounts->distributor_transactions_id = $tra->id;
                $distributors_accounts->created_at = format_date($tran->created_at);
                $distributors_accounts->save();

            }

        }
        $notification = array(
            'message' => 'Distributor has been assign successfully!',
            'alert-type' => 'success',
        );

        // Flash::success('Distributor has been assign successfully.');
        return redirect()->back()->with($notification);
    }

    public function assignStaffMultiple($user_id, Request $request)
    {
        $formData = $request->all();
        $tran = Transaction::whereIn('id', $formData)->get();
        foreach ($tran as $tran) {
            $distributor_transaction = DistributorTransaction::where('transaction_id', $tran->id)->first();

            $distributor_transaction->assigned_by = current_user_id();
            $distributor_transaction->assigned_to = $user_id;
            $distributor_transaction->save();
        }
        Flash::success('Distributor Staff has been assign successfully.');
        return redirect()->back();
    }

    public function viewSearch()
    {
        if (in_array(Auth::user()->level_id, [5])) {
            abort(403, 'Unauthorized action.');
        }
        $data['senders'] = $this->sender->getAll();
        $data['beneficiaries'] = $this->beneficiary->getAll();
        $data['distributors'] = Company::all();


        if (Auth::user()->level_id == 4) {
            $data['status'] = DB::table('status')
                ->where('id', 3)
                ->orWhere('id', 4)
                ->orWhere('id', 5)
                ->orWhere('id', 6)
                ->orWhere('id', 7)
                ->get();

        } else {
            $data['status'] = DB::table('status')->get();
        }
        if (in_array(Auth::user()->level_id, [1, 2, 6, 7, 8])) {
            $data['added_by'] = User::join('person', 'person.id', '=', 'users.person_id')->select('users.id', 'person.first_name', 'person.last_name')->get();
        }
        if (Auth::user()->level_id == 3) {
            $data['added_by'] = User::join('person', 'person.id', '=', 'users.person_id')->select('users.id', 'person.first_name', 'person.last_name')->where('users.id', Auth::user()->id)->get();
        }
        /* if (Auth::user()->level_id == 4) {
             $data['added_by'] = User::join('person', 'person.id', '=', 'users.person_id')->select('users.id', 'person.first_name', 'person.last_name')->get();
         }*/
        $data['search_attributes'] = array();

        if ($this->request->isMethod('post')) {
            $data['transactions'] = $this->searchResults($this->request->all());
            $data['search_attributes'] = $this->request->all();

        }
        return view('Transaction::search', $data);
    }

    public function searchResults($request)
    {

        $transaction_query = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id');

        if (isset($request['transaction_status'])) {

            $transaction_query = $transaction_query->whereIn('transactions.transaction_status_id', $request['transaction_status'])
                ->select('transactions.*', 'transactions.id as transactionId', 'transaction_details.*');


        } else {
            $transaction_query = $transaction_query->select('transactions.*', 'transactions.id as transactionId', 'transaction_details.*');

        }
        if (isset($request['sender_name'])) {
            $transaction_query = $transaction_query->leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
                ->whereIn('senders.id', $request['sender_name']);
        }

        if (isset($request['beneficiary_name'])) {
            $transaction_query = $transaction_query->leftJoin('beneficiaries', 'beneficiaries.beneficiary_id', '=', 'transactions.beneficiary_id')
                ->whereIn('beneficiaries.beneficiary_id', $request['beneficiary_name']);
        }


        if (isset($request['added_by'])) {
            if ($request['added_by'] == 0) {

            } else {

                $transaction_query = $transaction_query->where('transactions.added_by', $request['added_by']);
            }

        }

        if (isset($request['sending_amount'])) {
            $transaction_query = $transaction_query->where('transaction_details.sending_amount', $request['sending_amount']);
        }
        if (isset($request['distributor'])) {
            if ($request['distributor'] == 0) {

            } else {

                $transaction_query = $transaction_query->leftJoin('assign_distributors', 'assign_distributors.transactions_id', '=', 'transactions.id')->leftJoin('distributor_offices', 'distributor_offices.id', '=', 'assign_distributors.distributor_office_id')->leftJoin('companies', 'companies.id', '=', 'distributor_offices.companies_id')->where('companies.id', $request['distributor']);
            }

        }

        if (isset($request['to']) && isset($request['from'])) {

            /* $dates = explode(' - ', $request['transaction_date_range']);*/
            $date[0] = Carbon::parse($request['from'])->formatLocalized('%Y-%m-%d');
            $date[1] = Carbon::parse($request['to'])->formatLocalized('%Y-%m-%d');
            $transaction_query = $transaction_query->whereBetween('transaction_details.transaction_date', array((string)$date[0], (string)$date[1]));
        }
        if (isset($request['from']) && isset($request['to']) == false) {
            $date[0] = Carbon::parse($request['from'])->formatLocalized('%Y-%m-%d');
            $transaction_query = $transaction_query->where('transaction_details.transaction_date', (string)$date[0]);
        }
        if (isset($request['to']) && isset($request['from']) == false) {
            $date[0] = Carbon::parse($request['to'])->formatLocalized('%Y-%m-%d');
            $transaction_query = $transaction_query->where('transaction_details.transaction_date', (string)$date[0]);
        }
        if (Auth::user()->level_id == 3) {
            $transaction_query = $transaction_query->where('transactions.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            $transaction_query = $transaction_query->where('transactions.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 4) {
            $distibutor_role = DistributorUser::where('user_id', Auth::user()->id)->first();
            if ($distibutor_role->role_id == 1) {
                $transaction_query = $transaction_query->leftJoin('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
                    ->leftJoin('distributor_users', 'distributor_users.distributor_office_id', 'distributor_transactions.distributor_office_id')
                    ->where('transactions.transaction_status_id', '!=', 1)
                    ->where('transactions.transaction_status_id', '!=', 2)
                    ->where('distributor_users.user_id', Auth::user()->id);
            } else {
                $transaction_query = $transaction_query->leftJoin('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
                    ->where('distributor_transactions.assigned_to', Auth::user()->id);
            }

        }
        return $transaction_query->orderBy('transactions.id', 'desc')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadReceipt($document_id)
    {
        $transaction = TransactionDocument::where('id', $document_id)->first();
        $file = base_path() . '/public/TransactionIdentification/' . $transaction->file_name;

        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->download($file, $transaction->file_name, $headers);

        /* $file = '/public/TransactionIdentification/'.$transaction->file*/
        /*return response()->download($file, $transaction->file_name, $headers);
  */
        /* return view('Transaction::viewreceipt', compact('transaction', 'transaction_id'));*/
    }

    public function viewReceipt($document_id)
    {
        $transaction = TransactionDocument::where('id', $document_id)->first();
        return view('Transaction::viewreceipt', compact('transaction'));
    }

    public function addStaffNote($transaction_id)
    {
        $transaction = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->where('transactions.id', $transaction_id)->first();
        return view('Transaction::modals/staffNotesModal', compact('transaction', 'transaction_id'));
    }

    public function addCommentNote($transaction_id)
    {
        $transaction = Transaction::where('transactions.id', $transaction_id)->first();


        return view('Transaction::modals/addCommentModal', compact('transaction', 'transaction_id'));
    }

    public function sendCommentEmail($comment, $user_id, $transaction_id, $notes)
    {
        $user = User::where('id', $user_id)->first();
        $note = Note::where('id', $notes->id)->first();
        $commentingPerson = User::where('id', $note->added_by)->first();
        $person = Person::where('id', $user->person_id)->first();
        $commentingPersonDetail = Person::where('id', $commentingPerson->person_id)->first();
        $commenting_person_name = $commentingPersonDetail->first_name . ' ' . $commentingPersonDetail->last_name;
        $person_name = $person['first_name'] . ' ' . $person['last_name'];

        $body = $commenting_person_name . ' commented  <b>' . $comment . '</b> on Transaction ' . format_id($transaction_id, 'T');
        if ($user->level_id == 1 || $user->level_id == 2) {
            $params = [
                'to' => strtolower($user->email),
                'toName' => $person_name,
                'body' => $body,
                'subject' => 'Message on Transaction ' . format_id($transaction_id, 'T'),
                'fromEmail' => env('MAIL_FROM_ADDRESS'),
                'fromName' => env('FROM_NAME', $this->getApplicationService->getApplication()->name),
            ];

        } else {
            $params = [
                'to' => strtolower($person->email),
                'toName' => $person_name,
                'body' => $body,
                'subject' => 'Message on Transaction ' . format_id($transaction_id, 'T'),
                'fromEmail' => env('MAIL_FROM_ADDRESS'),
                'fromName' => env('FROM_NAME', $this->getApplicationService->getApplication()->name),
            ];

        }

        Mail::send('Transaction::Email/commentEmail', $params, function ($message) use ($params) {
            $message->to($params['to'], $params['toName'])
                ->from($params['fromEmail'], $params['fromName'])
                ->subject($params['subject']);
        });

    }

    public function storeCommentNote(Request $request, $transaction_id)
    {
        if (request()->ajax()) {

            $notes = Note::create([
                'comment' => $request->comment,
                'added_by' => Auth::user()->id,
                'transactions_id' => $transaction_id,
                'is_read' => 0
            ]);
            $admins = User::where('level_id', 1)->orWhere('level_id', 2)->get();
            if (isset($request->agent_user_id)) {
                $comment_note = new NoteAssign();
                $comment_note->user_id = $request->agent_user_id;
                $comment_note->notes_id = $notes->id;
                $comment_note->save();
                $this->sendCommentEmail($request->comment, $request->agent_user_id, $transaction_id, $notes);
            }
            if (isset($request->client_user_id)) {
                $comment_note = new NoteAssign();
                $comment_note->user_id = $request->client_user_id;
                $comment_note->notes_id = $notes->id;
                $comment_note->save();
                $this->sendCommentEmail($request->comment, $request->client_user_id, $transaction_id, $notes);
            }
            if (isset($request->admin)) {
                foreach ($admins as $admins) {
                    $comment_note = new NoteAssign();
                    $comment_note->user_id = $admins->id;
                    $comment_note->notes_id = $notes->id;
                    $comment_note->save();
                    $this->sendCommentEmail($request->comment, $admins->id, $transaction_id, $notes);
                }
            }
        }
        return $this->success();

        /* Flash::success('Comment has been updated successfully.');*/

        /*  return redirect()->back();*/


    }


    public function storeStaffNote(Request $request, $transaction_id)
    {
        $transaction = Transaction::find($transaction_id);
        $transactionDetails = TransactionDetails::where('transaction_details_id', $transaction->transaction_details_id)->first();
        $transactionDetails->staff_notes = $request->staff_notes;
        $transactionDetails->save();
        $notification = array(
            'message' => 'Payment Note has been updated successfully!',
            'alert-type' => 'success',
        );
        // Flash::success('Payment Note has been updated successfully.');

        return redirect()->back()->with($notification);
    }

    public function storeAdminStaffNote(Request $request, $transaction_id)
    {

        $transaction = Transaction::find($transaction_id);
        $transactionDetails = TransactionDetails::where('transaction_details_id', $transaction->transaction_details_id)->first();
        $transactionDetails->admin_staff_notes = $request->admin_staff_notes;
        $transactionDetails->save();
        if ($transactionDetails) {
            echo 1;
        } else {
            echo 0;
        }

    }

    public function create()
    {
        return view('Sender::add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $transaction = Transaction::where('id', $id)->first();
        $data['transaction_histories'] = TransactionHistory::where('transaction_id', $id)->get();
        $data['referrals'] = ReferralPoints::where('transaction_id', $id)->where('points', '<', 0)->first();
        $data['coupon_usage'] = CouponUsage::with('coupon')->where('transaction_id', $id)->first();
        $distributorCheckAdmin = DistributorUser::join('distributor_transactions', 'distributor_transactions.distributor_office_id', '=', 'distributor_users.distributor_office_id')->where('distributor_transactions.transaction_id', $id)->where('distributor_users.user_id', Auth::user()->id)->where('distributor_users.role_id', 1)->first();
        $distributorCheckStaff = DistributorUser::join('distributor_transactions', 'distributor_transactions.distributor_office_id', '=', 'distributor_users.distributor_office_id')->where('distributor_transactions.transaction_id', $id)->where('distributor_users.user_id', Auth::user()->id)->where('distributor_transactions.assigned_to', Auth::user()->id)->where('distributor_users.role_id', 2)->first();
        // check if free service charge from referrals is claimed
        $data['freeServiceChargeClaimed'] = FreeServiceCharge::where('redeemed_transaction_id', $id)->count() > 0;

        if (!(
            in_array(Auth::user()->level_id, [1, 2, 3, 6, 7, 8]) ||
            (Auth::user()->level_id == 5 && $transaction->added_by == Auth::user()->id) ||
            (Auth::user()->level_id == 4 && (Auth::user()->id == $distributorCheckAdmin['user_id'] || Auth::user()->id == $distributorCheckStaff['user_id']))
        )) {
            abort(403, 'Unauthorized action.');
        }


        if (request()->ajax()) {
            return $this->success();
        }


        $data['transaction'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->where('transactions.id', $id)->first();
        $data['sender'] = $this->sender->getSenderDetailById($transaction->sender_id);
        $data['tran_beneficiary'] = TransactionBeneficiary::where('transaction_id', $id)->first();
        $data['beneficiary'] = $this->beneficiary->getBeneficiaryBetailsByTransaction($data['transaction']);
        $data['agent_transaction'] = DB::table('agent_transactions')->where('transactions_id', $id)->first();

        if ($data['agent_transaction']) {
            $data['agent'] = $this->agent->getAgentById($data['agent_transaction']->agents_id);
        }
        $data['documents'] = TransactionDocument::where('transactions_id', $id)->orderBy('id', 'desc')->get();


        $data['status'] = SenderStatus::get();
        $data['sender_id'] = $transaction->sender_id;
        $data['beneficiary_id'] = $transaction->beneficiary_id;
        if (Auth::user()->level_id != 4) {
            $data['transaction_status'] = DB::table('status')
                ->get();
        } else {
            $data['transaction_status'] = DB::table('status')
                ->where('id', 3)
                ->orWhere('id', 4)
                ->orWhere('id', 5)
                ->orWhere('id', 6)
                ->orWhere('id', 7)
                ->get();
        }


        // get previous txn id
        if (Auth::user()->level_id == 3) {
            $agent_ID = getAgentId();
            $data['previous'] = Transaction::leftJoin('agent_transactions', 'agent_transactions.transactions_id', '=', 'transactions.id')
                ->where('agent_transactions.agents_id', $agent_ID)->where('transactions.id', '<', $id)->max('transactions.id');
            $data['next'] = Transaction::leftJoin('agent_transactions', 'agent_transactions.transactions_id', '=', 'transactions.id')
                ->where('agent_transactions.agents_id', $agent_ID)->where('transactions.id', '>', $id)->min('transactions.id');

        } else {
            $data['previous'] = Transaction::where('id', '<', $id)->max('id');
            $data['next'] = Transaction::where('id', '>', $id)->min('id');

        }

        $sender_identification = Identification::join('identification_documents', 'identification_documents.id', '=', 'identifications.identification_documents_id')
            ->join('documents', 'documents.id', '=', 'identification_documents.document_id')
            ->where('identifications.current', 1)
            ->where('identifications.Identification_status_id', 2)
            ->where('identifications.senders_id', $transaction->sender_id)
            ->select('identifications.senders_id as senderId', 'identifications.id_number', 'identifications.issued_by', 'identifications.id_number', 'identifications.expiry_date', 'documents.name', 'documents.name1')
            ->orderBy('documents.id', 'desc')->first();

        if (!empty($sender_identification)) {
            $data['first_doc'] = asset('identification/' . $sender_identification->name);
            $data['second_doc'] = isset($sender_identification->name1) ? asset('identification/' . $sender_identification->name1) : '';
        } else {
            $data['first_doc'] = '';
            $data['second_doc'] = '';
        }
        // get next txn id

        return view("Transaction::" . $this->extra_folder . "show", $data);

    }

    public function changesenderStatus($sender_id, Request $request)
    {
        $sender = new Sender();
        $data = $sender->find($sender_id);

        $data->sender_status_id = $request->status;

        $data->save();
        $notification = array(
            'message' => 'Sender status has been updated successfully!',
            'alert-type' => 'success',
        );
        //Flash::success('Sender status has been updated successfully.');

        return redirect()->back()->with($notification);
    }

    public function changetransactionStatus($transaction_id, Request $request)
    {

        $tran = Transaction::find($transaction_id);
        $tran->transaction_status_id = $request->status_id;
        $tran->save();

        if ($request->status_id == 10) {

            $referral = Referral::where('user_id', $tran->added_by)->where('status', 0)->first();
            $freeServiceChargeAlreadyExists = FreeServiceCharge::where('transaction_id', $tran->id)->first();

            if (isset($referral) && !$freeServiceChargeAlreadyExists ) {
                $referral->status = 1;
                $referral->save();
                // new referral system
                if ($referral->referral_system == ReferralSystemConstant::NEW) {
                    FreeServiceCharge::create([
                        'referrer_user_id' => $referral->referrer_id,
                        'referred_user_id' => $referral->user_id,
                        'transaction_id' => $tran->id,
                        'used' => FreeServiceChargeConstant::NOT_USED
                    ]);
                } else {
                    $referrerId = $referral->referrer_id;
                    Referral::where(
                        [
                            'referrer_id' => $referrerId,
                            'status' => 0,
                            'referral_system' => ReferralSystemConstant::OLD
                        ]
                    )->update(['status' => 1]);
                    FreeServiceCharge::create([
                        'referrer_user_id' => $referrerId,
                        'referred_user_id' => $referral->user_id,
                        'transaction_id' => $tran->id,
                        'used' => FreeServiceChargeConstant::NOT_USED
                    ]);
                    // old referral system
//                    $application = getAppDetailsForWeb();
//
//                    ReferralPoints::create([
//                        'date' => Carbon::now(),
//                        'points' => $application->discount_percent,
//                        'description' => 'Client Referral Points',
//                        'claimed_by' => $referral->referrer_id,
//                        'transaction_id' => $tran->id
//                    ]);
                }

            }
            // $this->transaction->sendDeliveredEmail($tran);

        }
        if($request->status_id == TransactionStatusConstant::TRANSACTION_IN_REVIEW){
            $tran->sendTransactionInReviewMail($tran);
        }
        if($request->status_id != 14 && $request->status_id != 10 && $request->status_id != 12){
            TransactionHistory::create(['transaction_id'=>$request->transaction_id,'status'=>'Processed in Nepal']);
        } elseif($request->status_id != 14 && $request->status_id == 10 && $request->status_id != 12){
            TransactionHistory::create(['transaction_id'=>$request->transaction_id,'status'=>'Paid in Nepal']);
        } elseif($request->status_id == 12){
            TransactionHistory::create(['transaction_id'=>$request->transaction_id,'status'=>'Cancelled']);
        }

        if ($request->status_id == 10 || $request->status_id == 7 || $request->status_id == 5) {
            $not['title'] = 'Transaction status changed';
            $not['notification_message'] = 'Transaction (' . format_id($transaction_id, 'T') . ') status changed to ' . getStatusName($request->status_id) . '.';

            $this->transaction->createNotification($transaction_id, $not);
        }
        $notification = array(
            'message' => 'Transaction status has been updated successfully!',
            'alert-type' => 'success',
        );
        // Flash::success('Transaction status has been updated successfully.');
        return redirect()->back()->with($notification);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function editsender($id)
    {
        $sender = $this->sender->getSenderDetailById($id);
        $suburb = AusStates::select('name')->where('type', 'aus_suburb')->pluck('name', 'name');
        return view("Transaction::modals/editsenderModal", compact('sender', 'suburb'));
    }

    public function editbeneficiary($id)
    {
        $beneficiary = $this->beneficiary->getBeneficiaryDetailById($id);
        $data['district'] = AusStates::select('name')->where('type', 'district')->orderBy('name')->pluck('name', 'name');
        $data['np_state'] = AusStates::select('name')->where('type', 'np_state')->pluck('name', 'name');
        return view("Transaction::modals/editbeneficiaryModal", compact('beneficiary', 'data'));
    }

    public function editAgentModal($transaction_id)
    {
        $agentTransaction = AgentTransaction::leftJoin('transactions', 'transactions.id', '=', 'agent_transactions.transactions_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->where('agent_transactions.transactions_id', $transaction_id)
            ->select('transactions.*', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.service_charge as serviceCharge', 'agent_transactions.id as agentTransactionId', 'agent_transactions.agents_id as agentId', 'agent_transactions.exchange_rate as agentRate', 'agent_transactions.total_commission as agentCommission')->first();

        return view("Transaction::modals/editAgentModal", compact('agentTransaction'));
    }

    public function saveEditAgentModal(Request $request, $transaction_id, $agent_id)
    {

        $transaction = Transaction::find($transaction_id);
        $transaction_details = TransactionDetails::where('transaction_details_id', $transaction->transaction_details_id)->first();
        $transaction_details->service_charge = $request->service_charge;
        $transaction_details->total_to_pay = $request->service_charge + $transaction_details->sending_amount;
        $transaction_details->save();
        $agent_transaction = AgentTransaction::where('transactions_id', $transaction->id)->first();
        $agent_transaction->exchange_rate = $request->rate_agent;
        $agent_transaction->service_charge = $request->service_charge;
        $agent_transaction->total_commission = $request->commission_agent_total;
        $agent_transaction->save();
        $notification = array(
            'message' => 'Agent Details has been updated successfully!',
            'alert-type' => 'success',
        );
        //Flash::success('Agent Details has been updated successfully.');
        return redirect()->back()->with($notification);

    }

    public function addsender()
    {
        $suburb = AusStates::select('name')->where('type', 'aus_suburb')->pluck('name', 'name');
        return view("Transaction::modals/addSenderModal", compact('suburb'));
    }

    public function addbeneficiary()
    {
        $sender_id = $_GET['id'];
        $data['district'] = AusStates::select('name')->where('type', 'district')->orderBy('name')->pluck('name', 'name');
        $data['np_state'] = AusStates::select('name')->where('type', StateTypeConstant::NEW_NP_STATES)->pluck('name', 'name');
        return view("Transaction::modals/" . $this->extra_folder . "addBeneficiaryModal", compact('sender_id', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function excelExport(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->selected_id;
            $excel_data1 = $ids;
            if (!empty($excel_data1)) {
                $previous = Session::get('excel_data1');
                if ($previous) {
                    Session::forget('excel_data1');
                }
                Session::put('excel_data1', $excel_data1);
                return 'success';
            } else {
                return 'error';
            }
            // return Excel::download(new TransactionExport($data), 'transactions.xlsx');
        }
    }

    public function excelExport2(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->selected_id;
            $excel_data2 = $ids;
            if (!empty($excel_data2)) {
                $previous = Session::get('excel_data2');
                if ($previous) {
                    Session::forget('excel_data2');
                }
                Session::put('excel_data2', $excel_data2);
                return 'success';

            } else {
                return 'error';
            }
        }
        //  return Excel::download(new TransactionExport2($data), 'transactions.xlsx');

    }

    public function excelExport3(Request $request)
    {
        //  $ids = $_GET;
        if ($request->ajax()) {
            $ids = $request->selected_id;
            $excel_data3 = $ids;
            if (!empty($excel_data3)) {
                $previous = Session::get('excel_data3');
                if ($previous) {
                    Session::forget('excel_data3');
                }
                Session::put('excel_data3', $excel_data3);
                return 'success';

            } else {
                return 'error';
            }
        }
        // return Excel::download(new TransactionExport3($data), 'transactions.xlsx');
    }

    public function excelExport4(Request $request)
    {
        //$ids = $_GET;
        if ($request->ajax()) {
            $ids = $request->selected_id;
            $excel_data4 = $ids;
            if (!empty($excel_data4)) {
                $previous = Session::get('excel_data4');
                if ($previous) {
                    Session::forget('excel_data4');
                }
                Session::put('excel_data4', $excel_data4);
                return 'success';

            } else {
                return 'error';
            }
        }
        // return Excel::download(new TransactionExport4($data), 'transactions.xlsx');
    }

    public function excelExport5(Request $request)
    {
        //$ids = $_GET;
        if ($request->ajax()) {
            $ids = $request->selected_id;
            $excel_data5 = $ids;
            if (!empty($excel_data5)) {
                $previous = Session::get('excel_data5');
                if ($previous) {
                    Session::forget('excel_data5');
                }
                Session::put('excel_data5', $excel_data5);
                return 'success';

            } else {
                return 'error';
            }
        }
        // return Excel::download(new TransactionExport4($data), 'transactions.xlsx');
    }

    public function excelExport6(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->selected_id;
            $excel_data6 = $ids;
            if (!empty($excel_data6)) {
                $previous = Session::get('excel_data6');
                if ($previous) {
                    Session::forget('excel_data6');
                }
                Session::put('excel_data6', $excel_data6);
                return 'success';

            } else {
                return 'error';
            }
        }
    }

    public function excelExport7(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->selected_id;
            $excel_data7 = $ids;
            if (!empty($excel_data7)) {
                $previous = Session::get('excel_data7');
                if ($previous) {
                    Session::forget('excel_data7');
                }
                Session::put('excel_data7', $excel_data7);
                return 'success';

            } else {
                return 'error';
            }
        }
    }

    public function excelExport8(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->selected_id;
            $excel_data8 = $ids;
            if (!empty($excel_data8)) {
                $previous = Session::get('excel_data8');
                if ($previous) {
                    Session::forget('excel_data8');
                }
                Session::put('excel_data8', $excel_data8);
                return 'success';

            } else {
                return 'error';
            }
        }
    }

    public function excelAustracExport(Request $request)
    {
        if ($request->ajax()) {
            $ids = $request->selected_id;
            $austrac_data = $ids;
            //$austrac_data  =  Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')->whereIn('id', $ids)->get();
            if (!empty($austrac_data)) {
                $previous = Session::get('austrac_data');
                if ($previous) {
                    Session::forget('austrac_data');
                }
                Session::put('austrac_data', $austrac_data);
                return 'success';
            } else {
                return 'error';
            }
        }
        // return Excel::download(new AustracExport($data), 'austrac-transactions.xlsx');
    }

    public function downloadExcel1()
    {
        $excel_data1 = Session::get('excel_data1');
        $data = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')->whereIn('id', $excel_data1)->get();
        return Excel::download(new TransactionExport($data), 'transactions.xlsx');
    }

    public function downloadExcel2()
    {
        $excel_data2 = Session::get('excel_data2');
        $data = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')->whereIn('id', $excel_data2)->get();
        return Excel::download(new TransactionExport2($data), 'transactions.xlsx');
    }

    public function downloadExcel3()
    {
        $excel_data3 = Session::get('excel_data3');
        $data = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')->whereIn('id', $excel_data3)->get();
        return Excel::download(new TransactionExport3($data), 'transactions.xlsx');
    }

    public function downloadExcel4()
    {
        $excel_data4 = Session::get('excel_data4');
        $data = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')->whereIn('id', $excel_data4)->get();
        return Excel::download(new TransactionExport4($data), 'transactions.xlsx');
    }

    public function downloadExcel5()
    {
        $excel_data5 = Session::get('excel_data5');
        $data = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->whereIn('id', $excel_data5)->get();
        return Excel::download(new TransactionExport5($data), 'transactions.xlsx');
    }

    public function downloadExcel6()
    {
        $excel_data6 = Session::get('excel_data6');
        $data = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->whereIn('id', $excel_data6)->get();
        return Excel::download(new TransactionExport6($data), 'transactions.xlsx');
    }

    public function downloadExcel7()
    {
        $excel_data7 = Session::get('excel_data7');
        $data = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->whereIn('id', $excel_data7)->get();
        return Excel::download(new TransactionExport7($data), 'transactions.xlsx');
    }

    public function downloadExcel8()
    {
        $excel_data8 = Session::get('excel_data8');
        $data = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->whereIn('id', $excel_data8)->get();
        return Excel::download(new TransactionExport8($data), 'transactions.xlsx');
    }

    public function downloadAustrac()
    {
        $austrac_data = Session::get('austrac_data');
        $data = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->leftJoin('status', 'status.id', '=', 'transactions.transaction_status_id')
            ->leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
            ->leftJoin('person as a', 'a.id', '=', 'senders.person_id')
            ->leftJoin('beneficiaries', 'beneficiaries.beneficiary_id', '=', 'transactions.beneficiary_id')
            ->leftJoin('person as b', 'b.id', '=', 'beneficiaries.person_id')
            ->leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.bank_details_id', '=', 'transactions.beneficiaries_bank_details_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->leftJoin('person_phones as b_ph', 'b_ph.person_id', '=', 'b.id')
            ->leftJoin('phones as b_p', 'b_p.id', '=', 'b_ph.phones_id')
            ->leftJoin('person_phones as s_ph', 's_ph.person_id', '=', 'a.id')
            ->leftJoin('phones as s_p', 's_p.id', '=', 's_ph.phones_id')
            ->leftJoin('identifications', 'identifications.senders_id', '=', 'senders.id')
            ->leftJoin('identification_types', 'identification_types.id', '=', 'identifications.identification_types_id')
            ->leftJoin('person_address as pa', 'pa.person_id', '=', 'a.id')
            ->leftJoin('addresses as ad', 'pa.address_id', '=', 'ad.id')
            ->leftJoin('country_list as cl1', 'cl1.id', '=', 'ad.country_list_id')
            ->leftJoin('person_address as b_pa', 'b_pa.person_id', '=', 'b.id')
            ->leftJoin('addresses as b_ad', 'b_pa.address_id', '=', 'b_ad.id')
            ->leftJoin('country_list as cl2', 'cl2.id', '=', 'b_ad.country_list_id')
            ->whereIn('transactions.id', $austrac_data)
            //->where('transaction_details.payment_type', 'Bank Transfer')
            ->orderBy('transactions.id', 'desc')
            ->select('s_p.number as sender_phone', 'status.name as transaction_status', 'transactions.id as transaction_id', 'transaction_details.transaction_date as transaction_date', 'transaction_details.sending_amount as sending_amount', 'transaction_details.purpose_of_transfer as purpose_of_transfer',
                'transaction_details.service_charge as service_charge',
                'transactions.sender_id as sender_id', 'a.dob as sender_dob', 'a.email as sender_email',
                'ad.street as sender_street', 'ad.suburb as sender_suburb', 'ad.postcode as sender_postcode', 'cl1.name as sender_country', 'ad.state as sender_state',
                'b_ad.street as b_street', 'b_ad.suburb as b_suburb', 'b_ad.postcode as b_postcode', 'cl2.name as b_country', 'b_ad.state as b_state',
                /* DB::raw('DATE_FORMAT(transaction_details.transaction_date, "%d/-%M-%Y") as transactionDate'),*/
                DB::raw('CONCAT_WS(" ", a.first_name, NULLIF(a.middle_name,""), a.last_name) AS sender_name'),
                DB::raw('CONCAT_WS(" ", b.first_name, NULLIF(b.middle_name,""), b.last_name) AS beneficiary_name'),
                'transaction_details.total_to_pay as total_amount', 'transaction_details.payment_amount as payment_amount',
                'transaction_details.exchange_rate as exchange_rate', 'transactions.transaction_status_id as transaction_status_id',
                'b_p.number as beneficiary_phone',
                'bank_details.account_name as account_name',
                'bank_details.account_no as account_no', 'bank_details.bsb as bsb',
                'bank_details.bank_name as bank_name',
                'transactions.beneficiary_id as beneficiary_id', 'identifications.expiry_date as expiry_date', 'identifications.issued_by as issued_by', 'identifications.id_number as id_number', 'identification_types.name as id_type')
            ->get();


        /* $data = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')->whereIn('id', $austrac_data)->get();
        */
        return Excel::download(new AustracExport($data), 'transactions.xlsx');
    }

    public function fetchComments(Request $request)
    {
        if (request()->ajax()) {
            $transaction_id = $request->get('Id');

            $comments = Note::where('transactions_id', $transaction_id)->orderBy('id', 'desc')->get();
            $view = view("Transaction::" . $this->extra_folder . "comment", compact('comments'))->render();

            return response()->json(['comment' => $view]);

//            $comment = '';
//
//            foreach ($comments as $comments) {
//                $comment .= '
//
//        <li class="timeline-inverted">
//          <div class="timeline-badge warning"><i class="fa fa-envelope"></i></div>
//          <div class="timeline-panel">
//            <div class="timeline-heading">
//              <h4 class="timeline-title">' . get_user_name($comments->added_by) . '</h4>
//              <small class="text-muted"><i class="fa fa-clock-o"></i> ' . get_notification_format($comments['created_at']) . ' | ' . format_date($comments->created_at) . ' </small>
//            </div>
//            <div class="timeline-body">
//              <p>' . $comments->comment . '</p>
//            </div>
//          </div>
//        </li>
//
//   ';
            /*  $comment .= ' <div class="col-md-12">
      <ul class="timeline" style="margin-bottom:-15px!important">
          <li class="time-label">
                <span class="bg-red">
                 ' . format_date($comments->created_at) . '
                </span>
          </li>
          <li>
              <i class="fa fa-comments bg-yellow"></i>
              <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> ' . get_notification_format($comments['created_at']) . '</span>
                  <h3 class="timeline-header"><a href="#">' . get_user_name($comments->added_by) . '</a> commented </h3>

                  <div class="timeline-body">
                      ' . $comments->comment . '
                  </div>
              </div>
          </li>
          <li class="time-label">
          </li>
          <li>
          </li>
</ul>
  </div>';*/

            /* }

             return json_encode(array('comment' => $comment));*/

        }


    }

    public function getLineChartData(Request $request)
    {

        if (request()->ajax()) {
            $query = $request->get('query');
            $year = Carbon::now()->format('Y');

            if ($query == 0) { //yearly
                for ($i = 1; $i <= 12; $i++) {
                    $sendingAmountPerMonth[Carbon::createFromFormat('!m', $i)->format('M')] = TransactionDetails::whereRaw('MONTH(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->sum('transaction_details.total_to_pay');
                    $paymentAmountPerMonth[Carbon::createFromFormat('!m', $i)->format('M')] = TransactionDetails::whereRaw('MONTH(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->sum('transaction_details.payment_amount');
                    $numberOfTransactions[Carbon::createFromFormat('!m', $i)->format('M')] = TransactionDetails::whereRaw('MONTH(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->count();
                }

            } elseif ($query == 1) { // monthly
                $start = Carbon::now()->startOfMonth()->format('d');
                $end = Carbon::now()->endOfMonth()->format('d');
                $month = Carbon::now()->format('m');
                $year = Carbon::now()->format('Y');

                for ($i = 1; $i <= $end; $i++) {
                    $sendingAmountPerMonth[Carbon::now()->format('M') . ' ' . $i] = TransactionDetails::whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('DAY(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->sum('transaction_details.total_to_pay');
                    $paymentAmountPerMonth[Carbon::now()->format('M') . ' ' . $i] = TransactionDetails::whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('DAY(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->sum('transaction_details.payment_amount');
                    $numberOfTransactions[Carbon::now()->format('M') . ' ' . $i] = TransactionDetails::whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('DAY(transaction_details.transaction_date) = ?', [$i])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->count();

                }

            } else { // 2 weekly
                $start = Carbon::now()->startOfweek();
                $end = Carbon::now()->endOfweek();
                $dates = $this->generateDateRange($start, $end);
                foreach ($dates as $date) {
                    $sendingAmountPerMonth[Carbon::parse($date)->format('M') . ' ' . Carbon::parse($date)->format('d') . ' ' . '(' . Carbon::parse($date)->format('D') . ')'] = TransactionDetails::whereRaw('MONTH(transaction_details.transaction_date)= ?', [Carbon::parse($date)->format('m')])->whereRaw('DAY(transaction_details.transaction_date) = ?', [Carbon::parse($date)->format('d')])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [Carbon::parse($date)->format('Y')])->sum('transaction_details.total_to_pay');
                    $paymentAmountPerMonth[Carbon::parse($date)->format('M') . ' ' . Carbon::parse($date)->format('d') . ' ' . '(' . Carbon::parse($date)->format('D') . ')'] = TransactionDetails::whereRaw('MONTH(transaction_details.transaction_date)= ?', [Carbon::parse($date)->format('m')])->whereRaw('DAY(transaction_details.transaction_date) = ?', [Carbon::parse($date)->format('d')])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [Carbon::parse($date)->format('Y')])->sum('transaction_details.payment_amount');
                    $numberOfTransactions[Carbon::parse($date)->format('M') . ' ' . Carbon::parse($date)->format('d') . ' ' . '(' . Carbon::parse($date)->format('D') . ')'] = TransactionDetails::whereRaw('MONTH(transaction_details.transaction_date)= ?', [Carbon::parse($date)->format('m')])->whereRaw('DAY(transaction_details.transaction_date) = ?', [Carbon::parse($date)->format('d')])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [Carbon::parse($date)->format('Y')])->count();

                }

            }
            $data = [
                'sendingAmountPerMonth' => $sendingAmountPerMonth,
                'paymentAmountPerMonth' => $paymentAmountPerMonth,
                'noOfTransactions' => $numberOfTransactions
            ];

            echo json_encode($data);
        }

    }

    function transactionQueryForUserChart()
    {
        $transaction = Transaction::join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->join('users', 'users.id', '=', 'transactions.added_by');
        return $transaction;
    }

    public function getTransactionByUserData(Request $request)
    {
        if (request()->ajax()) {
            $query = $request->get('query');

            if ($query == 1) {
                $staffs = $this->transactionQueryForUserChart()->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->whereIn('users.level_id', [1, 2])->sum('transaction_details.sending_amount');
                $staffs_count = $this->transactionQueryForUserChart()->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->whereIn('users.level_id', [1, 2])->count();
                $agents = $this->transactionQueryForUserChart()->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->where('users.level_id', 3)->sum('transaction_details.sending_amount');
                $agents_count = $this->transactionQueryForUserChart()->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->where('users.level_id', 3)->count();
                $client = $this->transactionQueryForUserChart()->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->where('users.level_id', 5)->sum('transaction_details.sending_amount');
                $client_count = $this->transactionQueryForUserChart()->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->where('users.level_id', 5)->count();
            } else if ($query == 2) {
                $a = format_date(Carbon::now()->startOfWeek());
                $startOfWeek = Carbon::parse($a)->format('Y-m-d');
                $b = format_date(Carbon::now()->endOfWeek());
                $endOfWeek = Carbon::parse($b)->format('Y-m-d');
                $staffs = $this->transactionQueryForUserChart()->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->whereIn('users.level_id', [1, 2])->sum('transaction_details.sending_amount');
                $staffs_count = $this->transactionQueryForUserChart()->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->whereIn('users.level_id', [1, 2])->count();
                $agents = $this->transactionQueryForUserChart()->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->where('users.level_id', 3)->sum('transaction_details.sending_amount');
                $agents_count = $this->transactionQueryForUserChart()->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->where('users.level_id', 3)->count();
                $client = $this->transactionQueryForUserChart()->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->where('users.level_id', 5)->sum('transaction_details.sending_amount');
                $client_count = $this->transactionQueryForUserChart()->whereBetween('transaction_details.transaction_date', [$startOfWeek, $endOfWeek])->where('users.level_id', 5)->count();
            } else if ($query == 3) {
                $month = Carbon::now()->format('m');
                $year = Carbon::now()->format('Y');
                $staffs = $this->transactionQueryForUserChart()->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->whereIn('users.level_id', [1, 2])->sum('transaction_details.sending_amount');
                $staffs_count = $this->transactionQueryForUserChart()->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->whereIn('users.level_id', [1, 2])->count();
                $agents = $this->transactionQueryForUserChart()->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->where('users.level_id', 3)->sum('transaction_details.sending_amount');
                $agents_count = $this->transactionQueryForUserChart()->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->where('users.level_id', 3)->count();
                $client = $this->transactionQueryForUserChart()->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->where('users.level_id', 5)->sum('transaction_details.sending_amount');
                $client_count = $this->transactionQueryForUserChart()->whereRaw('MONTH(transaction_details.transaction_date) = ?', [$month])->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->where('users.level_id', 5)->count();
            } else if ($query == 4) {
                $year = Carbon::now()->format('Y');
                $staffs = $this->transactionQueryForUserChart()->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->whereIn('users.level_id', [1, 2])->sum('transaction_details.sending_amount');
                $staffs_count = $this->transactionQueryForUserChart()->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->whereIn('users.level_id', [1, 2])->count();
                $agents = $this->transactionQueryForUserChart()->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->where('users.level_id', 3)->sum('transaction_details.sending_amount');
                $agents_count = $this->transactionQueryForUserChart()->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->where('users.level_id', 3)->count();
                $client = $this->transactionQueryForUserChart()->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->where('users.level_id', 5)->sum('transaction_details.sending_amount');
                $client_count = $this->transactionQueryForUserChart()->whereRaw('YEAR(transaction_details.transaction_date) = ?', [$year])->where('users.level_id', 5)->count();
            } else if ($query == 5) {
                $staffs = $this->transactionQueryForUserChart()->whereIn('users.level_id', [1, 2])->sum('transaction_details.sending_amount');
                $staffs_count = $this->transactionQueryForUserChart()->whereIn('users.level_id', [1, 2])->count();
                $agents = $this->transactionQueryForUserChart()->where('users.level_id', 3)->sum('transaction_details.sending_amount');
                $agents_count = $this->transactionQueryForUserChart()->where('users.level_id', 3)->count();
                $client = $this->transactionQueryForUserChart()->where('users.level_id', 5)->sum('transaction_details.sending_amount');
                $client_count = $this->transactionQueryForUserChart()->where('users.level_id', 5)->count();
            }
            $data = [
                'label' => ["Client(" . $client_count . ')', "Staffs(" . $staffs_count . ')', "Agents(" . $agents_count . ')'],
                'value' => [$client, $staffs, $agents],
            ];
            echo json_encode($data);
        }
    }

    public function getTransactionByDistributorsData()
    {
        if (request()->ajax()) {
            $companies = Company::get();
            $data = [];
            foreach ($companies as $company) {
                $data[$company->company_name] = DistributorTransaction::join('transactions', 'transactions.id', '=', 'distributor_transactions.transaction_id')
                    ->join('distributor_offices', 'distributor_offices.id', '=', 'distributor_transactions.distributor_office_id')
                    ->join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                    ->where(function ($query) {
                        $query->where('transactions.transaction_status_id', 3);
                        $query->orWhere('transactions.transaction_status_id', 4);
                        $query->orWhere('transactions.transaction_status_id', 5);
                    })
                    ->where('distributor_offices.companies_id', $company->id)
                    ->groupBy('distributor_offices.id')
                    ->sum('transaction_details.payment_amount');
            }
            echo json_encode($data);
        }
    }

    public function getTransactionByAgentsData()
    {
        if (request()->ajax()) {
            $agents = Agent::leftJoin('agent_transactions', 'agent_transactions.agents_id', '=', 'agents.id')
                ->leftJoin('transactions', 'transactions.id', '=', 'agent_transactions.transactions_id')
                ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')->select('agents.id as id')->get();

            $data = [];
            foreach ($agents as $agent) {
                $data[getAgentName($agent->id)] = AgentTransaction::join('transactions', 'transactions.id', '=', 'agent_transactions.transactions_id')
                    ->join('agents', 'agents.id', '=', 'agent_transactions.agents_id')
                    ->join('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                    ->where(function ($query) {
                        $query->where('transactions.transaction_status_id', 2);
                        $query->orWhere('transactions.transaction_status_id', 3);
                        $query->orWhere('transactions.transaction_status_id', 4);
                        $query->orWhere('transactions.transaction_status_id', 5);
                    })
                    ->where('agent_transactions.agents_id', $agent->id)
                    ->whereRaw('Date(transaction_details.transaction_date) = CURDATE()')
                    ->groupBy('agent_transactions.agents_id')
                    ->sum('transaction_details.sending_amount');
            }
            echo json_encode($data);
        }
    }

    public function generateDateRange(Carbon $start_date, Carbon $end_date)
    {

        $dates = [];

        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {

            $dates[] = $date->format('Y-m-d');

        }

        return $dates;

    }

    public function changeBeneficiary($sender_id, $beneficiaryId, $transaction_id)
    {
        $beneficiary = $this->beneficiary->getBeneficiaryAccordingToSender($sender_id);
        return view("Transaction::changeBeneficiary", compact('beneficiary', 'beneficiaryId', 'transaction_id'));
    }

    public function storeChangeBeneficiary(Request $request, $transaction_id)
    {
        $transaction = Transaction::find($transaction_id);
        $transaction->beneficiary_id = $request->beneficiary_id;


        $activeAccount = Beneficiary::leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->where('beneficiaries.beneficiary_id', $request->beneficiary_id)
            ->select(['bank_details.id as bank_details_id', 'beneficiary_bank_details.id as bankDetailId', 'bank_details.account_name as accountName', 'bank_details.account_no as accountNo', 'bank_details.bsb', 'bank_details.bank_name as bankName', 'beneficiary_bank_details.current'])
            ->where('beneficiary_bank_details.current', 1)
            ->first();

        $transaction->beneficiaries_bank_details_id = $activeAccount->bank_details_id;
        $transaction->save();
        $tran_ben['transaction_id'] = $transaction_id;
        $beneficiary_detail = getBeneficiaryDetails($request->beneficiary_id);
        $tran_ben['name'] = $beneficiary_detail->first_name . ' ' . $beneficiary_detail->last_name;
        $tran_ben['address'] = $beneficiary_detail->street . ' ' . $beneficiary_detail->suburb . ' ' . $beneficiary_detail->post_code . ' ' . $beneficiary_detail->state . ' ' . $beneficiary_detail->country;
        $tran_ben['phone_number'] = $beneficiary_detail->number;
        $tran_ben['bank_name'] = $beneficiary_detail->bank_name;
        $tran_ben['account_number'] = $beneficiary_detail->account_no;
        $tran_ben['branch_name'] = $beneficiary_detail->bsb;
        $tran_ben['account_name'] = $beneficiary_detail->account_name;
        $tran_ben['pickup_district'] = $request->pickup_district;
        TransactionBeneficiary::updateOrCreate(['transaction_id' => $transaction_id], $tran_ben);
        $notification = array(
            'message' => 'Beneficiary changed successfully!',
            'alert-type' => 'success',
        );
        //  Flash::success('Beneficiary changed successfully.');
        return redirect()->back()->with($notification);
    }

    public function getSendersDropDownDataByAjaxForSearch(Request $request)
    {
        /*  $senders = Sender::join('person', 'person.id', '=', 'senders.person_id')
              ->select(['senders.id', 'senders.added_by', 'person.first_name', 'person.last_name', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS full_name')])
              ->orderBy('senders.id', 'desc');*/
        /*temporary code*/
        $senders = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->join('sender_status', 'senders.sender_status_id', '=', 'sender_status.id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })
            ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->select(['senders.id', 'senders.created_at', 'senders.added_by', 'person.first_name', 'person.last_name', 'person.email', 'senders.sender_status_id', 'phones.number', 'sender_status.name as status', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS full_name')])
            ->orderBy('senders.id', 'desc');
        if (Auth::user()->level_id == 3) {
            $senders = $senders->where('senders.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            $senders = $senders->where('senders.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 4) {
            $distributor_staff = DistributorUser::where('user_id', Auth::user()->id)->first();
            if ($distributor_staff->role_id == 1) {
                $senders = $senders->join('transactions', 'transactions.sender_id', '=', 'senders.id')
                    ->join('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
                    ->where('distributor_transactions.distributor_office_id', $distributor_staff->distributor_office_id)
                    ->distinct();
            } else {
                $senders = $senders->join('transactions', 'transactions.sender_id', '=', 'senders.id')
                    ->join('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
                    ->where('distributor_transactions.assigned_to', Auth::user()->id)
                    ->distinct();

            }
        }
        if ($keyword = $request->q) {
            //$keyword =  str_replace(' ', '', $keyword);

            $senders = $senders->where(function ($q) use ($keyword) {
                $q->where(DB::raw('CONCAT_WS(" ", person.first_name, person.last_name)'), 'like', '%' . $keyword . '%');

            });
            /* $senders = $senders->where(function ($q) use ($keyword) {
                 $q->where('phones.number', 'like', '%' . $keyword . '%')
                     ->orWhere('person.email', 'like', '%' . $keyword . '%')
                     ->orWhere(DB::raw('CONCAT_WS(" ", person.first_name, person.last_name)'), 'like', '%' . $keyword . '%');

             });*/
        }
        $senders = $senders->get();

        $tag = [];
        foreach ($senders as $sender) {
            $tag[] = ['id' => $sender->id, 'text' => $sender->full_name . ' | ' . $sender->email . ' | ' . $sender->number];
            //$tag[] = ['id' => $sender->id, 'text'=>$sender->full_name.' | '. $sender->email .' | '.$sender->number/*.' | + '. get_user_name($sender->added_by)*/];
        }

        echo json_encode($tag);
    }

    public function getBeneficiaryDropDownDataByAjaxForSearch(Request $request)
    {
        $beneficiaries = Beneficiary::join('person', 'person.id', '=', 'beneficiaries.person_id')
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
            ->leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->select(['addresses.street', 'phones.number', 'bank_details.account_no', 'bank_details.account_name', 'beneficiaries.beneficiary_id', 'beneficiaries.added_by', 'person.first_name', 'person.last_name', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
            ->orderBy('beneficiaries.beneficiary_id', 'desc');
        if (Auth::user()->level_id == 3) {
            $beneficiaries = $beneficiaries->where('beneficiaries.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            $beneficiaries = $beneficiaries->where('beneficiaries.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 4) {
            $distributor_staff = DistributorUser::where('user_id', Auth::user()->id)->first();
            if ($distributor_staff->role_id == 1) {
                $beneficiaries = $beneficiaries->join('transactions', 'transactions.beneficiary_id', '=', 'beneficiaries.beneficiary_id')
                    ->join('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
                    ->where('distributor_transactions.distributor_office_id', $distributor_staff->distributor_office_id)
                    ->distinct();
            } else {
                $beneficiaries = $beneficiaries->join('transactions', 'transactions.beneficiary_id', '=', 'beneficiaries.beneficiary_id')
                    ->join('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
                    ->where('distributor_transactions.assigned_to', Auth::user()->id)
                    ->distinct();
            }
        }
        if ($keyword = $request->q) {
            //$keyword =  str_replace(' ', '', $keyword);

            $beneficiaries = $beneficiaries->where(function ($q) use ($keyword) {
                $q->where(DB::raw('CONCAT_WS(" ", person.first_name, person.last_name)'), 'like', '%' . $keyword . '%');
            });
        }
        $beneficiaries = $beneficiaries->get();

        $tag = [];
        foreach ($beneficiaries as $beneficiary) {
            $tag[] = ['id' => $beneficiary->beneficiary_id, 'text' => $beneficiary->full_name . ' | ' . $beneficiary->number . ' | ' . $beneficiary->street];
            // $tag[] = ['id' => $beneficiary->beneficiary_id, 'text' => $beneficiary->full_name.' | '. $beneficiary->number.' | '. $beneficiary->street/* .' | '.$beneficiary->account_no.' | '.$beneficiary->account_name*/];
        }
        echo json_encode($tag);
    }

    public function transactionReport()
    {

        $start = Carbon::now()->subDays(6);
        $end = Carbon::now()->subDays(1);
        $dates = $this->generateDateRange($start, $end);
        foreach ($dates as $date) {
            $dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['transaction_count'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date)->count();
            $dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['payment_amount'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date)->sum('transaction_details.payment_amount');
            $dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['sending_amount'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date)->sum('transaction_details.sending_amount');
            $dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['service_fee'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date)->sum('transaction_details.service_charge');
            $dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['total_exchange_rate'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date)->sum('transaction_details.exchange_rate');
            if ($dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['transaction_count'] == 0) {
                $dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['average_rate'] = 0;
            } else {
                $dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['average_rate'] = ($dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['total_exchange_rate']) / ($dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['transaction_count']);
            }
            $dates_this_week[Carbon::parse($date)->format('D') . ' (' . Carbon::parse($date)->format('d-M-Y') . ')']['service_fee'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date)->sum('transaction_details.service_charge');
        }
        $search_attributes = array();


        if ($this->request->isMethod('post')) {
            $dates_this_week = $this->searchTransactionReport($this->request->all());
            $search_attributes = $this->request->all();

        }
        return view('Transaction::transactionReport', compact('dates_this_week', 'search_attributes'));
    }

    public function searchTransactionReport($request)
    {

        $start = Carbon::parse($request['from'])->format('Y-m-d');
        $end = Carbon::parse($request['to'])->format('Y-m-d');
        $dates = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->whereBetween('transaction_details.transaction_date', [$start, $end])->select('transaction_details.transaction_date')->get();

        foreach ($dates as $date) {
            $dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['transaction_count'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date->transaction_date)->count();
            $dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['payment_amount'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date->transaction_date)->sum('transaction_details.payment_amount');
            $dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['sending_amount'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date->transaction_date)->sum('transaction_details.sending_amount');
            $dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['service_fee'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date->transaction_date)->sum('transaction_details.service_charge');
            $dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['total_exchange_rate'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date->transaction_date)->sum('transaction_details.exchange_rate');
            if ($dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['transaction_count'] == 0) {
                $dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['average_rate'] = 0;
            } else {
                $dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['average_rate'] = ($dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['total_exchange_rate']) / ($dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['transaction_count']);
            }
            $dates_this_week[Carbon::parse($date->transaction_date)->format('D') . ' (' . Carbon::parse($date->transaction_date)->format('d-M-Y') . ')']['service_fee'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
                ->where('transaction_details.transaction_date', '=', $date->transaction_date)->sum('transaction_details.service_charge');
        }
        return $dates_this_week;
    }

    public function pdfInvoice($transaction_id)
    {

        $items['items'] = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')->where('transactions.id', $transaction_id)->first();
        view()->share('items', $items);

        $pdf = PDF::loadView('Transaction::invoicePdf', $items);
        return $pdf->download('transaction.pdf');

        //  return view('Transaction::invoicePdf',$items);
    }

    public function profitPerDay()
    {
        if (!in_array(Auth::user()->level_id, [1, 6])) {
            abort(403, 'Unauthorized action.');
        }
        $final_transaction = [];
        $transactions = $this->get_transaction()->where('transactions.transaction_status_id', '!=', 12)->orderBy('transactionDate', 'desc')->get();
        $transactions = $transactions->groupBy('transactionDate');
        foreach ($transactions as $key => $transaction) {
            $total_transaction['profit'] = 0;
            foreach ($transaction as $tran) {
                $agent_transaction = AgentTransaction::where('transactions_id', $tran->id)->first();

                if ($agent_transaction) {
                    //$profit = ($today->serviceCharge-7) + (($today->sendingAmount * ($today->cost_rate - $agent_transaction->exchange_rate))/$today->cost_rate);
                    $profit = 3 + ((($tran->totalAmount - $tran->serviceCharge) * ($tran->cost_rate - $agent_transaction->exchange_rate)) / $tran->cost_rate);
                } else {
                    $profit = $tran->serviceCharge + ((($tran->totalAmount - $tran->serviceCharge) * ($tran->cost_rate - $tran->exchangeRate)) / $tran->cost_rate);
                }
                $total_transaction['profit'] = $total_transaction['profit'] + $profit;
            }

            $total_transaction['date'] = $key;

            $final_transaction[] = $total_transaction;

        }
        return view('Transaction::tracker/profit', compact('final_transaction'));
    }

    public function get_transaction()
    {
        $transaction = Transaction::leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
            ->leftJoin('person', 'person.id', '=', 'senders.person_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->select('transaction_details.total_to_pay as totalAmount', 'transaction_details.cost_rate as cost_rate', 'person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', 'transactions.added_by as addedBy', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.transaction_date as transactionDate', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transaction_details.service_charge as serviceCharge', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'));
        if (Auth::user()->level_id == 3) {
            $transaction = $transaction->where('transactions.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            $transaction = $transaction->where('transactions.added_by', Auth::user()->id);
        }
        return $transaction;
    }

    public function changeIsVerifiedModal($transaction_id)
    {
        $transaction = Transaction::find($transaction_id);
        $transaction_id = $transaction->id;
        $is_verified = $transaction->is_verified;
        return view("Transaction::modals/changeVerifiedStatus", compact('transaction_id', 'is_verified'));
    }

    public function changeIsVerifiedModalStore(Request $request)
    {
        $tran = Transaction::find($request->transaction_id);
        $tran->is_verified = $request->is_verified;
        $tran->save();
        return $this->success();
    }

    /**
     */
    public function toggleMaxAmountRestrictionForSender($sender_id){
        if(Auth::user()->level_id != 1){
            abort(403);
        }
        $sender = Sender::find($sender_id);
        $sender->is_restricted = !$sender->is_restricted;
        $sender->save();
        if($sender->is_restricted){
            $messageText = 'Sender has been restricted successfully!!';
        }else{
            $messageText = 'Sender has been unrestricted successfully!!';
        }
        $notification = array(
            'message' => $messageText,
            'alert-type' => 'success'
        );
        return redirect()->route('senders.index')->with($notification);

    }
    public function toggleMaxAmountRestrictionForAgent($agent_id)
    {
        if(Auth::user()->level_id != 1){
            abort(403);
        }
        $agent = Agent::find($agent_id);
        $agent->is_restricted = !$agent->is_restricted;
        $agent->save();
        if($agent->is_restricted){
            $messageText = 'Agent has been restricted successfully!!';
        }else{
            $messageText = 'Agent has been unrestricted successfully!!';
        }
        $notification = array(
            'message' => $messageText,
            'alert-type' => 'success'
        );
        return redirect()->route('agents.index')->with($notification);
    }
}

