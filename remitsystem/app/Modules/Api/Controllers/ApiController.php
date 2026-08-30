<?php

namespace App\Modules\Api\Controllers;

use App\Modules\Beneficiary\Models\BankDetails;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Beneficiary\Models\BeneficiaryBankDetails;
use App\Modules\Sender\Models\Document;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionDocument;
use App\Modules\Transaction\Service\GetServiceChargeService;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiController extends Controller
{

    function __construct(
        private GetServiceChargeService $getServiceChargeService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getApiToken(){
        $token =  Str::random(32);
        return $token;
    }

    public function multipleReceiptUpload($receipt,$transaction_id,$user){

        if (\Illuminate\Support\Facades\Request::file('receipt')) {
                $image = \Illuminate\Support\Facades\Request::file('receipt');
            foreach ($receipt['receipt'] as $image) {
                $destinationPath = 'TransactionIdentification';
                $uniqueid = uniqid();
                $fileName = date('Y-m-d-H-i-s') . '-' . $uniqueid . '.' . $image->getClientOriginalExtension();
                $image->move($destinationPath, $fileName);
                TransactionDocument::create([
                    'file_name'=> $fileName,
                    'transaction_document_type_id' => 1,
                    'transactions_id' => $transaction_id,
                    'added_by' => $user->id,
                    'created_date' => Carbon::today()
                ]);
                Document::create([
                    'type'=>'',
                    'user_id' => $user->id,
                    'name' => $fileName,
                    'shelf_location'=>'',
                ]);
            }
        }else{
            TransactionDocument::create([
                'file_name'=> 'noReceiptUploaded.jpg',
                'transaction_document_type_id' => 1,
                'transactions_id' => $transaction_id,
                'added_by' => $user->id,
                'created_date' => Carbon::today()
            ]);
            Document::create([
                'type'=>'',
                'user_id' => $user->id,
                'name' => 'noReceiptUploaded.jpg',
                'shelf_location'=>'',
            ]);
        }
    }
    public function addBeneficiary(array $request,$user)
    {
        DB::beginTransaction();

        try {
            // Saving client profile
            $person = Person::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name']
            ]);

            $beneficiary = Beneficiary::create([
                'person_id' => $person->id,
                'added_by' => $user->id,
                'created_date' => get_today_date()
            ]);

            DB::table('sender_beneficiaries')->insert([
                'sender_id' => $request['sender_id'],
                'beneficiary_id' => $beneficiary->beneficiary_id,
            ]);

            // Add address
            $address = Address::create([
                'street' => $request['suburb'],
                'suburb' => $request['district'],
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
            $phone_id = $phone->add($request['phone_number']);
            PersonPhone::create([
                'phones_id' => $phone_id,
                'person_id' => $person->id,
                'current' => 1
            ]);

            // Add Bank Details
            $bank_details = BankDetails::create([
                'account_name' => $request['account_name'],
                'account_no' => $request['account_no'],
                'bsb' => $request['bsb'],
                'bank_name' => $request['bank_name'],
                'current' => 1
            ]);

            BeneficiaryBankDetails::create([
                'bank_details_id' => $bank_details->id,
                'beneficiaries_beneficiary_id' => $beneficiary->beneficiary_id,
                'current' => 1
            ]);

            DB::commit();
            return $beneficiary->beneficiary_id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }
    public function getTransaction()
    {
        $transaction = Transaction::leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
            ->leftJoin('person as a', 'a.id', '=', 'senders.person_id')
            ->leftJoin('beneficiaries', 'beneficiaries.beneficiary_id', '=', 'transactions.beneficiary_id')
            ->leftJoin('person as b', 'b.id', '=', 'beneficiaries.person_id')
            ->leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->leftJoin('person_phones', 'person_phones.person_id', '=', 'b.id')
            ->leftJoin('phones', 'phones.id', '=', 'person_phones.phones_id')
            ->leftJoin('users as u', 'u.id', '=', 'transactions.added_by')
            ->leftJoin('person as w', 'w.id', '=', 'u.person_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->leftJoin('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
            ->leftJoin('users as z', 'z.id', '=', 'distributor_transactions.assigned_to')
            ->leftJoin('person as x', 'x.id', '=', 'z.person_id')
            ->leftJoin('distributor_offices', 'distributor_offices.id', '=', 'distributor_transactions.distributor_office_id')
            ->leftJoin('companies', 'companies.id', '=', 'distributor_offices.companies_id')
            ->where('beneficiary_bank_details.current', '=', 1)
            ->orderBy('transactions.id', 'desc')
            ->select('transactions.id as transaction_id','transactions.pickup_district', DB::raw('DATE_FORMAT(transaction_details.transaction_date, "%d-%b-%Y") as transactionDate'), DB::raw('CONCAT_WS(" ", a.first_name, NULLIF(a.middle_name,""), a.last_name) AS sender_name'), DB::raw('CONCAT_WS(" ", b.first_name, NULLIF(b.middle_name,""), b.last_name) AS beneficiary_name'), 'transaction_details.total_to_pay as totalAmount', 'transaction_details.service_charge as serviceCharge', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transactions.added_by as added_by', DB::raw('CONCAT_WS(" ", w.first_name, NULLIF(w.middle_name,""), w.last_name) AS addedBy'), 'transactions.transaction_status_id as status_id', 'companies.company_name as companyName', 'distributor_transactions.distributor_office_id as distributor_office_id', 'distributor_transactions.assigned_to as assignedDistributorStaff', DB::raw('CONCAT_WS(" ", x.first_name, NULLIF(x.middle_name,""), x.last_name) as assignedDistributor'),
                'phones.number as beneficiary_phone', 'bank_details.account_name as account_name', 'bank_details.account_no as account_no', 'bank_details.bsb as bsb', 'bank_details.bank_name as bank_name', 'transactions.sender_id as sender_id', 'transactions.beneficiary_id as beneficiary_id','transaction_details.staff_notes','transaction_details.payment_type');
        return $transaction;
    }
    public function getTransactionList($id)
    {
        $transaction = Transaction::leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
            ->leftJoin('person as a', 'a.id', '=', 'senders.person_id')
            ->leftJoin('beneficiaries', 'beneficiaries.beneficiary_id', '=', 'transactions.beneficiary_id')
            ->leftJoin('person as b', 'b.id', '=', 'beneficiaries.person_id')
            ->leftJoin('beneficiary_bank_details', 'beneficiary_bank_details.beneficiaries_beneficiary_id', '=', 'beneficiaries.beneficiary_id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'beneficiary_bank_details.bank_details_id')
            ->leftJoin('person_phones', 'person_phones.person_id', '=', 'b.id')
            ->leftJoin('phones', 'phones.id', '=', 'person_phones.phones_id')
            ->leftJoin('users as u', 'u.id', '=', 'transactions.added_by')
            ->leftJoin('person as w', 'w.id', '=', 'u.person_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->leftJoin('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
            ->leftJoin('users as z', 'z.id', '=', 'distributor_transactions.assigned_to')
            ->leftJoin('person as x', 'x.id', '=', 'z.person_id')
            ->leftJoin('distributor_offices', 'distributor_offices.id', '=', 'distributor_transactions.distributor_office_id')
            ->leftJoin('companies', 'companies.id', '=', 'distributor_offices.companies_id')
            ->where('beneficiary_bank_details.current', '=', 1)
            ->where('transactions.id', '=', $id)
            ->orderBy('transactions.id', 'desc')
            ->select('transactions.id as transaction_id','transactions.pickup_district', DB::raw('DATE_FORMAT(transaction_details.transaction_date, "%d-%b-%Y") as transactionDate'), DB::raw('CONCAT_WS(" ", a.first_name, NULLIF(a.middle_name,""), a.last_name) AS sender_name'), DB::raw('CONCAT_WS(" ", b.first_name, NULLIF(b.middle_name,""), b.last_name) AS beneficiary_name'), 'transaction_details.total_to_pay as totalAmount', 'transaction_details.service_charge as serviceCharge', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transactions.added_by as added_by', DB::raw('CONCAT_WS(" ", w.first_name, NULLIF(w.middle_name,""), w.last_name) AS addedBy'), 'transactions.transaction_status_id as status_id', 'companies.company_name as companyName', 'distributor_transactions.distributor_office_id as distributor_office_id', 'distributor_transactions.assigned_to as assignedDistributorStaff', DB::raw('CONCAT_WS(" ", x.first_name, NULLIF(x.middle_name,""), x.last_name) as assignedDistributor'),
                'phones.number as beneficiary_phone', 'bank_details.account_name as account_name', 'bank_details.account_no as account_no', 'bank_details.bsb as bsb', 'bank_details.bank_name as bank_name', 'transactions.sender_id as sender_id', 'transactions.beneficiary_id as beneficiary_id','transaction_details.staff_notes','transaction_details.payment_type')->first();
        return $transaction;
    }

    public function getTransactionDetail($id)
    {
        $transaction = Transaction::leftJoin('senders', 'senders.id', '=', 'transactions.sender_id')
            ->leftJoin('person as a', 'a.id', '=', 'senders.person_id')
            ->leftJoin('users as u', 'u.id', '=', 'transactions.added_by')
            ->leftJoin('person as w', 'w.id', '=', 'u.person_id')
            ->leftJoin('transaction_details', 'transaction_details.transaction_details_id', '=', 'transactions.transaction_details_id')
            ->leftJoin('distributor_transactions', 'distributor_transactions.transaction_id', '=', 'transactions.id')
            ->leftJoin('users as z', 'z.id', '=', 'distributor_transactions.assigned_to')
            ->leftJoin('person as x', 'x.id', '=', 'z.person_id')
            ->leftJoin('distributor_offices', 'distributor_offices.id', '=', 'distributor_transactions.distributor_office_id')
            ->leftJoin('companies', 'companies.id', '=', 'distributor_offices.companies_id')
            ->leftJoin('transactions_beneficiaries','transactions_beneficiaries.transaction_id','transactions.id')
            ->where('transactions.id',$id)
            ->orderBy('transactions.id', 'desc')
            ->select('transactions.id as transaction_id', DB::raw('DATE_FORMAT(transaction_details.transaction_date, "%d-%b-%Y") as transactionDate'), DB::raw('CONCAT_WS(" ", a.first_name, NULLIF(a.middle_name,""), a.last_name) AS sender_name'), 'transactions_beneficiaries.name', 'transaction_details.total_to_pay as totalAmount', 'transaction_details.service_charge as serviceCharge', 'transaction_details.sending_amount as sendingAmount', 'transaction_details.payment_amount as paymentAmount', 'transaction_details.exchange_rate as exchangeRate', 'transactions.added_by as added_by', DB::raw('CONCAT_WS(" ", w.first_name, NULLIF(w.middle_name,""), w.last_name) AS addedBy'), 'transactions.transaction_status_id as status_id', 'companies.company_name as companyName', 'distributor_transactions.distributor_office_id as distributor_office_id', 'distributor_transactions.assigned_to as assignedDistributorStaff', DB::raw('CONCAT_WS(" ", x.first_name, NULLIF(x.middle_name,""), x.last_name) as assignedDistributor'),
                'transaction_details.staff_notes','transaction_details.payment_type','transactions_beneficiaries.phone_number as beneficiary_phone','transactions_beneficiaries.account_name as account_name', 'transactions_beneficiaries.bank_name as bank_name',
                'transactions.beneficiary_id as beneficiary_id','transaction_details.staff_notes','transaction_details.payment_type'
                ,'transactions_beneficiaries.name as beneficiary_name','transactions_beneficiaries.account_number as account_no','transactions_beneficiaries.pickup_district','transactions_beneficiaries.branch_name as bsb')->first();
        return $transaction;
    }

    public function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {

            $dates[] = $date->format('Y-m-d');

        }
        return $dates;
    }

    public function format_notification($notifications){
        $new_array = [];

        foreach($notifications as $key=>$notification){
            $new_array[$key]['id'] = $notification->id;
            if(isset($notification->data['identification'])){
                $new_array[$key]['title'] = $notification->data['message'];
            }elseif(isset($notification->data['transaction_edit'])){
                $new_array[$key]['title'] = $notification->data['message'];
            }else{
                if(isset( $notification->data['new'])){
                    $new_array[$key]['title'] = 'Transaction( '.format_id($notification->data['transaction_id'],'T') .') created.';
                }
                else{
                    $new_array[$key]['title'] = 'Transaction( '.format_id($notification->data['transaction_id'],'T') .' ) status changed to '.getStatusName($notification->data['status_id']).'.';
                }
            }
            $new_array[$key]['created_at'] = get_notification_format($notification->created_at);
        }
        return $new_array;
    }
    public function getExchangeRate(): JsonResponse
    {
        $serviceCharge = $this->getServiceChargeService->get();
        $exchange_rate = DB::table('exchange_rates')
            ->orderBy('id','desc')
            ->select('id', 'created_at', 'exchange_rate')
            ->first();
        return response()->json([
            'status' => 1,
            'rate' => $exchange_rate,
            'service_charge' => $serviceCharge,
        ]);
    }
}
