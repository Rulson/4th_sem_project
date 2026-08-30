<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Imports\SenderImports;
use App\Modules\Agent\Models\Agent;
use App\Modules\Agent\Models\AgentTransaction;
use App\Modules\Application\Models\Application;
use App\Modules\Application\Service\GetApplicationService;
use App\Modules\Distributor\Models\DistributorOffice;
use App\Modules\Distributor\Models\DistributorTransaction;
use App\Modules\Distributor\Models\DistributorUser;
use App\Modules\Referral\Models\ReferralPoints;
use App\Modules\Sender\Models\Sender;
use App\Modules\Settings\Models\Settings;
use App\Modules\Transaction\Models\CommentNote;
use App\Modules\Transaction\Models\Note;
use App\Modules\Transaction\Models\NoteAssign;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionStatus;
use App\Modules\User\Models\ExchangeRate;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserLevel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class UserController extends BaseController
{

    // Validation rules for user create and edit
    protected $rules = [
        'first_name' => 'required|min:2|max:55',
        'last_name' => 'required|min:2|max:55',
        'number' => 'required'
    ];

    function __construct(
        User $user, Agent $agent, Transaction $transaction, DistributorOffice $distributorOffice, DistributorTransaction $distributorTransaction,
        private GetApplicationService $getApplicationService,
    )
    {
        $this->user = $user;
        $this->agent = $agent;
        $this->transaction = $transaction;
        $this->distributorOffice = $distributorOffice;
        $this->distributorTransaction = $distributorTransaction;

        parent::__construct();
    }

    /*
     * Display User Dashboard
     * */
    public function dashboard()
    {
        $total_amount_au = 0;
        $total_amount_np = 0;
        $total_month_au = 0;
        $total_month_np = 0;
        $order = Transaction::leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
            ->leftJoin('person', 'person.id', '=', 'senders.person_id')
            ->select('person.first_name as first_name', 'person.last_name as last_name', 'transactions.id as transaction_id', 'transactions.transaction_status_id as status')
            ->orderBy('transactions.id', 'desc');
        if (Auth::user()->level_id == 3) {
            $orders = $order->leftJoin('agent_transactions', 'agent_transactions.transactions_id', '=', 'transactions.id')->where('agent_transactions.agents_id', getAgentId())/*->orWhere('transactions.added_by', Auth::user()->id)*/->take(10)->get();
        } elseif (Auth::user()->level_id == 5) {
            $orders = $order->where('transactions.added_by', Auth::user()->id)->get();
            $transactions = $this->get_transaction();
            foreach($transactions->get() as $transaction){
                $total_amount_np += $transaction->paymentAmount;
                $total_amount_au += $transaction->sendingAmount;
            }
            $month_transactions = $transactions->where('transaction_date','>=',Carbon::now()->startOfMonth()->toDateString())->where('transaction_date','<=',Carbon::now()->endOfMonth()->toDateString())->get();
            foreach($month_transactions as $m_transaction){
                $total_month_np += $m_transaction->paymentAmount;
                $total_month_au += $m_transaction->sendingAmount;
            }
        } elseif (in_array(Auth::user()->level_id, [1,2,6,7,8])) {
            $orders = $order->take(10)->get();
        } else {
            $orders = '';
        }
        $status = TransactionStatus::pluck('name', 'id')->toArray();
        $txn_count = [];

        foreach ($status as $key => $value) {
            $txn_count[$key] = $this->getTransactionCount($key);
        }

        $rate = ExchangeRate::orderBy('id', 'desc')->first();
        $rate_list = ExchangeRate::whereDate('created_at',$rate->created_at)->orderBy('threshold_amount','desc')->pluck('exchange_rate','threshold_amount')->toArray();
        $a = [];
        $i = 0;
        foreach($rate_list as $key=>$value){
            $a[$i]['rate'] = $value;
            $i++;
        }
        $exchangerate = end($a)['rate'];
//        $exchangerate = ExchangeRate::latest('created_at')->first()->exchange_rate;

        $application = getAppDetailsForWeb();
        $referral_points = (new ReferralPoints)->getRemainingReferralPoints(Auth::user()->id);
        $agentQRCode = null;
        if(Auth::user()->level_id == 3) {
            $agentQRCode =  _generateAgentShareableLinkQR(getAgentId());
        }

        return view("User::".$this->extra_folder."dashboard", compact('referral_points', 'orders', 'txn_count','exchangerate','total_amount_au','total_amount_np','total_month_np','total_month_au', 'application'))
            ->with('total_senders', $this->totalSenders())
            ->with('total_beneficiaries', $this->totalBeneficiaries())
            ->with('all_transactions_count', $this->transactionCount())
            ->with('total_transaction_count', $this->get_total_transaction_count())
            ->with('today_admin_profit', $this->get_admin_profit())
            ->with('agent_total_transaction_count', $this->getAgentTotalTransactionCount())
            ->with('agent_average_rate', $this->getAgentAverageRate())
            ->with('client_average_rate', $this->getClientAverageRate())
            ->with('agentQRCode', $agentQRCode)
            ->with('client_average_rate_for_agent', $this->getClientAverageRate_agent());
    }
    public function downloadQRCode($agentId)
    {
        // Define a temporary file path
        $filePath = 'temp/qr-code.png';

        // Generate and save the QR code to the temporary file
        Storage::disk('local')->put($filePath, QrCode::size(200)->format('png')->generate(_generateAgentShareableLink($agentId)));

        // Stream the file for download
        return response()->download(storage_path("app/{$filePath}"))->deleteFileAfterSend(true);
    }

    public function getTransactionCount($id)
    {

        $transaction = Transaction::leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
            ->leftJoin('person', 'person.id', '=', 'senders.person_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->select('transaction_details.total_to_pay as totalAmount', 'person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', 'transactions.added_by as addedBy', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.transaction_date as transactionDate', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transaction_details.service_charge as serviceCharge', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'));
        if (Auth::user()->level_id == 3) {
            $transaction = $transaction->leftJoin('agent_transactions', 'agent_transactions.transactions_id', '=', 'transactions.id')
                ->where(function($q){
                    $q->Where('agent_transactions.agents_id', getAgentId());
                    $q->orWhere('transactions.added_by', Auth::user()->id);
                });
         //   $transaction = $transaction->where('transactions.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            $transaction = $transaction->where('transactions.added_by', Auth::user()->id);
        }
        /* if (Auth::user()->level_id == 4) {
             $distributorOffice = DB::table('distributor_users')->where('distributor_users.user_id', Auth::user()->id)->first();
             if ($distributorOffice->role_id == 2) {
                 $transaction = $transaction->join('distributor_transactions', 'transactions.id', '=', 'distributor_transactions.transaction_id')
                     ->where('distributor_transactions.distributor_office_id', $distributorOffice->distributor_office_id)
                     ->where('distributor_transactions.assigned_to', Auth::user()->id);
             } else {
                 $transaction = $transaction->join('distributor_transactions', 'transactions.id', '=', 'distributor_transactions.transaction_id')->where('distributor_transactions.distributor_office_id', $distributorOffice->distributor_office_id);
             }
         }*/
        $txn = $transaction->where('transaction_status_id', $id);
        $txns['sending_amount'] = $txn->sum('transaction_details.sending_amount');
        $txns['payment_amount'] = $txn->sum('transaction_details.payment_amount');
        $txns['count'] = $txn->count();
        return $txns;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $checkForStaffDistributor = DistributorUser::where('user_id', Auth::user()->id)->first();
        if (!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || (Auth::user()->level_id == 4 && $checkForStaffDistributor->role_id == 1))) {
            abort(403, 'Unauthorized action.');
        }


        if (Auth::user()->level_id == 4) {
            $distributorOffice = DistributorUser::join('distributor_offices', 'distributor_offices.id', '=', 'distributor_users.distributor_office_id')
                ->where('distributor_users.user_id', Auth::user()->id)
                ->first();

            $data['users'] = User::join('person', 'person.id', '=', 'users.person_id')
                ->join('distributor_users', 'distributor_users.user_id', '=', 'users.id')
                ->join('user_status', 'users.user_status_id', '=', 'user_status.id')
                ->join('levels', 'users.level_id', '=', 'levels.id')
                ->leftJoin('person_phones', function ($q) {
                    $q->on('person_phones.person_id', '=', 'person.id');
                    $q->where('person_phones.current', '=', 1);
                })
                ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
                ->select(['users.id as user_id', 'person.first_name', 'person.last_name', 'users.email', 'users.user_status_id', 'phones.number', 'levels.name as role', 'user_status.name as status', 'users.created_at', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')])
                ->where('distributor_users.distributor_office_id', $distributorOffice->distributor_office_id)
                ->orderBy('users.id', 'desc')
                ->get();


        } else {
            $data['users'] = $this->user->getAll();
        }
        return view("User::index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)) {
            abort(403, 'Unauthorized action.');
        }
        $data['user_roles'] = UserLevel::orderBy('name', 'asc')->whereNotIn('id', [3, 4, 5])->pluck('name', 'id');
        if(Auth::user()->level_id == 2){
            unset($data['user_roles'][1]);
            unset($data['user_roles'][3]);
            unset($data['user_roles'][4]);
            unset($data['user_roles'][6]);
        }
        return view('User::add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* Additional validations for creating user */
        $this->rules['email'] = 'email|min:5|max:55|unique:users';
        $this->rules['password'] = 'required|min:6';
        $this->rules['confirm_password'] = 'required|same:password|min:6';
        $messages = [
            'email.unique' => 'User with the same email address already exists.'
        ];
        $this->validate($request, $this->rules, $messages);
        $data = $request->all();
        if (Auth::user()->level_id != 1) {
            $data['level_id'] = 5;
        }
        // if validates
        $created = $this->user->add($data);
        if ($created) {
            $notification = array(
                'message' => 'User has been created successfully!',
                'alert-type' => 'success',
            );
            // Flash::success('User has been created successfully.');
        }
        return redirect()->route('users.index')->with($notification);
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
        if (!(in_array(Auth::user()->level_id, [1, 2, 6]) || (in_array(Auth::user()->level_id, [3,7,8]) && Auth::user()->id == $id))) {
            abort(403, 'Unauthorized action.');
        }


        $data['user'] = $user_details = $this->user->getUserDetailsByID($id);

        if(Auth::user()->level_id == 2 && in_array($user_details->levelid,[1,3,4,6])){
            abort(403,'Forbidden Action');
        }

        $data['user_roles'] = UserLevel::orderBy('name', 'asc')->where('id', '!=', 4)->pluck('name', 'id');

        if(Auth::user()->level_id == 2){
            unset($data['user_roles'][1]);
            unset($data['user_roles'][3]);
            unset($data['user_roles'][4]);
            unset($data['user_roles'][6]);
        }
        return view('User::edit', $data);
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

        if(!isset($request->level_id)){
            $request->request->add(['level_id' =>Auth::user()->level_id]);
        }


        /* Additional validations for creating user */
        if($request->level_id == 5){
            $this->rules['email'] = 'email|min:5|max:55|unique:users,email,' . $id.',id,application_id,'.$request->application_id;

        }
        else{
            $this->rules['email'] = 'email|min:5|max:55|unique:users,email,' . $id;

        }

        //$this->rules['password'] = 'min:6';
        $this->rules['confirm_password'] = 'required_with:password|same:password';

        $messages = [
            'email.unique' => ($request->level_id == 5)?'User with the same email address already exists in '.getApplicationDetail('id',$request->application_id)->name :'User with the same email address already exists'
        ];

        $this->validate($request, $this->rules, $messages);

        // if validates
        $updated = $this->user->edit($request->all(), $id);
        if ($updated) {
            $notification1 = array(
                'message' => 'User has been updated successfully!',
                'alert-type' => 'success',
            );
            //  Flash::success('User has been updated successfully.');
        }
        if (in_array(Auth::user()->level_id, [3,5,7,8])) {
            return redirect()->back()->with($notification1);

        }
        if (in_array(Auth::user()->level_id, [1,2])) {
            return redirect()->route('users.index')->with($notification1);
        }
    }

    public function credentialPage($user_id)
    {

        if (!((Auth::user()->id == $user_id))) {
            abort(403, 'Unauthorized action.');
        }

        $user = User::where('id', $user_id)->first();
        return view("User::".$this->extra_folder."credential", compact('user_id', 'user'));

    }

    public function credentialStore(Request $request, $user_id)
    {


        $this->validate($request, [
            'password' => 'string|min:6',
            'password_confirmation' => 'required_with:password|same:password'
        ]);


        $user = User::find($user_id);

        $user->email = strtolower($request->email);
        $user->password = Hash::make($request->password);
        $user->save();
        $notification = array(
            'message' => 'Credential updated successfully!',
            'alert-type' => 'success',
        );
        // Flash::success('Credential updated successfully.');

        return redirect()->back()->with($notification);


    }

    public function changeStatus($user_id)
    {
        if (!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)) {
            abort(403, 'Unauthorized action.');
        }
        $user = new User();
        $data = $user->find($user_id);

       if(Auth::user()->level_id == 2){
           if(in_array($data->level_id,[1,3,4,6])){
               abort(403,'Forbidden Action');
           }
       }

        if ($data->user_status_id == 2)
            $data->user_status_id = 3;
        else
            $data->user_status_id = 2;
        $data->save();
        Flash::success('User status has been updated successfully.');

        return redirect()->route('users.index');
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

    public function getResetPassword($id)
    {
        if (!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)) {
            abort(403, 'Unauthorized action.');
        }
        $user = User::find($id);
        if(Auth::user()->level_id == 2 && in_array($user->level_id,[1,3,4,6])){
            abort(403,'Forbidden Action');
        }
        return view("User::password", compact('user'));
    }

    public function resetPassword(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'string|min:6',
            'password_confirmation' => 'required_with:password|same:password'
        ]);


        $user = User::find($id);
        if ($request->password != '')
            $user->password = Hash::make($request['password']);
        $user->save();
        $notification = array(
            'message' => 'Password has been updated successfully!',
            'alert-type' => 'success',
        );
        // Flash::success('Password has been updated successfully.');
        return redirect()->route('users.index')->with($notification);
    }

    public function exchangeRate(Request $request)
    {

        ExchangeRate::create([
            'exchange_rate' => $request->exchange_rate,
            'created_at' => Carbon::today()
        ]);

        return redirect()->route('user.dashboard');
    }

    public function markReadNoteAssigned($note_assign_id)
    {
        if (request()->ajax()) {
            /*  $data = NoteAssign::where('id',$note_assign_id)->first();
              $data->is_read = 1;
              $data->save();*/
            $notes_id = Note::join('notes_assign', 'notes_assign.notes_id', '=', 'notes.id')->where('notes_assign.id', $note_assign_id)->first();

            $admins = User::where('level_id', 1)->orWhere('level_id', 2)->get();


            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2) {
                foreach ($admins as $admin) {

                    $adminid = NoteAssign::where('notes_id', $notes_id->notes_id)->where('user_id', $admin->id)->update([
                        'is_read' => 1
                    ]);
                }

            } else {
                $data = NoteAssign::where('id', $note_assign_id)->first();
                if ($data) {
                    $data->is_read = 1;
                    $data->save();
                }

            }
            return $this->success();
        }

    }

    public function approveClient(Request $request)
    {
        try {
            $recentClient = User::find($request->client_user_id);
             $recentSender = Sender::join('person', 'person.id', 'senders.person_id')
                 ->where('person.id', $recentClient->person_id)->update([
                     'sender_status_id' => 2
                 ]);
             $recentClient->update(['active'=>1,'user_status_id'=>2]);
             if ($recentSender) {

                $clientName = Person::where('id', $recentClient->person_id)->first();
                $application = Application::where('domain_url',request()->getHttpHost())->first();
                 $email_template = getEmailTemplate('type','approve sender',$application);

                 if($email_template){
                     $subject = $email_template->subject;
                     $body = $email_template->message;
                     $data_array_parse = array(
                         'FULL_NAME'  => $clientName['first_name'] . ' ' . $clientName['last_name'],
                         'SENDER_NAME'  => $clientName['first_name'] . ' ' . $clientName['last_name'],

                     );
                     $data_array_parse = format_template_array($application,$data_array_parse);
                     if(!empty($application->playstore_url)){
                         $body = buildTemplate($body,$application);
                     }
                     $subject = parseTemplate($subject,$data_array_parse);
                     $body = parseTemplate($body,$data_array_parse);
                     $view = 'EmailTemplate::Email/email';
                 }
                 else{
                     $subject = 'Account Confirmation';
                     $body = 'We have confirmed your account. You can login and start Sending Money now. You can also login to check Exchange Rate and to calculate amount you want to send.';
                     $view = 'Auth::Email/accountConfirmation';
                 }
                $param = [
                    'to' => strtolower($recentClient['email']),
                    'toName' => $clientName['first_name'] . ' ' . $clientName['last_name'],
                    'body' => $body,
                    'subject' => $subject,
                    'fromEmail' => $application->email,
                    'fromName' => $application->name,
                    'application' => $application
                ];

                Mail::send($view, $param, function ($message) use ($param) {
                    $message->to($param['to'], $param['toName'])
                        ->from($param['fromEmail'], $param['fromName'])
                        ->subject($param['subject']);
                });
                $notification = array(
                    'message' => 'Client has been approved successfully.!',
                    'alert-type' => 'success',
                );


                return redirect()->back()->with($notification);
           }
        } catch (\Exception $e) {

            $message = $e->getMessage() . 'Line #' . $e->getLine();
            flash($message)->error()->important();

            return redirect()->back();

        }

    }

    public function declineClient(Request $request)
    {
        $recentClient = User::find($request->client_user_id);
        Sender::join('person', 'person.id', 'senders.person_id')
            ->where('person.id', $recentClient->person_id)->update([
                'sender_status_id' => 3
            ]);
        $notification = array(
            'message' => 'Client has been declined successfully.!',
            'alert-type' => 'success',
        );
        return redirect()->back()->with($notification);


    }

    public function generateDateRange(Carbon $start_date, Carbon $end_date)
    {

        $dates = [];

        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {

            $dates[] = $date->format('Y-m-d');

        }

        return $dates;

    }

    public function import()
    {
        if (!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)) {
            abort(403, 'Unauthorized action.');
        }
        return view("User::import");
    }

    public function storeImport(Request $request)
    {

        if ($request->hasFile('import_file')) {

            try {
                Excel::import(new SenderImports, request()->file('import_file'));
            } catch (ValidationException $e) {

                return response()->json(['success' => 'errorList', 'message' => $e->errors()]);
            }

            return redirect()->back();
        }
    }

    public function test()
    {
        return view("User::test");
    }

    public function emailtest(){
        $params = [
            'to' => 'info@mantraideas.com',
            'toName' => 'Test',
            'body' => 'This is email test',
            'subject' => 'test',
            'fromEmail' => env('MAIL_FROM_ADDRESS'),
            'fromName' => env('FROM_NAME', $this->getApplicationService->getApplication()->name),
        ];
           Mail::send('Transaction::Email/transactionDelivered', $params, function ($message) use ($params) {
               $message->to($params['to'], $params['toName'])
                   ->from($params['fromEmail'], $params['fromName'])
                   ->subject($params['subject']);
           });
           echo 'email sent successfully';
    }

    public function storeTest(Request $request)
    {
        if (\Illuminate\Support\Facades\Request::file('image')) {

            $destinationPath = 'TestFolder';
            $uniqueid = uniqid();
            $fileName = date('Y-m-d-H-i-s') . '-' . $uniqueid . '.' . $request->image->getClientOriginalExtension();

            $request->image->move($destinationPath, $fileName);
        }
        return redirect()->back();
    }

    public function storeNoteNavbar(Request $request)
    {
        $query = $request->get('notes');

        $setting = Settings::first();
        $setting->update([
            'notes' => $query
        ]);
        if ($setting) {
            echo 1;
        } else {
            echo 0;
        }

    }
    /*   public function new(){
                   $data['institutes'] = DB::table('institutes')->leftJoin('companies', 'institutes.company_id', '=', 'companies.company_id')
               ->leftJoin('requirements','requirements.institution_id','=','institutes.institution_id')
               ->select(['companies.name', 'requirements.requirement_id', 'requirements.turnaround_time', 'requirements.documents', 'requirements.special_note', 'requirements.lodgement_procedure', 'requirements.commission_processing_timeframe', 'requirements.institution_id'])
               ->get();


       return view("User::new",$data);
       }*/


}
