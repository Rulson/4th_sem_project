<?php

namespace App\Modules\Beneficiary\Controllers;

use App\Http\Controllers\BaseController;
use App\Modules\Beneficiary\Constants\StateTypeConstant;
use App\Modules\Beneficiary\Models\BankDetails;
use App\Modules\Beneficiary\Models\BankList;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Beneficiary\Models\BeneficiaryBankDetails;
use App\Modules\Sender\Models\Sender;
use App\Modules\Sender\Models\SenderBeneficiary;
use App\Modules\Transaction\Models\Transaction;

use App\Modules\User\Models\Address;
use App\Modules\User\Models\AusStates;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use App\Rules\MaxWords;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class BeneficiaryController extends BaseController
{

    /* Validation rules for sender create and edit */
    protected $rules = [
        'first_name' => 'required|min:2|max:55',
        'last_name' => 'required|min:2|max:55',
        'number' => ['required', 'regex:/^(?:\+977[-\s]?)?[9][6-9]\d{8}$|^01[-]?[0-9]{7}$/'],
        'street' => 'required',
        'suburb' => 'required',
        'state' => 'required',
        'postcode' => 'required',
        'country_list_id' => 'required',
        /*'account_name' => 'required',
        'account_no' => 'required',
        'bsb' => 'required',
        'bank_name' => 'required',*/
    ];
    protected $messages = [
        'number.regex' => 'The phone number format is invalid. It should be a valid nepali number in the format 9XXXXXXXXX or 01-XXXXXXX',
    ];


    protected $beneficiary;

    function __construct(Beneficiary $beneficiary, Request $request, Sender $sender)
    {
        $this->beneficiary = $beneficiary;
        $this->sender = $sender;
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
        if (!in_array(Auth::user()->level_id ,[1,2,3,5,6,8])) {
            abort(403, 'Unauthorized action.');
        }
        /* $beneficiary = Beneficiary::leftJoin('person as v', 'v.id', '=', 'beneficiaries.person_id')
               ->leftJoin('person_phones','person_phones.person_id','=','v.id')
               ->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
               ->leftJoin('users','users.id','=','beneficiaries.added_by')
               ->leftJoin('person as w', 'w.id', '=', 'users.person_id')
             ->leftJoin('sender_beneficiaries','sender_beneficiaries.beneficiary_id','=','beneficiaries.beneficiary_id')
             ->leftJoin('senders','senders.id','=','sender_beneficiaries.sender_id')
             ->leftJoin('person as x','x.id','=','senders.person_id')
               ->orderBy('beneficiaries.beneficiary_id', 'desc')
               ->select('beneficiaries.beneficiary_id','sender_beneficiaries.sender_id',DB::raw('DATE_FORMAT(beneficiaries.created_date, "%d-%b-%Y") as datejoined'), 'v.first_name','v.last_name','phones.number',DB::raw('CONCAT_WS(" ", x.first_name, NULLIF(x.middle_name,""), x.last_name) AS sender_name'), DB::raw('CONCAT_WS(" ", w.first_name, NULLIF(w.middle_name,""), w.last_name) AS addedBy'),'beneficiaries.added_by as added_by')->toSql();
        */
        /* $data['beneficiaries'] = $this->beneficiary->getAll();*/
        //  return view("Beneficiary::index", $data);
        return view("Beneficiary::".$this->extra_folder."index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(in_array(Auth::user()->level_id,[4,6])){
            abort(403,'Unauthorized action.');
        }
        $data['sender_id'] = User::join('person','users.person_id','person.id')->join('senders','person.id','senders.person_id')
            ->where('users.id', current_user_id())->select('senders.id')->first();
        $data['np_state'] = AusStates::select('name')->where('type','np_state')->pluck('name','name');
        $data['district'] = AusStates::select('name')->where('type','district')->pluck('name','name');
        return view('Beneficiary::add', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* Additional validations for creating sender */
        /*$this->rules['email'] = 'email|min:5|max:55|unique:senders';
        $this->rules['password'] = 'required|min:6';
        $this->rules['confirm_password'] = 'required|same:password|min:6';

        $messages = [
            'email.unique' => 'Sender with the same email address already exists.'
        ];*/

        //$this->validate($request, $this->rules, $messages);
        $rules = $this->rules;
        $rules['bsb'] = ['required',new MaxWords(2)];

        if ($request->ajax()) {
            //return $this->success(['beneficiary_id' => 1, 'number' => 'abc', 'address' => '123', 'fullname' => 'New Name']);
            $validator = \Validator::make($request->all(), $rules, $this->messages);

            if ($validator->fails())
                return $this->fail(['errors' => $validator->getMessageBag()->toArray()]);
            // if validates
            $beneficiary_id = $this->beneficiary->add($request->all());
            return $this->success(['beneficiary_id' => $beneficiary_id, 'number' => $request->get('number'), 'address' => $request->get('street'), 'sender_id' => $request->get('sender_id'), 'fullname' => $request->get('first_name') . ' ' . $request->get('last_name')]);
        } else {
            $this->validate($request, $this->rules, $this->messages);
            // if validates
            $created = $this->beneficiary->add($request->all());
            if ($created) {
                Flash::success('Beneficiary has been created successfully.');
            }
            return redirect()->back();
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
        $addedByCheck = Beneficiary::where('beneficiary_id', $id)->first();
        if (!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 8 || (Auth::user()->level_id == 3 && $addedByCheck->added_by == Auth::user()->id) || (Auth::user()->level_id == 5 && $addedByCheck->added_by == Auth::user()->id) || Auth::user()->level_id == 6)) {
            abort(403, 'Unauthorized action.');
        }

        $beneficiary = $this->beneficiary->getBeneficiaryDetailById($id);
        $transaction1 = Transaction::leftJoin('beneficiaries', 'beneficiaries.beneficiary_id', '=', 'transactions.beneficiary_id')
            ->leftJoin('person', 'person.id', '=', 'beneficiaries.person_id')
            ->leftJoin('status', 'status.id', '=', 'transactions.transaction_status_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->select('transactions.id as transaction_id', 'transactions.beneficiary_id', 'transaction_details.total_to_pay as totalAmount', 'status.name as status', 'person.first_name', 'person.last_name', 'person.middle_name', 'transactions.id', 'transactions.added_by as addedBy', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.transaction_date as transactionDate', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transaction_details.service_charge as serviceCharge', DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS sender_full_name'))
            ->where('beneficiaries.beneficiary_id', $id)
            ->orderBy('transactions.id', 'desc');
        $transaction = $transaction1->get();

        $transaction_count = $transaction1->count();
        $sumSendingAmount = $transaction1->sum('transaction_details.sending_amount');
        $sumPaymentAmount = $transaction1->sum('transaction_details.payment_amount');

        $allAccounts = Beneficiary::leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->where('beneficiaries.beneficiary_id', $id)
            ->select(['beneficiary_bank_details.id as bankDetailId', 'bank_details.account_name as accountName', 'bank_details.account_no as accountNo', 'bank_details.bsb', 'bank_details.bank_name as bankName', 'beneficiary_bank_details.current'])
            ->orderBy('bank_details.id')
            ->get();
        return view('Beneficiary::'.$this->extra_folder.'show', compact('beneficiary', 'transaction', 'transaction_count', 'sumSendingAmount', 'sumPaymentAmount', 'allAccounts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(in_array(Auth::user()->level_id, [4,6])) {
            abort(403, 'Unauthorized action.');
        }
        $beneficiary = $this->beneficiary->getBeneficiaryDetailById($id);

        $data['sender_id'] = User::join('person','users.person_id','person.id')->join('senders','person.id','senders.person_id')
            ->where('users.id', current_user_id())->select('senders.id')->first();
        $data['np_state'] = AusStates::select('name')->where('type',StateTypeConstant::NEW_NP_STATES)->pluck('name','name');
        $data['district'] = AusStates::select('name')->where('type','district')->orderBy('name')->pluck('name','name');
        return view('Beneficiary::'.$this->extra_folder.'edit', compact('data','beneficiary'));
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
        $rules = $this->rules;
        $rules['bsb'] = ['required',new MaxWords(2)];
        $this->validate($request, $rules,$this->messages);
        if ($request->ajax()) {

            $beneficiary_id = $this->beneficiary->edit($request->all(), $id);
            return $this->success(['beneficiary_id' => $beneficiary_id, 'number' => $request->get('number'), 'address' => $request->get('street'), 'fullname' => $request->get('first_name') . ' ' . $request->get('last_name')]);
        } else {
            $updated = $this->beneficiary->edit($request->all(), $id);
            if ($updated) {
                Flash::success('Beneficiary has been updated successfully.');
            }
            if (isset($request->modal)) {
                return redirect()->back();
            } else {
                return redirect()->route('beneficiaries.index');
            }

        }
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

    public function addAdditionalAccount(Request $request, $beneficiary_id)
    {
        $bank_details = BankDetails::create([
            'account_name' => $request['account_name'],
            'account_no' => $request['account_no'],
            'bsb' => $request['bsb'],
            'bank_name' => $request['bank_name'],
            'current' => 1
        ]);

        BeneficiaryBankDetails::create([
            'bank_details_id' => $bank_details->id,
            'beneficiaries_beneficiary_id' => $beneficiary_id,
            'current' => 0
        ]);
        Flash::success('Additional Account has been successfully added.');
        return redirect()->back();

    }

    public function changeAccount($beneficiary_id)
    {
        $allAccounts = Beneficiary::leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->where('beneficiaries.beneficiary_id', $beneficiary_id)
            ->select(['beneficiary_bank_details.id as bankDetailId', 'bank_details.account_name as accountName', 'bank_details.account_no as accountNo', 'bank_details.bsb', 'bank_details.bank_name as bankName', 'beneficiary_bank_details.current'])
            ->orderBy('bank_details.id')
            ->get();
        $activeAccount = Beneficiary::leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->where('beneficiaries.beneficiary_id', $beneficiary_id)
            ->select(['beneficiary_bank_details.id as bankDetailId', 'bank_details.account_name as accountName', 'bank_details.account_no as accountNo', 'bank_details.bsb', 'bank_details.bank_name as bankName', 'beneficiary_bank_details.current'])
            ->where('beneficiary_bank_details.current', 1)
            ->first();

        return view("Beneficiary::updateAccountModal", compact('beneficiary_id', 'allAccounts', 'activeAccount'));
    }

    public function storeChangeAccount(Request $request, $beneficiary_id)
    {
        Beneficiary::leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->where('beneficiaries.beneficiary_id', $beneficiary_id)
            ->where('beneficiary_bank_details.current', 1)->update([
                'beneficiary_bank_details.current' => '0'
            ]);
        Beneficiary::leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->where('beneficiaries.beneficiary_id', $beneficiary_id)
            ->where('beneficiary_bank_details.id', $request['beneficiary_bank_details_id'])->update([
                'beneficiary_bank_details.current' => '1'
            ]);

        if ($request->ajax()) {
            return $this->success();

        } else {
            Flash::success('Account Updated Successfully');
            return redirect()->back();
        }
    }

    public function viewSearch()
    {
        //  $data['beneficiaries'] = $this->beneficiary->getAll();
        // $data['senders'] = $this->sender->getAll();
        if (in_array(Auth::user()->level_id,[1,2,6,8])) {
            $data['added_by'] = User::join('person', 'person.id', '=', 'users.person_id')->select('users.id', 'person.first_name', 'person.last_name')->get();
        }
        if (Auth::user()->level_id == 3 || Auth::user()->level_id == 5) {
            $data['added_by'] = User::join('person', 'person.id', '=', 'users.person_id')->select('users.id', 'person.first_name', 'person.last_name')->where('users.id', Auth::user()->id)->get();
        }
        $data['search_attributes'] = array();
        if ($this->request->isMethod('post')) {
            $data['beneficiaries_data'] = $this->searchResults($this->request->all());
            $data['search_attributes'] = $this->request->all();
        }

        return view("Beneficiary::search", $data);
    }

    public function searchResults($request)
    {
        $beneficiary_query = Beneficiary::join('person', 'person.id', '=', 'beneficiaries.person_id')
            ->leftJoin('person_phones', function ($q) {
                $q->on('person_phones.person_id', '=', 'person.id');
                $q->where('person_phones.current', '=', 1);
            })->leftJoin('phones', 'person_phones.phones_id', '=', 'phones.id')
            ->leftJoin('sender_beneficiaries', 'sender_beneficiaries.beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('senders', 'senders.id', '=', 'sender_beneficiaries.sender_id')
            ->leftJoin('person as x', 'x.id', '=', 'senders.person_id')
            ->select(['beneficiaries.beneficiary_id', 'beneficiaries.created_date as dateAdded', 'beneficiaries.added_by', 'x.first_name as sender_first_name', 'x.last_name as sender_last_name', 'person.first_name', 'person.last_name', 'person.email', 'phones.number', DB::raw('CONCAT_WS(" ", person.first_Name, NULLIF(person.middle_name,""), person.last_Name) AS full_name')]);
        /*  if (isset($request['beneficiary_name'])) {
              $beneficiary_query = $beneficiary_query->whereIn('beneficiaries.beneficiary_id', $request['beneficiary_name']);
          }*/
        if (isset($request['beneficiary_first_name'])) {
            $beneficiary_query = $beneficiary_query->where('person.first_name', $request['beneficiary_first_name']);
        }
        if (isset($request['beneficiary_last_name'])) {
            if (isset($request['beneficiary_first_name']) && isset($request['beneficiary_last_name'])) {
                $beneficiary_query = $beneficiary_query->where('person.first_name', $request['beneficiary_first_name'])->where('person.last_name', $request['beneficiary_last_name']);
            } else {
                $beneficiary_query = $beneficiary_query->where('person.last_name', $request['beneficiary_last_name']);
            }
        }
        if (isset($request['sender_first_name'])) {
            $beneficiary_query = $beneficiary_query->where('x.first_name', $request['sender_first_name']);
        }
        if (isset($request['sender_last_name'])) {
            $beneficiary_query = $beneficiary_query->where('x.last_name', $request['sender_last_name']);
        }
        if (isset($request['phone_number'])) {
            $beneficiary_query = $beneficiary_query->where('phones.number', $request['phone_number']);
        }
        if (isset($request['added_by'])) {
            if ($request['added_by'] == 0) {
            } else {
                $beneficiary_query = $beneficiary_query->where('beneficiaries.added_by', $request['added_by']);
            }
        }
        /* if (isset($request['date_joined'])) {
             $date = Carbon::parse($request['date_joined'])->formatLocalized('%Y-%m-%d');
             $beneficiary_query = $beneficiary_query->where('beneficiaries.created_date', $date); */

        if ($request['date_joined'] != '') {
            $dates = explode(' - ', $request['date_joined']);
            $dates[0] = Carbon::parse($dates[0])->formatLocalized('%Y-%m-%d');
            $dates[1] = Carbon::parse($dates[1])->formatLocalized('%Y-%m-%d');
            $beneficiary_query = $beneficiary_query->whereBetween(DB::raw("(STR_TO_DATE(beneficiaries.created_date,'%Y-%m-%d'))"), array((string)$dates[0], (string)$dates[1]));
        }
        /* if (isset($request['sender_name'])) {
             $beneficiary_query = $beneficiary_query->leftJoin('sender_beneficiaries','sender_beneficiaries.beneficiary_id','=','beneficiaries.beneficiary_id')
                 ->whereIn('sender_beneficiaries.sender_id',$request['sender_name']);
         }*/
        if (Auth::user()->level_id == 3) {
            $beneficiary_query = $beneficiary_query->where('beneficiaries.added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            $beneficiary_query = $beneficiary_query->where('beneficiaries.added_by', Auth::user()->id);

        }
        return $beneficiary_query->orderBy('beneficiaries.beneficiary_id', 'desc')->get();
    }

    public function getBeneficiariesDataByAjax()
    {
        $result = DB::table('beneficiary_views2');

        if (Auth::user()->level_id == 3) {
            $result = $result->where('added_by', Auth::user()->id);
        }
        if (Auth::user()->level_id == 5) {
            // $result = $result->where('added_by', Auth::user()->id);

            $sender = Sender::leftJoin('person', 'person.id', '=', 'senders.person_id')->leftJoin('users', 'users.person_id', '=', 'senders.person_id')->where('users.id', Auth::user()->id)->select('senders.id')->first();
            $result = $result->where('sender_id', $sender->id);
        }
        return DataTables::of($result)
            ->addColumn('action', function ($data) {
                if (in_array(Auth::user()->level_id, [1,2,3,5])) {
                    $edit_btn = '<a href="' . route('beneficiary.edit', [$data->beneficiary_id]) . '" data-toggle="tooltip" data-placement="bottom" title="Edit" class="btn btn-sm btn-primary"><i
                                            class="fa fa-edit"></i></a>';
                } else {
                    $edit_btn = '';
                }
                if (Auth::user()->level_id == 1 || Auth::user()->level_id == 2) {
                    $beneficiary_transaction_check = Transaction::where('beneficiary_id', $data->beneficiary_id)->first();

                    if ($beneficiary_transaction_check) {
                        $delete_btn = '';
                    } else {

                        $delete_btn = '<a href="' . route('beneficiary.delete', [$data->beneficiary_id]) . '" data-toggle="tooltip" data-placement="bottom" title="Delete" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure ,you want to delete this beneficiary?\')"><i
                                            class="fa fa-trash"></i></a>';
                    }

                } else {
                    $delete_btn = '';
                }
                return '<a href="' . route('beneficiary.show', [$data->beneficiary_id]) . '" data-toggle="tooltip" data-placement="bottom" title="View"
                                   class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>' . $edit_btn . $delete_btn;
            })
            ->editColumn('beneficiary_id', function ($data) {
                return format_id($data->beneficiary_id, "B");
            })->make(true);


    }

    public function deleteBeneficiary($beneficiary_id)
    {
        $x = $this->beneficiary->deleteBeneficiary($beneficiary_id);
        if ($x) {
            Flash::success('Beneficiary Deleted Successfully.');
        } else {
            Flash::info('Beneficiary could not be deleted');
        }

        return redirect()->back();
    }
    public function banks(){
        $banks = BankList::all();
        return view("Beneficiary::banklist",compact('banks'));
    }
    public function createBank(){
        return view("Beneficiary::addBank");
    }
    public function storeBank(Request $request){
            $this->validate($request,[
                'bank_name'=>'required'
            ]);
            BankList::create(['name'=>$request->bank_name
            ]);
            $notification = array(
                'message' => 'Bank has been added successfully!',
                'alert-type' => 'success'
            );
            return redirect()->route('banks.index')->with($notification);
    }
    public function updateBank(Request $request,$id){
        $this->validate($request,[
            'bank_name'=>'required'
        ]);
        BankList::where('id',$id)->update([
            'name'=>$request->bank_name
        ]);
        $notification = array(
            'message' => 'Bank has been updated successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('banks.index')->with($notification);
    }
    public function editBank($id){
            $bank_details = BankList::where('id',$id)->first();
            return view("Beneficiary::editBank",compact('bank_details'));
    }

    public function getState($id){
        $state = AusStates::where('name',$id)->first();
        $data['selected_state'] = AusStates::select('name')->where('id',$state->parent_id)->first();
        $data['np_state'] = AusStates::select('name')->where('type','np_state')->pluck('name','name');
        return response($data);
    }

    public function changeBankStatus($bank_id,$status_id){

        $bank = BankList::findOrFail($bank_id);
        $bank->active = $status_id;
        $bank->save();
        $notification = array(
            'message' => 'Bank Status been updated successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('banks.index')->with($notification);
    }
}
