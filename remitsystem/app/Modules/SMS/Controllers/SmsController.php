<?php

namespace App\Modules\SMS\Controllers;

use App\Modules\Agent\Models\Agent;
use App\Modules\Sender\Models\Sender;
use App\Modules\Settings\Models\Settings;
use App\Modules\SMS\Models\sms;
use App\Modules\SMS\Models\SMSCron;
use App\Modules\SMS\Models\SmsPayment;
use App\Modules\User\Models\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Laracasts\Flash\Flash;

class SmsController extends BaseController
{

    function __construct(SMS $sms, Request $request, Agent $agent)
    {
        $this->sms = $sms;
        $this->request = $request;
        $this->agent = $agent;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Settings::first();
        return view("Settings::edit", compact('data'));
    }

    public function composeSMS()
    {
        if(!(Auth::user()->level_id == 1)) {
            abort(403, 'Unauthorized action.');
        }
        $settings = Settings::first();
        $number = $settings->phone_number;
        $data['agents'] = Agent::join('person', 'person.id', '=', 'agents.person_id')
            ->join('person_phones', 'person_phones.person_id', '=', 'person.id')
            ->join('phones', 'phones.id', '=', 'person_phones.phones_id')
            ->select([DB::raw('CONCAT(person.first_name, " ", person.last_name) as fullName'), 'person.id as personId', 'phones.number as phoneNumber'])
            ->get();
        $data['senders'] = Sender::join('person', 'person.id', '=', 'senders.person_id')
            ->join('person_phones', 'person_phones.person_id', '=', 'person.id')
            ->join('phones', 'phones.id', '=', 'person_phones.phones_id')
            ->select([DB::raw('CONCAT(person.first_name, " ", person.last_name) as fullName'), 'person.id as personId', 'phones.number as phoneNumber'])
            ->get();

        return view("SMS::compose", compact('number', 'data'));
    }
    public function bulkCompose(){
        if(!(Auth::user()->level_id == 1)) {
            abort(403, 'Unauthorized action.');
        }
        $settings = Settings::first();
        $number = $settings->phone_number;
        return view("SMS::bulk-compose",compact('number'));


    }
    public function bulkSmsSend(Request $request){

        $this->validate($request, [
            'source' => 'required',
            'sms' => 'required',
            'receiver'=>'required'

        ]);
              if ($request->receiver == 'Agents') {
            $agents = $this->agent->getAgents();
            foreach ($agents as $agent) {
                SMSCron::create([
                    'source' => $request->source,
                    'destination' => $agent->number,
                    'message' => $request->sms,
                    'status' => 0,
                    'group' => 'agent'
                ]);
            }
        }
        if ($request->receiver == 'Senders') {
            $sender_model = new Sender();
            $senders = $sender_model->getAll();
            foreach ($senders as $sender) {
                SMSCron::create([
                    'source' => $request->source,
                    'destination' => $sender->number,
                    'message' => $request->sms,
                    'status' => 0,
                    'group' => 'sender'
                ]);
            }
        }
        $notification = array(
            'message' => 'Bulk Email has been croned successfully!',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }

    function sendSMS($content)
    {
        $ch = curl_init('https://api.smsbroadcast.com.au/api.php');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view("Settings::create");
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
            'source' => 'required',
            'sms' => 'required',
            'receiver'=>'required'

        ]);


        if ($request->receiver == "Senders") {
            $phone = Person::join('person_phones', 'person_phones.person_id', '=', 'person.id')
                ->join('phones', 'phones.id', '=', 'person_phones.phones_id')
                ->where('person.id', $request->sender_person_id)
                ->first();

            $person_id = $request->sender_person_id;

        }
        if ($request->receiver == "Agents") {
            $phone = Person::join('person_phones', 'person_phones.person_id', '=', 'person.id')
                ->join('phones', 'phones.id', '=', 'person_phones.phones_id')
                ->where('person.id', $request->agent_person_id)
                ->first();
            $person_id = $request->agent_person_id;

        }

        $request = $request->all();
        $data = strlen($request['sms']);
        if ($data <= 150) {
            $request['credit_value'] = 1;
        } else {
            $request['credit_value'] = 2;
        }
        $request['destination'] = $phone->number;
        $request['status'] = 1;
        $request['send_by'] = Auth::user()->id;

        $destination = $phone->number;
        $sms = $request['sms'];
        $username = '';
        $password = '';
        $source = $request['source'];

        $content = 'username=' . rawurlencode($username) .
            '&password=' . rawurlencode($password) .
            '&to=' . rawurlencode($destination) .
            '&from=' . rawurlencode($source) .
            '&message=' . rawurlencode($sms);


        $setting_detail = Settings::first();
        $balance = $setting_detail->sms_credit;

            $smsbroadcast_response = $this->sendSMS($content);
            if (strpos($smsbroadcast_response, 'ERROR') !== false) {
                $notification = array(
                    'message' => $smsbroadcast_response,
                    'alert-type' => 'error'
                );
            } else {
                sms::create([
                    'receiver_id' => $person_id,
                    'message' => $sms,
                    'credit_value' => $request['credit_value'],
                    'send_from' => Auth::user()->id,
                ]);

                $balance1 = $setting_detail->sms_credit;

                $total_credit = $balance1;

                $balance = $total_credit - $request['credit_value'];

                $setting3 = Settings::first();
                $setting3->sms_credit = $balance;
                $setting3->save();

                $notification = array(
                    'message' => 'Message sent successfully',
                    'alert-type' => 'success'
                );
            }
        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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

    public function getSMSAmount(Request $request)
    {
        $resp = false;
        $post = $request->all();
        if (isset($post['sms_credit'])) {
            $setting = new Settings();
            $fee = $setting->getSMSFee();
            if ($fee) {
                $resp = ($fee * $post['sms_credit']) / 100;

            }
        }
        echo $resp;
    }

    public function viewPurchase()
    {
        if(!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )) {
            abort(403, 'Unauthorized action.');
        }
        $setting = Settings::first();
        $balance = $setting->sms_credit;
        return view("SMS::purchase", compact('balance'));
    }

    public function postPurchase()
    {

        $this->sms->sms_purchase($this->request->all());
        Flash::success('SMS credit has been purchased successfully.');
        return redirect()->route('sms.credit.purchase.show');
    }

    public function smslog()
    {
        if(!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )) {
            abort(403, 'Unauthorized action.');
        }
        $sms = sms::orderBy('id', 'desc')->get();
        return view("SMS::smslog", compact('sms'));
    }

    public function smsPayment()
    {
        if(!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )) {
            abort(403, 'Unauthorized action.');
        }
        $sms_payment = SmsPayment::orderBy('sms_payment_id', 'desc')->get();
        return view("SMS::sms_payments", compact('sms_payment'));
    }
}
