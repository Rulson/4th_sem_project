<?php

namespace App\Modules\Distributor\Controllers;

use App\Modules\Distributor\Models\Company;
use App\Modules\Distributor\Models\CompanyBankDetails;
use App\Modules\Distributor\Models\Application;
use App\Modules\Distributor\Models\DistributorOffice;
use App\Modules\Distributor\Models\DistributorAccount;
use App\Modules\Distributor\Models\DistributorPayment;
use App\Modules\Distributor\Models\DistributorTransaction;
use App\Modules\Distributor\Models\DistributorUser;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\BankDetails;
use App\Modules\User\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;

class DistributorController extends BaseController
{
    protected $rules = [
        'first_name' => 'required|min:2|max:55',
        'last_name' => 'required|min:2|max:55',
        'number' => 'required'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(DistributorOffice $distributor,DistributorTransaction $distributorTransaction)
    {
        $this->distributor = $distributor;
        $this->distributorTransaction = $distributorTransaction;
        parent::__construct();
    }

    public function index()
    {
        if(!in_array(Auth::user()->level_id ,[1,2,6])) {
            abort(403, 'Unauthorized action.');
        }
        $data = Company::orderBy('id','desc')->get();
        return view("Distributor::index", compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )) {
            abort(403, 'Unauthorized action.');
        }
        return view("Distributor::create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'company_name' => 'required',
            'phone_number' => 'required',
            'email' => 'required | email',
            'street' => 'required',
            'state' => 'required',
            'suburb' => 'required',
            'post_code' => 'required',
        ]);


        DB::beginTransaction();
        try {

            $formData['company_name'] = $request['company_name'];
            $formData['phone_no'] = $request['phone_number'];
            $formData['email'] = strtolower($request['email']);

            $addressData['street'] = $request['street'];
            $addressData['suburb'] = $request['suburb'];
            $addressData['postcode'] = $request['post_code'];
            $addressData['state'] = $request['state'];
            $addressData['country_list_id'] = $request['country'];
            $address = Address::create($addressData);

            $formData['addresses_id'] = $address->id;

            $company = Company::create($formData);

            $distributorOffice['companies_id'] = $company->id;
            $distributorOffice['active'] = 1;
            DistributorOffice::create($distributorOffice);

            $bankDetailsData['account_name'] = $request['account_name'];
            $bankDetailsData['account_no'] = $request['account_no'];
            $bankDetailsData['bsb'] = $request['bsb'];
            $bankDetailsData['bank_name'] = $request['bank_name'];
            if (in_array(null, $bankDetailsData) == false) {
                $bankDetails = BankDetails::create($bankDetailsData);
                $companyBankDetail['bank_details_id'] = $bankDetails->id;
                $companyBankDetail['companies_id'] = $company->id;
                CompanyBankDetails::create($companyBankDetail);
            }
            $notification = array(
                'message' => 'Distributor has been created successfully!',
                'alert-type' => 'success'
            );
        } catch (\Exception $e) {
            DB::rollback();
            $message = 'Sorry distributor could not be saved.';

            if (env('APP_DEBUG'))
                $message = $e->getMessage() . 'Line #' . $e->getLine();
            $notification = array(
                'message' => 'Sorry distributor could not be saved!',
                'alert-type' => 'error'
            );

        }
        DB::commit();
        return redirect()->route('distributors.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!in_array(Auth::user()->level_id , [1,2,6])) {
            abort(403, 'Unauthorized action.');
        }
        $data = Company::join('addresses', 'addresses.id', '=', 'companies.addresses_id')
            ->leftJoin('country_list','country_list.id','=','addresses.country_list_id')
            ->where('companies.id', $id)
            ->select('companies.id as id', 'companies.company_name as companyName', 'companies.email as email', 'companies.phone_no as phoneNumber',
                'addresses.street as street', 'addresses.suburb as suburb','addresses.state as state', 'addresses.postcode as postCode', 'country_list.name as country')
            ->first();
        $distributorOffice = DistributorOffice::where('companies_id',$id)->first();
        $distributorPaymentSummary = $this->distributorTransaction->paymentSummaryById($id);

        $distributor_transactions=$this->distributorTransaction->getDistributorTransaction($id);
        $distributor_transactions= $distributor_transactions->get();
        $distributor_payments = DistributorPayment::where('distributor_company_id',$id)->orderBy('id','desc')->get();
        return view("Distributor::show",compact('distributor_payments','data','distributorPaymentSummary','distributor_transactions'));
    }

    public function editPayment($payment_id){
        $payments = DistributorPayment::where('id',$payment_id)->first();
        return view("Distributor::payment/edit",compact('payments'));
    }

    public function updatePayment(Request $request,$payment_id){
        $payment = DistributorPayment::find($payment_id);
        $payment->date = $request->date;
        $payment->amount = $request->amount;
        $payment->description = $request->description;
        $payment->method = $request->payment_method;
        $payment->cost_rate = $request->cost_rate;
        $payment->save();
        $notification = array(
            'message' => 'Payment Details has been updated successfully!',
            'alert-type' => 'success',
        );
        return redirect()->route('distributor.show',$payment->distributor_company_id)->with($notification);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )) {
            abort(403, 'Unauthorized action.');
        }
        $data = Company::join('addresses', 'addresses.id', '=', 'companies.addresses_id')
            ->leftJoin('company_bank_details', 'company_bank_details.companies_id', '=', 'companies.id')
            ->leftJoin('bank_details', 'bank_details.id', '=', 'company_bank_details.bank_details_id')
            ->where('companies.id', $id)
            ->select('companies.id as id', 'companies.company_name as companyName', 'companies.email as email', 'companies.phone_no as phoneNumber',
                'addresses.street as street', 'addresses.suburb as suburb','addresses.state as state', 'addresses.postcode as postCode', 'addresses.country_list_id as countryId',
                'bank_details.account_name as accountName', 'bank_details.account_no as accountNo', 'bank_details.bsb as bsb', 'bank_details.bank_name as bankName')
            ->first();

        return view("Distributor::edit", compact('data'));
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
        $company = Company::find($id);
        $company['company_name'] = $request['company_name'];
        $company['phone_no'] = $request['phone_number'];
        $company['email'] = strtolower($request['email']);
        $company->save();

        $address = Address::find($company->addresses_id);
        $address['street'] = $request['street'];
        $address['postcode'] = $request['post_code'];
        $address['suburb'] = $request['suburb'];
        $address['state'] = $request['state'];
        $address['country_list_id'] = $request['country'];
        $address->save();

        $companyBankDetail = CompanyBankDetails::where('companies_id', $company->id)->first();
        if (isset( $companyBankDetail)) {
            $bankDetail = BankDetails::find($companyBankDetail->bank_details_id);
            $bankDetail['account_name'] = $request['account_name'];
            $bankDetail['account_no'] = $request['account_no'];
            $bankDetail['bsb'] = $request['bsb'];
            $bankDetail['bank_name'] = $request['bank_name'];
            $bankDetail->save();
        }
        $notification = array(
            'message' => 'Distributor has been updated successfully!',
            'alert-type' => 'success'
        );
        return redirect()->route('distributors.index')->with($notification);

    }

    public function balanceSummary(){
        $distributor = DistributorUser::where('user_id',Auth::user()->id)->first();
        $companyId = DistributorOffice::where('id',$distributor->distributor_office_id)->first();
        $distributorPaymentSummary = $this->distributorTransaction->totalSum($companyId->companies_id);
        return view("Distributor::payment/summary",compact('distributorPaymentSummary'));
    }
}
