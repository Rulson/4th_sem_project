<?php
namespace App\Modules\Agent\Controllers;


use App\Http\Controllers\BaseController;
use App\Modules\Agent\Models\Agent;
use App\Modules\Agent\Models\AgentAccount;
use App\Modules\Agent\Models\AgentPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;


class PaymentController extends BaseController
{
    protected $rules = [
        'amount' => 'required',
        'agent_id' => 'required',
        'date' => 'required',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {}

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
        return view("Agent::payment/create");
    }
    public function createPayment($agent_id){
        if(!(Auth::user()->level_id == 1)) {
            abort(403, 'Unauthorized action.');
        }
        $agent = Agent::where('id',$agent_id)->first();
        return view("Agent::payment/create",compact('agent'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = insert_dateformat($request['date']);
        $this->validate($request,$this->rules);
        $formData['date'] = $date;
        $formData['agent_id'] = $request['agent_id'];
        $formData['amount'] = $request['amount'];
        $formData['method'] = $request['payment_method'];
        $formData['description'] = $request['description'];
        $formData['added_by'] = current_user_id();
        $formData['created_at'] = $date;
        $formData['dashboard'] = $request['dashboard'];

        $datapayment = AgentPayment::create($formData);

        if (isset($datapayment)) {
            $agent_account['agent_transactions_id'] = null; //for now only
            $agent_account['agent_payments_id'] = $datapayment->id;
            $agent_account['created_at'] = get_today_date();

            AgentAccount::create($agent_account);
        }
        $notification = array(
            'message' => 'Payment has been added successfully!',
            'alert-type' => 'success',
        );
        if(isset($formData['dashboard'])){
            return redirect()->route('agent.show',$request['agent_id'])->with($notification);
        }else{
            return redirect()->back()->with($notification);
        }
    }

}