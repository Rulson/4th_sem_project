<?php

namespace App\Modules\Sender\Controllers;

use App\Http\Controllers\BaseController;
use App\Modules\Application\Models\Application;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Beneficiary\Models\BeneficiaryBankDetails;
use App\Modules\Beneficiary\Models\Receivers;
use App\Modules\Coupon\Models\CouponUsage;
use App\Modules\Email\Models\EmailLogs;
use App\Modules\Notification\Models\SendNotification;
use App\Modules\Referral\Models\ReferralPoints;
use App\Modules\Sender\Models\CustomerSender;
use App\Modules\Sender\Models\Document;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\IdentificationDocument;
use App\Modules\Sender\Models\Sender;
use App\Modules\Sender\Models\SenderBeneficiary;
use App\Modules\Sender\Models\SenderStatus;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\User\Models\Address;
use App\Modules\Beneficiary\Models\BankDetails;
use App\Modules\User\Models\AusStates;
use App\Modules\User\Models\ExchangeRate;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use App\Notifications\IdentificationRequestNotification;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;


class SenderController extends BaseController
{

    /* Validation rules for sender create and edit */
    public $rules = [
        'first_name' => 'required|min:2|max:55',
        'last_name' => 'required|min:2|max:55',
        'number' => ['required','regex:/^(?:\+?61|0)4([0-9]{8})$/'],
        'issued_by' => 'required',
        'id_number' => 'required',
        'id_type' => 'required',
        'image' => 'max:10000',
        'street' => 'required',
        'suburb' => 'required',
        'postcode' => 'required',
        'state' => 'required'
    ];
    public $rules1 = [
        'first_name' => 'required|min:2|max:55',
        'last_name' => 'required|min:2|max:55',
        'number' => ['required','regex:/^(?:\+?61|0)4([0-9]{8})$/'],
        'issued_by' => 'required',
        'id_number' => 'required',
        'id_type' => 'required',
        'image' => 'max:10000',
        'address_proof' => 'required|max:10000',
        'street' => 'required',
        'suburb' => 'required',
        'postcode' => 'required',
        'state' => 'required'
    ];

    protected $sender;

    function __construct(Sender $sender, Beneficiary $beneficiary, Request $request)
    {
        $this->sender = $sender;
        $this->beneficiary = $beneficiary;
        $this->request = $request;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!in_array(Auth::user()->level_id,[1,2,3,4,6,8])) {
            abort(403, 'Unauthorized action.');
        }
        /* $senders = Sender::leftJoin('person as v', 'v.id', '=', 'senders.person_id')
              ->leftJoin('sender_status', 'senders.sender_status_id', '=', 'sender_status.id')
              ->leftJoin('person_phones','person_phones.person_id','=','v.id')
              ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
              ->leftJoin('users','users.id','=','senders.added_by')
               ->leftJoin('person as w', 'w.id', '=', 'users.person_id')
             ->orderBy('senders.id', 'desc')
             ->select('senders.id',DB::raw('DATE_FORMAT(senders.created_at, "%d-%b-%Y") as datejoined'), 'v.first_name','v.last_name', 'v.email','phones.number', DB::raw('CONCAT_WS(" ", w.first_name, NULLIF(w.middle_name,""), w.last_name) AS addedBy'), 'sender_status.name','senders.added_by as added_by')->toSql();
          */
        $this_month = Carbon::now()->month;

        $a = format_date(Carbon::now()->startOfWeek());
        $startOfWeek = Carbon::parse($a)->format('Y-m-d');
        $b = format_date(Carbon::now()->endOfWeek());
        $endOfWeek = Carbon::parse($b)->format('Y-m-d');

        /* $data['agent_senders'] = Sender::leftJoin('users', 'users.id', '=', 'senders.added_by')->where('users.level_id', 3);
         $data['admin_senders'] = Sender::leftJoin('users', 'users.id', '=', 'senders.added_by')->whereIn('users.level_id', [1,2]);
         $data['client_senders'] = Sender::leftJoin('users', 'users.id', '=', 'senders.added_by')->where('users.level_id', 5);*/
        /*agent senders*/
        $data['agent_senders_total'] = $this->agent_senders()->count();
        $data['agent_senders_today'] = $this->agent_senders()->whereRaw('Date(senders.created_at) = CURDATE()')->count();
        $data['agent_senders_this_week'] = $this->agent_senders()->whereBetween('senders.created_at', [$startOfWeek, $endOfWeek])->count();
        $data['agent_senders_this_month'] = $this->agent_senders()->whereRaw('MONTH(senders.created_at)=?', $this_month)->count();
        /*admin senders*/
        $data['admin_senders_total'] = $this->admin_senders()->count();
        $data['admin_senders_today'] = $this->admin_senders()->whereRaw('Date(senders.created_at) = CURDATE()')->count();
        $data['admin_senders_this_week'] = $this->admin_senders()->whereBetween('senders.created_at', [$startOfWeek, $endOfWeek])->count();
        $data['admin_senders_this_month'] = $this->admin_senders()->whereMonth('senders.created_at', '=', $this_month)->count();
        /*client senders*/
        $data['client_senders_total'] = $this->client_senders()->count();
        $data['client_senders_today'] = $this->client_senders()->whereRaw('Date(senders.created_at) = CURDATE()')->count();
        $data['client_senders_this_week'] = $this->client_senders()->whereBetween('senders.created_at', [$startOfWeek, $endOfWeek])->count();
        $data['client_senders_this_month'] = $this->client_senders()->whereMonth('senders.created_at', '=', $this_month)->count();

        return view("Sender::index", $data);

    }

    public function agent_senders()
    {
        $data['agent_senders'] = Sender::leftJoin('users', 'users.id', '=', 'senders.added_by')->where('users.level_id', 3);
        return $data['agent_senders'];
    }

    public function admin_senders()
    {
        $data['admin_senders'] = Sender::leftJoin('users', 'users.id', '=', 'senders.added_by')->whereIn('users.level_id', [1, 2]);
        return $data['admin_senders'];
    }

    public function client_senders()
    {
        $data['client_senders'] = Sender::leftJoin('users', 'users.id', '=', 'senders.added_by')->where('users.level_id', 5);
        return $data['client_senders'];
    }

    public function importSender($ref = 0){
        $customer = CustomerSender::select('id','cr_first_name','cr_last_name','cr_email','cr_phone_mobile','cr_address_address',
            'cr_suburb_city','cr_postcode','cr_state','cr_date_of_birth','cr_id_type_id','cr_id_issuer_id','cr_id_number','cr_id_expiry_date')->where('id', '>', $ref)->first();
        //$customer = $customers[0];
            //foreach($customers as $customer){
                $sender1 = Sender::join('person', 'person.id', '=', 'senders.person_id')->where('person.email', $customer->cr_email)->where('senders.added_by', Auth::user()->id)->first();

                if (empty($sender1)) {
                $person = Person::create([
                    'first_name' => $customer->cr_first_name,
                    'last_name' => $customer->cr_last_name,
                    'dob' =>$customer->cr_date_of_birth,
                    'email' => strtolower($customer->cr_email)
                ]);
                $sender = Sender::create([
                    'person_id' => $person->id,
                    'added_by' => current_user_id(),
                    'sender_status_id' => 1 //Change this later
                ]);

                $address = Address::create([
                    'street' => $customer->cr_address_address,
                    'suburb' => $customer->cr_suburb_city,
                    'postcode' => $customer->cr_postcode,
                    'state' => $customer->cr_state,
                    'country_list_id' => 13,
                ]);

                PersonAddress::create([
                    'address_id' => $address->id,
                    'person_id' => $person->id,
                    'current' => 1,
                    'address_status_id' => 1
                ]);
                // Add Phone Number
                $phone = new Phone();
                $phone_id = $phone->add($customer->cr_phone_mobile);
                PersonPhone::create([
                    'phones_id' => $phone_id,
                    'person_id' => $person->id,
                    'current' => 1
                ]);
                $senderInstance = new Sender();
                $document = Document::create([
                    'type' => '',
                    'user_id' => Auth::user()->id,
                    'name' => 'noId.jpg',
                    'shelf_location' => '',
                ]);

                $identification_document = IdentificationDocument::create([
                    'document_id' => $document->id,
                ]);

                $identification = new Identification();
                $identification->issued_by = ($customer->cr_id_issuer_id == null)? '':$customer->cr_id_issuer_id;
                $identification->id_number = ($customer->cr_id_number == null)? '':$customer->cr_id_number;
                $identification->identification_status_id = 2;

                $identification->identification_types_id = ($customer->cr_id_type_id == null)? 1:$customer->cr_id_type_id;

                $identification->expiry_date =($customer->cr_id_expiry_date == null)? '': $customer->cr_id_expiry_date;
                $identification->senders_id = $sender->id;
                $identification->identification_documents_id = $identification_document->id;
                $identification->current = 1;
                $identification->save();

                 $check = Receivers::select('customer_id','rx_first_name','rx_last_name','rx_phone_mobile','rx_address_address','rx_suburb_city','rx_postcode',
                     'rx_state','rx_accountname','rx_account_number','rx_bankname','rx_bankbranch')->where('customer_id',$customer->id)->get();

                 if(count($check) != 0){
                     foreach($check as $check){
                     $person_beneficiary = Person::create([
                         'first_name' => $check->rx_first_name,
                         'last_name' => $check->rx_last_name
                     ]);

                     $beneficiary = Beneficiary::create([
                         'person_id' => $person_beneficiary->id,
                         'added_by' => Auth::user()->id,
                         'created_date' => get_today_date()
                     ]);
                     DB::table('sender_beneficiaries')->insert([
                         'sender_id' => $sender->id,
                         'beneficiary_id' => $beneficiary->beneficiary_id,
                     ]);
                     // Add address
                     $address_beneficiary = Address::create([
                         'street' => $check->rx_address_address,
                         'suburb' =>  $check->rx_suburb_city,
                         'postcode' =>  $check->rx_postcode,
                         'state' =>  $check->rx_state,
                         'country_list_id' =>  154,
                     ]);

                     PersonAddress::create([
                         'address_id' => $address_beneficiary->id,
                         'person_id' => $person_beneficiary->id,
                         'current' => 1,
                         'address_status_id' => 1
                     ]);

                     // Add Phone Number
                     $phone_beneficiary = new Phone();
                     $phone_id_beneficiary = $phone_beneficiary->add($check->rx_phone_mobile);
                     PersonPhone::create([
                         'phones_id' => $phone_id_beneficiary,
                         'person_id' => $person_beneficiary->id,
                         'current' => 1
                     ]);

                     // Add Bank Details
                     $bank_details = BankDetails::create([
                         'account_name' => $check->rx_accountname,
                         'account_no' => $check->rx_account_number,
                         'bsb' => $check->rx_bankbranch,
                         'bank_name' => $check->rx_bankname,
                         'current' => 1
                     ]);

                     BeneficiaryBankDetails::create([
                         'bank_details_id' => $bank_details->id,
                         'beneficiaries_beneficiary_id' => $beneficiary->beneficiary_id,
                         'current' => 1
                     ]);
                     }
                 }

            }
        //    return Response::make( '', 302 )->header( 'Location', route('senders.import', ['id' => $customer->id]) );
        //}
        //return redirect()->to(route('senders.import', ['id' => $customer->id]))->send();
        //return redirect(route('senders.import', ['id' => $customer->id]));
        $url = 'http://cashnepal.com.au//import-customers/'.$customer->id;
        echo '<script>window.location.href = "'.$url.'"</script>';
        echo 'successfully sender and beneficiary imported : '.$customer->id;
        exit;
    }


    public function getSendersDataByAjax()
    {
        $result = DB::table('sender_views');
        $unrestrictedSenders = Sender::where('is_restricted', 0)->pluck('id')->toArray();

        if (Auth::user()->level_id == 3) {
            $result = $result->where('added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            $result = $result->where('added_by', Auth::user()->id);
        }
        $result = $result->where('name', '!=', 'Blacklisted');

        return DataTables::of($result)
            ->addColumn('action', function ($data) use($unrestrictedSenders) {
                if (in_array(Auth::user()->level_id, [1,2,3,8])) {
                    $edit_btn = '<a href="' . route('sender.edit', [$data->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Edit" class="btn btn-sm btn-primary"><i
                                            class="fa fa-edit"></i></a>';
                } else {
                    $edit_btn = '';
                }
                if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 8) {
                    $user_check = User::leftJoin('senders', 'senders.person_id', '=', 'users.person_id')->where('senders.id', $data->id)->first();
                    if (!$user_check) {
                        $user_btn = '<a href="' . route('sender.createUser', [$data->id]) . '" data-toggle="tooltip" data-placement="bottom" onclick="return confirm(\'Are you sure? You want to add him as a user\')" title="Create User" class="btn btn-sm btn-warning"><i
                                            class="fa fa-user"></i></a>';
                    } else {
                        $user_btn = '';
                    }
                } else {
                    $user_btn = '';
                }
                if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 8) {
                    $sender_transaction_check = Transaction::where('sender_id', $data->id)->first();
                    $if_user_or_not = Sender::leftJoin('users', 'users.person_id', '=', 'senders.person_id')->where('senders.id', $data->id)->first();
                    $check_if_beneficiary = SenderBeneficiary::where('sender_id', $data->id)->first();
                    if ($sender_transaction_check && $if_user_or_not && $check_if_beneficiary) {
                        $delete_btn = '';
                    } else {

                        $delete_btn = '<a href="' . route('sender.delete', [$data->id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure ,you want to delete this sender?\')"><i
                                            class="fa fa-trash"></i></a>';
                    }

                } else {
                    $delete_btn = '';
                }
                if(in_array($data->id, $unrestrictedSenders)){
                    $restrict_text = 'restrict transaction';
                    $restrict_btn_style = 'btn-warning';
                }else{
                    $restrict_text = 'unrestrict transaction';
                    $restrict_btn_style = 'btn-success';
                }
                if(Auth::user()->level_id ==1){
                    $restrict_btn = '<a href="' . route('senders.toggle-restriction', [$data->id]) . '" data-toggle="tooltip" data-placement="bottom" title="' . ucfirst($restrict_text). '" class="btn btn-sm ' . $restrict_btn_style .'  " onclick="return confirm(\'Are you sure you want to ' . $restrict_text. ' for this sender?\')" ><i
                                            class="fa fa-exclamation-triangle"></i></a>';
                }else{
                    $restrict_btn = '';
                }

                return '<a href="' . route('sender.show', [$data->id]) . '" data-toggle="tooltip" data-placement="bottom" title="View"
                                   class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>' . $user_btn . $edit_btn . $delete_btn. $restrict_btn;
            })
            ->editColumn('id', function ($data) {
                return format_id($data->id, "S");
            })->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!in_array(Auth::user()->level_id ,[1,2,3])) {
            abort(403, 'Unauthorized action.');
        }
        $suburb = AusStates::select('name')->where('type','aus_suburb')->pluck('name','name');

        return view('Sender::add',compact('suburb'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $today_carbon = Carbon::today();
        $today=Carbon::parse($today_carbon)->format('d-M-Y');
    //    $expiry=Carbon::parse($request->expiry_date)->format('d-M-Y');
         if(!empty($request['expiry_date'])){

             $request['expiry_date']= insert_dateformat($request->expiry_date);
//             $request['expiry_date']= Carbon::createFromFormat('d/m/Y', $request->expiry_date)->format('Y-m-d');
             //,Carbon::parse($request->expiry_date)->format('d-M-Y'));
             $this->rules1['expiry_date'] = 'required|after_or_equal:'.$today;
             $this->rules['expiry_date'] = 'required|after_or_equal:'.$today;

             /* $request['expiry_date']=Carbon::createFromFormat('d-M-Y', $request->expiry_date)->format('d/m/Y');
             */

         }else{
             $this->rules1['expiry_date'] = 'required';
             $this->rules['expiry_date'] = 'required';
         }
        if(!empty($request['dob'])){

            $request['dob']= Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d');
            //,Carbon::parse($request->expiry_date)->format('d-M-Y'));
            $this->rules1['dob'] = 'required|before_or_equal:'.$today;
            $this->rules['dob'] = 'required|before_or_equal:'.$today;

            /* $request['expiry_date']=Carbon::createFromFormat('d-M-Y', $request->expiry_date)->format('d/m/Y');
            */

        }else{
            $this->rules1['dob'] = 'required';
            $this->rules['dob'] = 'required';
        }


        if ($request->ajax()) {

            $sender = Sender::join('person', 'person.id', '=', 'senders.person_id')->where('person.email', $request->email)->where('senders.added_by', Auth::user()->id)->first();
            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2) {
                $validator1 = \Validator::make($request->all(), $this->rules1, [
                    'number.regex' => 'Please Enter Valid Phone Number'
                ]);
            } else {
                $validator1 = \Validator::make($request->all(), $this->rules, [
                    'number.regex' => 'Please Enter Valid Phone Number'
                ]);
            }

            if ($validator1->fails()) {
                return $this->fail(['errors' => $validator1->getMessageBag()->toArray()]);

            }

            if ($sender) {
                if ($request->email == $sender->email) {

                    $validator = Validator::make($request->all(), ['email' => 'required | max:255|unique:person']);
                }
            } else {
                $validator = Validator::make($request->all(), ['email' => 'required | max:255']);
            }
            if ($validator->fails())
                return $this->fail(['errors' => $validator->getMessageBag()->toArray()]);
            $sender_id = $this->sender->add($request->all());
            return $this->success(['sender_id' => $sender_id, 'number' => $request->get('number'), 'email' => $request->get('email'), 'fullname' => $request->get('first_name') . ' ' . $request->get('last_name'), 'status' => 'confirmed']);
        } else {
            $sender = Sender::join('person', 'person.id', '=', 'senders.person_id')->where('person.email', $request->email)->first();
            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2) {
                $this->validate($request, $this->rules1, [
                    'number.regex' => 'Please Enter Valid Phone Number'
                ]);
            } else {
                $this->validate($request, $this->rules, [
                    'number.regex' => 'Please Enter Valid Phone Number'
                ]);
            }
            if ($sender) {
                if ($request->email == $sender->email) {
                    $this->validate($request, ['email' => 'required | max:255|unique:person']);
                }
            } else {
                $this->validate($request, ['email' => 'required | max:255']);
            }
            // if validates
            $created = $this->sender->add($request->all());
            if ($created) {
                $notification1 = array(
                    'message' => 'Sender has been created successfully!',
                    'alert-type' => 'success'
                );
           //     Flash::success('Sender has been created successfully.');
            }
            return redirect()->route('senders.index')->with($notification1);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $addedByCheck = Sender::where('id', $id)->first();
        $addedByCheckForClient = Sender::leftJoin('person', 'person.id', '=', 'senders.person_id')->leftJoin('users', 'users.person_id', '=', 'senders.person_id')->where('users.id', Auth::user()->id)->where('senders.id', $id)->first();
        if (!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2|| Auth::user()->level_id == 6 || Auth::user()->level_id == 8 ||  (Auth::user()->level_id == 3 && $addedByCheck->added_by == Auth::user()->id) || (Auth::user()->level_id == 5 && isset($addedByCheckForClient)))) {
            abort(403, 'Unauthorized action.');
        }

        $sender_status = SenderStatus::all();
        $transaction = [];
        $transactionAndSender = Transaction::leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
            ->leftJoin('person', 'person.id', '=', 'senders.person_id')
            ->leftJoin('status', 'status.id', '=', 'transactions.transaction_status_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->select('transaction_details.total_to_pay as totalAmount', 'status.name as status', 'person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', 'transactions.added_by as addedBy', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.transaction_date as transactionDate', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transaction_details.service_charge as serviceCharge', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'))
            ->where('senders.id', $id)
            ->orderBy('transactions.id', 'desc')
            ->get();
        $beneficiary = Transaction::leftJoin('beneficiaries', 'beneficiaries.beneficiary_id', '=', 'transactions.beneficiary_id')
            ->leftJoin('person', 'person.id', '=', 'beneficiaries.person_id')
            ->select('person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS beneficiary_full_name'))
            ->get();
        $i = 0;
        $transaction = [];
        $transactionData['sumSendingAmount'] = 0;
        $transactionData['sumPaymentAmount'] = 0;

        foreach ($transactionAndSender as $datas) {
            $transaction[$i]['transaction_id'] = $datas->id;
            $transaction[$i]['sendingAmount'] = $datas->sendingAmount;
            $transaction[$i]['paymentAmount'] = $datas->paymentAmount;
            $transaction[$i]['exchangeRate'] = $datas->exchangeRate;
            $transaction[$i]['date'] = $datas->transactionDate;
            $transaction[$i]['totalAmount'] = $datas->totalAmount;
            $transaction[$i]['serviceCharge'] = $datas->serviceCharge;
            $transaction[$i]['addedBy'] = $datas->addedBy;
            $transaction[$i]['status'] = $datas->status;
            $transactionData['sumSendingAmount'] = $transactionData['sumSendingAmount'] + $datas->sendingAmount;
            $transactionData['sumPaymentAmount'] = $transactionData['sumPaymentAmount'] + $datas->paymentAmount;

            foreach ($beneficiary as $datas1) {

                if ($datas->id == $datas1->id) {
                    $transaction[$i]['beneficiary_full_name'] = $datas1->beneficiary_full_name;
                }
            }
            $i++;
        }
        $sender = $this->sender->getSenderDetailById($id);
        $transaction_count = Transaction::where('sender_id', $id)
            ->count();

        $beneficiary = DB::table('sender_beneficiaries')
            ->where('sender_beneficiaries.sender_id', $id)
            ->count();
        $allIdentifications = Identification::join('identification_types', 'identification_types.id', '=', 'identifications.identification_types_id')
            ->where('identifications.senders_id', $id)
            ->orderBy('identifications.identification_id', 'desc')->get();
        $allAddress = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->join('person_address', 'person_address.person_id', '=', 'person.id')
            ->join('addresses', 'addresses.id', '=', 'person_address.address_id')
            ->join('country_list', 'country_list.id', '=', 'addresses.country_list_id')
            ->where('senders.id', $id)
            ->select('country_list.name as country', 'addresses.*', 'person_address.current as current', 'addresses.id as addressId')
            ->orderBy('person_address.id', 'desc')->get();
        $sender_beneficiary = $this->beneficiary->getBeneficiaryAccordingToSender($id);

        $identification = Identification::join('identification_documents', 'identification_documents.id', '=', 'identifications.identification_documents_id')
            ->join('documents', 'documents.id', '=', 'identification_documents.document_id')
            ->where('identifications.current', 1)
            ->where('identifications.Identification_status_id', 2)
            ->where('identifications.senders_id',$sender->sender_id)
            ->select('identifications.senders_id as senderId','identifications.id_number', 'identifications.issued_by', 'identifications.id_number', 'identifications.expiry_date', 'documents.name','documents.name1','documents.address_proof')
            ->orderBy('documents.id', 'desc')->first();
        if(!empty($identification)){
            $first_doc = asset('identification/'.$identification->name);
            $second_doc = isset($identification->name1) ? asset('identification/'.$identification->name1) : '';
            $address_proof_doc = isset($identification->address_proof) ? asset('identification/'.$identification->address_proof) : '';
        }else{
            $first_doc = '';
            $second_doc = '';
            $address_proof_doc = '';
        }
        $sender_user = Sender::leftJoin('person','person.id','=','senders.person_id')
            ->leftJoin('users','users.person_id','=','person.id')
            ->where('senders.id',$id)
            ->select('users.id as user_id')
            ->first();

        $allCoupons = [];
        $allReferrals = [];
        $referral_points= [];
        if($sender_user){
            $allCoupons = CouponUsage::with('coupon:name,code,discount_value,discount_unit,id')->where('user_id',$sender_user->user_id)->orderBy('created_at','desc')->get();
            $allReferrals = ReferralPoints::where('claimed_by',$sender_user->user_id)->orderBy('date','desc')->get();
            $referral_points = (new ReferralPoints)->getReferralPoints($sender_user->user_id);
        }
        return view("Sender::".$this->extra_folder."show", compact('allCoupons','allReferrals','referral_points','first_doc','second_doc','address_proof_doc','allAddress', 'allIdentifications', 'sender', 'sender_beneficiary', 'sender_status', 'transaction_count', 'beneficiary', 'transaction', 'transactionData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!in_array(Auth::user()->level_id, [1,2,3,8])) {
            abort(403, 'Unauthorized action.');
        }
        $addedByCheck = Sender::where('id', $id)->first();
        $addedByCheckForClient = Sender::leftJoin('person', 'person.id', '=', 'senders.person_id')->leftJoin('users', 'users.person_id', '=', 'senders.person_id')->where('users.id', Auth::user()->id)->where('senders.id', $id)->first();
        if (!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2|| Auth::user()->level_id == 6 || Auth::user()->level_id == 8 || (Auth::user()->level_id == 3 && $addedByCheck->added_by == Auth::user()->id) || (Auth::user()->level_id == 5 && isset($addedByCheckForClient)))) {
            abort(403, 'Unauthorized action.');
        }
        $sender = $this->sender->getSenderDetailById($id);
        $suburb = AusStates::select('name')->where('type','aus_suburb')->pluck('name','name');
        return view('Sender::edit', compact('sender','suburb'));
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
        /*  $this->rules['email'] = 'email|min:5|max:55|unique:senders,' . $id;
         //$this->rules['password'] = 'min:6';
         $this->rules['confirm_password'] = 'required_with:password|same:password';*/
    /*    $today_carbon = Carbon::today()->format('d/m/Y');
        $this->rules['expiry_date'] = 'required|after_or_equal:'.$today_carbon;*/


       /* $messages = [
            'email.unique' => 'Sender with the same email address already exists.'
        ];*/

//        $this->validate($request, $this->rules, $messages);

        // if validates
        if (request()->ajax()) {
            $sender_id = $this->sender->edit($request->all(), $id);
            return $this->success(['sender_id' => $sender_id, 'number' => $request->get('number'), 'email' => $request->get('email'), 'fullname' => $request->get('first_name') . ' ' . $request->get('last_name'), 'status' => 'confirmed']);
        } else {
            if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2) {
                $this->validate($request, $this->rules1,[
                    'number.regex' => 'Please Enter Valid Number'
                ]);
            } else {
                $this->validate($request, $this->rules,[
                    'number.regex' => 'Please Enter Valid Number'
                ]);
            }
            $updated = $this->sender->edit($request->all(), $id);
            if ($updated) {
                $notification = array(
                    'message' => 'Sender has been updated successfully!!',
                    'alert-type' => 'success'
                );
               // Flash::success('Sender has been updated successfully!');
            }
            if (isset($request->modal))
                return redirect()->back()->with($notification);

            else {
                return redirect()->route('senders.index')->with($notification);
            }
        }
    }


    public function changeStatus(Request $request, $sender_id)
    {
        if (request()->ajax()) {
            $sender = Sender::find($sender_id);
            $sender->sender_status_id = $request->status_id;
            if($request->status_id == 2){
                $sender->similar_ids = null;
            }
            $sender->save();
            if($request->status_id == 4){
                $user = User::where('person_id',$sender->person_id)->first();
                if($user){
                    $user->user_status_id = 3;
                    $user->save();
                }
            }
            if($request->status_id == 2){
                $user = User::where('person_id',$sender->person_id)->first();
                if($user){
                    $user->user_status_id = 2;
                    $user->save();
                }
                 $message = 'Your identification has been approved by admin.';
                 $user->notify(new IdentificationRequestNotification($message));

            }
            return $this->success(['status' => 1,'sender_id'=>$sender_id]);
        } else {
            $sender = Sender::find($sender_id);
            $sender->sender_status_id = $request->status_id;
            if($request->status_id == 2){
                $sender->similar_ids = null;
            }
            $sender->save();
            if($request->status_id == 4){
                $user = User::where('person_id',$sender->person_id)->first();
                if($user){
                    $user->user_status_id = 3;
                    $user->save();
                }
            }
            if($request->status_id == 2){
                $user = User::where('person_id',$sender->person_id)->first();
                if($user){
                    $user->user_status_id = 2;
                    $user->save();
                }
                $message = 'Your identification has been approved by admin.';
                $user->notify(new IdentificationRequestNotification($message));
            }
            $notification = array(
                'message' => 'Sender status has been updated successfully!',
                'alert-type' => 'success',
            );
            if ($request->type) {

              //  Flash::success('Sender status has been updated successfully.');
                return redirect()->back()->with($notification);
            } else {
                //Flash::success('Sender status has been updated successfully.');
                return redirect()->route('senders.index')->with($notification);
            }
        }


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

    public function update_identification(Request $request, $id)
    {

        Identification::where('senders_id', $id)
            ->update([
                'identifications.issued_by' => $request->issued_by,
                'identifications.id_number' => $request->id_number,
               // 'identifications.expiry_date' => insert_dateformat($request->expiry_date),
                'identifications.expiry_date' =>$request->expiry_date,
                'identifications.identification_types_id' => $request->id_type
            ]);

        if (\Illuminate\Support\Facades\Request::hasfile('image')) {
            $file = $request['image'];

            $destinationPath = 'identification';
            $fileName = date('Y-m-d-H-i-s') . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);

            $fileName1 = null;
            if(\Illuminate\Support\Facades\Request::hasfile('image1')){
                $file1 = $request['image1'];
                $fileName1 = date('Y-m-d-H-i-s') . $file1->getClientOriginalName();
                $file1->move($destinationPath, $fileName1);
            }

            $data = DB::table('senders')
                ->join('identifications', 'senders.id', '=', 'identifications.senders_id')
                ->join('identification_documents', 'identifications.identification_documents_id', '=', 'identification_documents.id')
                ->join('documents', 'identification_documents.document_id', '=', 'documents.id')
                ->where('senders.id', $id)
                ->update([
                    'documents.name' => $fileName,
                    'documents.name1' => $fileName1
                ]);
        }
        $notification = array(
            'message' => 'Identification updated successfully.!',
            'alert-type' => 'success',
        );
        return redirect()->back()->with($notification);

    }

    public function viewIdentification($id)
    {
        $identification = Identification::join('identification_documents', 'identification_documents.id', '=', 'identifications.identification_documents_id')
            ->join('documents', 'documents.id', '=', 'identification_documents.document_id')
            ->where('identifications.current', 1)
            ->where('identifications.Identification_status_id', 2)
            ->where('identifications.senders_id', $id)
            ->select('identifications.senders_id as senderId', 'identifications.id_number', 'identifications.issued_by', 'identifications.id_number', 'identifications.expiry_date', 'documents.name')
            ->orderBy('documents.id', 'desc')->first();
        return view('Sender::viewIdentification', compact('identification'));
    }

    public function getIdentification($id)
    {
        $identification = Identification::join('identification_documents', 'identification_documents.id', '=', 'identifications.identification_documents_id')
            ->join('documents', 'documents.id', '=', 'identification_documents.document_id')
            ->where('identifications.current', 1)
            ->where('identifications.Identification_status_id', 2)
            ->where('identifications.senders_id', $id)
            ->select('identifications.id_number', 'identifications.issued_by', 'identifications.id_number', 'identifications.expiry_date', 'documents.name','documents.address_proof')
            ->orderBy('documents.id', 'desc')->first();
        if(request('type') == 'address_proof'){
            $file = base_path() . '/public/identification/' . $identification->address_proof;
        }else{
            $file = base_path() . '/public/identification/' . $identification->name;
        }
        return response()->download($file, $identification->name);
        /* $identification = Identification::join('identification_documents', 'identification_documents.id', '=', 'identifications.identification_documents_id')
             ->join('documents', 'documents.id', '=', 'identification_documents.document_id')
             ->where('identifications.current', 1)
             ->where('identifications.Identification_status_id', 2)
             ->where('identifications.senders_id', $id)
             ->select('identifications.id_number', 'identifications.issued_by', 'identifications.id_number', 'identifications.expiry_date', 'documents.name')
             ->orderBy('documents.id', 'desc')->first();



         $file = base_path() . '/public/identification/' . $identification->name;

         $headers = array(
             'Content-Type: application/pdf',
         );
         return response()->download($file, $identification->name, $headers);*/

    }

    public function showAddAddress($senderId)
    {
        return view("Sender::".$this->extra_folder."addAddressModal", compact('senderId'));
    }

    public function showAddNewIdentifications($senderId)
    {
        return view("Sender::".$this->extra_folder."addNewIdentificationModal", compact('senderId'));
    }

    public function addNewIdentifications(Request $request, $sender_id)
    {
        $sender = Sender::join('person', 'person.id', '=', 'senders.person_id')->where('senders.id', $sender_id)->select('senders.*', 'person.id as person_id')->first();
        $senderInstance = new Sender();
        $identification_document_id = $senderInstance->uploadIdentification($request->all(), $sender->person_id);
        // Add identification
        $identification = Identification::create([
            'issued_by' => $request['issued_by'],
            'id_number' => $request['id_number'],
            'identification_status_id' => 1,
            'identification_types_id' => $request['id_type'],
            'state' => $request['state'],
            'expiry_date' => insert_dateformat($request['expiry_date']),
            //'expiry_date' => $request['expiry_date'],
            'senders_id' => $sender_id,
            'identification_documents_id' => $identification_document_id,
            'current' => 1
        ]);
        if(Auth::user()->level_id == 5){
            if($identification){
                $sender->sender_status_id = 5;
                $sender->save();
            }
            $message = 'Your identification is currently under review by admin.';
            (auth()->user())->notify(new IdentificationRequestNotification($message));
        }
        $notification = array(
            'message' => 'New Identification request sent successfully.!',
            'alert-type' => 'success',
        );
       // Flash::success('New Identification request sent successfully.');
        return redirect()->back()->with($notification);
    }

    public function addNewAddress(Request $request, $sender_id)
    {
        $person = Sender::where('id', $sender_id)->first();

        $address = Address::create([
            'street' => $request->street,
            'suburb' => $request->suburb,
            'postcode' => $request->postcode,
            'state' => $request->state,
            'country_list_id' => $request->country_list_id
        ]);

        $allAddress = PersonAddress::where('person_id', $person->person_id)->get();
        foreach ($allAddress as $addresss) {
            PersonAddress::where('id', $addresss->id)->update(['current' => 0]);
        }
        PersonAddress::create([
            'address_id' => $address->id,
            'person_id' => $person->person_id,
            'current' => 1,
            'address_status_id' => 1
        ]);
        $notification = array(
            'message' => 'New address added successfully.!',
            'alert-type' => 'success',
        );

        //Flash::success('New address added successfully.');
        return redirect()->back()->with($notification);
    }

    public function approveIdentification(Request $request)
    {
        $identity = Identification::find($request->identification_id);
        $identity->current = 1;
        $identity->Identification_status_id = 2;
        $identity->save();

        $sender_detail = Sender::leftJoin('person','person.id','=','senders.person_id')->
        leftJoin('users','users.person_id','person.id')->select('users.id as user_id')->where('senders.id',$request->sender_id)->first();
        if(!empty($sender_detail)){
            $user = User::find($sender_detail->user_id);
            if($user){
                $message = 'Your identification has been approved by admin.';
                $user->notify(new IdentificationRequestNotification($message));
            }
        }
        $notification = array(
            'message' => 'Identification  approved successfully!',
            'alert-type' => 'success'
        );
        //Flash::success('Identification  approved successfully.');
        return redirect()->back()->with($notification);
    }

    public function declineIdentification(Request $request)
    {
        $identity = Identification::find($request->identification_id);
        $identity->current = 1;
        $identity->Identification_status_id = 3;
        $identity->save();

        $sender_detail = Sender::leftJoin('person','person.id','=','senders.person_id')->
        leftJoin('users','users.person_id','person.id')->select('users.id as user_id')->where('senders.id',$request->sender_id)->first();
        if(!empty($sender_detail)){
            $user = User::find($sender_detail->user_id);
            if($user){
                $message = 'Your identification request has been declined. Please contact admin for more information.';
                $user->notify(new IdentificationRequestNotification($message));
            }
        }
        $notification = array(
            'message' => 'Identification declined successfully!',
            'alert-type' => 'success'
        );
       // Flash::success('Identification declined successfully.');
        return redirect()->back()->with($notification);
    }

    public function viewSearch()
    {
        /*  $data['senders'] = $this->sender->getAll();*/
        if (in_array(Auth::user()->level_id,[1,2,6,8])) {
            $data['added_by'] = User::join('person', 'person.id', '=', 'users.person_id')->select('users.id', 'person.first_name', 'person.last_name')->get();
        }
        if (Auth::user()->level_id == 3) {
            $data['added_by'] = User::join('person', 'person.id', '=', 'users.person_id')->select('users.id', 'person.first_name', 'person.last_name')->where('users.id', Auth::user()->id)->get();
        }
        $data['status'] = DB::table('sender_status')->get();
        $data['value'] = SenderStatus::all();
        $data['search_attributes'] = array();
        if ($this->request->isMethod('post')) {

            $data['senders_data'] = $this->searchResults($this->request->all());
            $data['search_attributes'] = $this->request->all();


        }

        return view("Sender::search", $data);
    }

    public function searchResults($request)
    {
        $sender_query = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->join('sender_status', 'senders.sender_status_id', '=', 'sender_status.id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->select(['senders.id as sender_id', 'senders.added_by', 'senders.created_at as dateAdded', 'senders.added_by', 'person.first_name', 'person.last_name', 'person.email', 'senders.sender_status_id', 'phones.number', 'sender_status.name as status', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')]);

        if (isset($request['first_name'])) {
            $sender_query = $sender_query->where('person.first_name', $request['first_name']);
        }
        if (isset($request['phone_number'])) {
            $sender_query = $sender_query->where('phones.number', $request['phone_number']);
        }
        if (isset($request['last_name'])) {
            if (isset($request['first_name']) && isset($request['last_name'])) {
                $sender_query = $sender_query->where('person.first_name', $request['first_name'])->where('person.last_name', $request['last_name']);
            } else {
                $sender_query = $sender_query->where('person.last_name', $request['last_name']);
            }
        }
        if (isset($request['added_by'])) {
            if ($request['added_by'] == 0) {
            } else {
                $sender_query = $sender_query->where('senders.added_by', $request['added_by']);
            }
        }
        /* if (isset($request['date_joined'])) {
             $date = Carbon::parse($request['date_joined'])->formatLocalized('%Y-%m-%d');
             $sender_query = $sender_query->where(DB::raw("(STR_TO_DATE(senders.created_at,'%Y-%m-%d'))"), $date);
         }*/

        if ($request['date_joined'] != '') {

            $dates = explode(' - ', $request['date_joined']);
            $dates[0] = Carbon::parse($dates[0])->formatLocalized('%Y-%m-%d');
            $dates[1] = Carbon::parse($dates[1])->formatLocalized('%Y-%m-%d');
            $sender_query = $sender_query->whereBetween(DB::raw("(STR_TO_DATE(senders.created_at,'%Y-%m-%d'))"), array((string)$dates[0], (string)$dates[1]));

        }
        if (isset($request['sender_status'])) {
            $sender_query = $sender_query->whereIn('sender_status_id', $request['sender_status']);
        }
        if (Auth::user()->level_id == 3) {
            $sender_query = $sender_query->where('senders.added_by', Auth::user()->id);
        }
        return $sender_query->orderBy('senders.id', 'desc')->get();
    }

    public function showChangeStatusModal($sender_id)
    {
        $sender_status = SenderStatus::all();
        $sender = $this->sender->getSenderDetailById($sender_id);

        return view("Transaction::modals/changeStatusModal", compact('sender_status', 'sender'));
    }

    public function createUser($sender_id)
    {
        $sender = Sender::find($sender_id);

        $person = Person::find($sender->person_id);

        $application = Application::where('agent_id',$sender->added_by)->first();
        if(!$application){
            $application = Application::first();

        }

        $user_checkk = User::where('email', $person->email)->where('application_id',$application->id)->first();

        if (!$user_checkk) {

            $url = route('set.sender.password', uniqid());
            $user = User::create([
                'level_id' => 5,
                'user_status_id' => 1, // Pending
                'person_id' => $sender->person_id,
                'email' => strtolower($person->email),
                'password' => '',
                'api_token' => getApiToken(),
                'application_id'=>$application->id
            ]);
            $user->auth_code = uniqid() . md5($user->id);
            $user->save();
            if ($user) {
                $userName = Person::where('id', $sender->person_id)->first();
                // $application = Application::where('domain_url',request()->getHttpHost())->first();
                /*$application = Application::where('agent_id',$sender->added_by)->first();

                if(!$application){
                    $application = Application::where('package_name','com.ideas.nepalpaisa')->first();
                }*/
                //$url = route('activate', $user['auth_code']);
                $url = route('set.sender.password', $user['auth_code']);
                $email_template = getEmailTemplate('type','create sender user',$application);

                if($email_template){
                    $subject = $email_template->subject;
                    $body = $email_template->message;
                    $data_array_parse = array(
                        'FULL_NAME'  => $userName['first_name'] . ' ' . $userName['last_name'],
                        'SENDER_NAME'  => $userName['first_name'] . ' ' . $userName['last_name'],
                        'PASSWORD_UPDATE_LINK'  => '<a href="'.$url.'">Activate Now</a>',

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
                    $subject = 'Activate your account';
                    $body = 'Welcome to '.$application->name.'. We are glad to announce you that we have recently launched our mobile Apps on iOS & Android of '.$application->name.'. Now Sending Money to Nepal is more easier and secure with us. Please activate the account by clicking on link below.<br><br> <a href="' . $url . '">Activate Now</a><br><br>';
                    $view = 'Auth::Email/activation';
                }

                $param = [
                    'to' => strtolower($person->email),
                    'toName' => $userName['first_name'] . ' ' . $userName['last_name'],
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
            }
            $notification = array(
                'message' => 'User has been created successfully.',
                'alert-type' => 'success',
            );
            return redirect()->route('senders.index')->with($notification);
        } else {
            $notification = array(
                'message' => 'User with the same email already exists.',
                'alert-type' => 'error',
            );
            return redirect()->route('senders.index')->with($notification);
        }

    }

    public function deleteSender($sender_id)
    {
        $sender = Sender::find($sender_id);


        if (!empty($sender)) {
            $person = Person::where('id', $sender->person_id)->first();
            if ($person) {
                /* sender address delete */
                $person_address = PersonAddress::where('person_id', $person->id)->first();
                if($person_address){
                    Address::where('addresses.id', $person_address->address_id)->delete();
                    $person_address->delete();
                }
                $user = User::where('person_id',$person->id)->first();
                if($user){
                    $user->delete();
                }
                /* sender phone delete */
                $person_phone = PersonPhone::where('person_id', $person->id)->first();
                Phone::where('phones.id', $person_phone->phones_id)->delete();
                $person_phone->delete();
                $person->delete();
            }
            $sender_beneficiary_details = SenderBeneficiary::where('sender_id',$sender_id)->get();
            if($sender_beneficiary_details->isNotEmpty()){
                foreach($sender_beneficiary_details as $key=>$value){

                    $a=SenderBeneficiary::where('beneficiary_id',$value->beneficiary_id)->first();
                    $b=Beneficiary::where('beneficiary_id',$value->beneficiary_id)->first();
                    if($a){
                        $a->delete();
                    }
                    if($b){
                        $b->delete();
                    }

                }

            }
            $identification = Identification::where('senders_id', $sender_id)->first();
            if ($identification) {
                $identification_document = IdentificationDocument::where('id', $identification->identification_documents_id)->first();
                if ($identification_document) {
                    Document::where('id', $identification_document->document_id)->delete();
                    $identification_document->delete();
                }
                $identification->delete();
            }
            $sender->delete();
        }
        Flash::success('Sender Deleted Successfully.');
        return redirect()->back();
    }
    public function emailCheck(){
        if (request()->ajax()) {
           $email = Sender::leftJoin('person','person.id','=','senders.person_id')->where('person.email',$this->request['email'])->first();
           if($email){
              return 1;
           }else{
               return 0;
           }
        }
    }

    public function getLineChartData(Request $request){
        if(request()->ajax()){
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

            $data['exchange_rate'] = $exchange_rate;

            echo json_encode($data);
        }
    }

    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {

        $dates = [];

        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {

            $dates[] = $date->format('Y-m-d');

        }

        return $dates;

    }

    public function approveSender($id){
        $recentClient = User::find($id);
        $recentSender = Sender::where('person_id', $recentClient->person_id)->first();
        $recentSender->sender_status_id = 2;
        $recentSender->save();
        if ($recentSender) {
            $recentClient->update(['active'=>1,'user_status_id'=>2]);
            $application = Application::where('id', $recentClient->application_id)->first();
            if(!$application){
                $application = Application::where('package_name','com.ideas.nepalpaisa')->first();
            }
            $clientName = Person::where('id', $recentClient->person_id)->first();
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

            try {
                Mail::send($view, $param, function ($message) use ($param) {
                    $message->to($param['to'], $param['toName'])
                        ->from($param['fromEmail'], $param['fromName'])
                        ->subject($param['subject']);
                });
            } catch (\Exception $e) {
                Log::error($e);
            }
            $notification = array(
                'message' => 'Client has been approved successfully.!',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Client could not be approved.!',
                'alert-type' => 'error',
            );
        }
        return redirect()->back()->with($notification);
    }
    public function sendEmailToSender($id){
        $sender = Sender::leftJoin('person','person.id','senders.person_id')->select('senders.id')->where('senders.id',$id)->first();
        return view('Sender::sendEmailModal', compact('sender','id'));

    }
    public function sendEmailTOSpecificSender(Request $request){
        $rule =  [
            'subject' => 'required',
            'message' => 'required',
        ];
        if($request->ajax()){
            $validator = Validator::make($request->all(), $rule);

            if ($validator->fails()){
                return $this->fail(['errors' => $validator->getMessageBag()->toArray()]);
            }
            $sender = Sender::leftJoin('person','person.id','senders.person_id')->where('senders.id',$request->sender_id)->select('senders.added_by','person.email')->first();
            $application =  Application::where('agent_id',$sender->added_by)->first();
            if($application){
                $admin_email = $application->email;
            } else {
                $admin_email = env('MAIL_FROM_ADDRESS');
            }

            $data = array(
                'to' => strtolower($sender->email),
                'subject' => $request->subject,
                'emailmessage'=>$request->message,
                'from'=>$admin_email
            );

            Mail::send('Email::email_template',$data,function($message) use($data){
                $message->to($data['to']);
                $message->subject($data['subject']);
                $message->from($data['from']);
            });
            $log = EmailLogs::create([
                'from'=>$admin_email,
                'receiver'=>$sender->email,
                'subject'=>$request->subject,
                'email_message'=>$request->message,
                'status'=>'sent'
            ]);
            return $this->success(['status'=>1,'response_message'=>'Email has been sent successfully!']);
        }
    }
    public function sendNotification($id){
        $applications = Application::select('agent_id','name')->pluck('name','agent_id');
        return view('Sender::sendNotificationModal', compact('id','applications'));
    }
    public function sendNotificationToSender(Request $request){
         $rules = [
            'title' => 'required',
            'url' => 'sometimes|nullable|url',
            'message' => 'required',
        ];
         if($request->ajax()){
             $validator = Validator::make($request->all(), $rules);

             if ($validator->fails()){
                 return $this->fail(['errors' => $validator->getMessageBag()->toArray()]);
             }
             $user_id = $this->current_user()->id;
             $pushnoticiation = SendNotification::create([
                 'title'=>$request->title,
                 'notification_message'=>$request->message,
                 'url'=>$request->url,
                 'user_id'=>$user_id
             ]);
             $application = Application::where('agent_id',$request->agent_id)->first();
             $pushnoticiation->application_name = $application->name;
             $pushnoticiation->save();
             $sender = Sender::leftJoin('person','person.id','senders.person_id')->
             leftJoin('users','users.person_id','person.id')->select('users.firebase_token')->where('senders.id',$request->id)->first();
             _sendPushNotification($request->title,$request->message,$request->url,'',$sender->firebase_token,$application->firebase_key);
             return $this->success(['status'=>1,'response_message'=>'Notification has been sent successfully!']);
         }
    }

    public function similarSenders(){
        if(!in_array(Auth::user()->level_id,[1,2,8])){
            abort(403,'Access Forbidden');
        }
        $senders = User::leftJoin('person','person.id','=','users.person_id')
            ->leftJoin('senders','senders.person_id','=','users.person_id')
            ->where('users.level_id',5)
            ->where('users.user_status_id',1)
            ->where('senders.sender_status_id',1)
            ->orderBy('users.id','desc')
            ->select('senders.id','senders.similar_ids','person.first_name','person.last_name','person.dob')->paginate(20);
        return view('Sender::similar_senders', compact('senders'));
    }

    public function compare($new_sender_id, $old_sender_id){

        $data = [];
        $sender1_user_id = $this->sender->userId($new_sender_id);
        $sender2_user_id = $this->sender->userId($old_sender_id);

        if($sender1_user_id && $sender2_user_id){
            $user_model = new User();
            $data['sender1'] = $user_model->getDetails($sender1_user_id);
            $data['sender2'] = $user_model->getDetails($sender2_user_id);

        }else{
            $data['error'] = 'Something went wrong. Please check for senders first.';
        }

        return view('Sender::compare',$data);
    }
}
