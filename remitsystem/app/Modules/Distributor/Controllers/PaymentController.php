<?php
namespace App\Modules\Distributor\Controllers;
use App\Http\Controllers\BaseController;
use App\Modules\Distributor\Models\Company;
use App\Modules\Distributor\Models\DistributorAccount;
use App\Modules\Distributor\Models\DistributorOffice;
use App\Modules\Distributor\Models\DistributorPayment;
use App\Modules\User\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class PaymentController extends BaseController
{
    protected $rules = [
        'amount' => 'required',
        'distributor_id' => 'required',
        'date' => 'required',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $rates = ExchangeRate::orderBy('id','desc')->first();
        return view("Distributor::payment/create",compact('rates'));
    }

    public function createPayment($id)
    {
        if (!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2)) {
            abort(403, 'Unauthorized action.');
        }
        $distributor = Company::where('id', $id)->first();
        $rates = ExchangeRate::orderBy('id','desc')->first();
        return view("Distributor::payment/create", compact('distributor','rates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $date = insert_dateformat($request['date']);
        $formData['date'] = $date;
        $formData['distributor_company_id'] = $request['distributor_id'];
        $formData['amount'] = $request['amount'];
        $formData['method'] = $request['payment_method'];
        $formData['description'] = $request['description'];
        $formData['added_by'] = current_user_id();
        $formData['dashboard'] = $request['dashboard'];
        $formData['cost_rate'] = $request['cost_rate'];
        $datapayment = DistributorPayment::create($formData);

        if (isset($datapayment)) {
            $distributor_account['distributor_transactions_id'] = null; //for now only
            $distributor_account['distributor_payments_id'] = $datapayment->id;
            $distributor_account['created_at'] = $date;

            DistributorAccount::create($distributor_account);
        }
        $notification = array(
            'message' => 'Payment has been added successfully!',
            'alert-type' => 'success',
        );


        if (isset($formData['dashboard'])) {
            return redirect()->route('user.dashboard')->with($notification);
        } else {
            return redirect()->back()->with($notification);
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
        //
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
        //
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

}