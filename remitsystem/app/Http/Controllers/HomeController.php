<?php

namespace App\Http\Controllers;

use App\Modules\Sender\Models\CustomerSender;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\Sender;
use App\Modules\SMS\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('adminPanel.layouts.master');
    }

    public function data_migrate($start = 0)
    {
        $senders = Sender::select('senders.*','person.email')->join('person', 'person.id', '=', 'senders.person_id')->offset($start)->take(500)->get();
        $count = 0;
        foreach($senders as $sender){
            $customerSender = CustomerSender::where('cr_email',$sender->email)->first();
            if($customerSender){
                if($customerSender->cr_id_type_id!=null){
                    DB::table('identifications')->where('senders_id' ,$sender->id)->update([
                            'identification_types_id' => ($customerSender->cr_id_type_id == 1 ? 2 : ($customerSender->cr_id_type_id == 2 ? 1 : $customerSender->cr_id_type_id ) )
                        ]
                    );
                }
                $count++;
            }
        }
        dd('success '.$count);exit;
    }

}
