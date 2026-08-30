<?php

namespace App\Modules\Email\Controllers;

use App\Modules\Agent\Models\Agent;
use App\Modules\Application\Models\Application;
use App\Modules\Application\Service\GetApplicationService;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Email\Models\EmailLogs;
use App\Modules\Sender\Models\Sender;
use App\Modules\Settings\Models\Settings;
use App\Modules\Email\Models\CronEmail;
use App\Modules\SMS\Models\sms;
use App\Modules\SMS\Models\SmsPayment;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;

class EmailController extends BaseController
{

    function __construct(
        SMS $sms, Request $request, Agent $agent,
        private GetApplicationService $getApplicationService,
    )
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


    public function composeEmail()
    {
        if (!(Auth::user()->level_id == 1)) {
            abort(403, 'Unauthorized action.');
        }

        return view("Email::compose");
    }

    public function bulkCompose()
    {
        if (!(Auth::user()->level_id == 1)) {
            abort(403, 'Unauthorized action.');
        }
        return view("Email::bulkCompose");
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required | email',
            'subject' => 'required',
            'message' => 'required',
        ]);
        // $admin_email = strtolower($this->current_user()->email);
        $admin_email =  env('MAIL_FROM_ADDRESS');

        $data = array(
            'to' => strtolower($request->email),
            'subject' => $request->subject,
            'emailmessage'=>$request->message,
            'from'=>$admin_email
        );

      Mail::send('Email::email_template',$data,function($message) use($data){
            $message->to($data['to']);
            $message->subject($data['subject']);
            $message->from($data['from'],$this->getApplicationService->getApplication()->name);
      });
     EmailLogs::create([
          'from'=>$admin_email,
          'receiver'=>$request->email,
          'subject'=>$request->subject,
          'email_message'=>$request->message,
          'status'=>'sent'
      ]);
      $notification = array(
          'message' => 'Email sent successfully!',
          'alert-type' => 'success'
      );
      return redirect()->back()->with($notification);
    }

    public function orderModal()
    {

	    $ids = $_GET['ids'];
        return view("Email::orderCompose", compact('ids'));
    }


    public function orderSend(Request $request)
    {
		    $request->validate([
            'order_id' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $admin_email =  env('MAIL_FROM_ADDRESS');
        $count = 0;

        $applications = Application::get();
        $applications_admin_email = [];
        $applications_admin_email[0] = $admin_email;
        foreach($applications as $application){
            $applications_admin_email[$application->id] = $application->email;
        }
	$oid = explode(',', $request->order_id);
	foreach ($oid as $key => $value) {
		$order = Transaction::find($value);
            $user = User::where('id', $order->added_by)->first();
            $count = $count +1;
                CronEmail::create([
                    'from' => ($user->application_id == null) ? $applications_admin_email[0] : $applications_admin_email[$user->application_id],
                    'to' => strtolower($user->email),
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'group' => 'order'
                ]);
                $receiver_msg = 'sent to '.$count.' orders';
        }
        EmailLogs::create([
            'from' => $admin_email,
            'receiver' => 'sent to ' . $count . ' orders',
            'subject' => $request->subject,
            'email_message' => $request->message,

        ]);
        return $this->success(['message'=>'success']);
    }

    public function bulkSend(Request $request)
    {
        $request->validate([
            'receiver' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $admin_email =  env('MAIL_FROM_ADDRESS');
        $count = 0;

        $applications = Application::get();
        $applications_admin_email = [];
        $applications_admin_email[0] = $admin_email;
        foreach($applications as $application){
            $applications_admin_email[$application->id] = $application->email;
        }

        switch($request->receiver) {
            case 'Agents':
            $agents = $this->agent->getAgentsEmail();
            foreach ($agents as $agent) {
                $count = $count +1;
                CronEmail::create([
                    'from' => $admin_email,
                    'to' => strtolower($agent),
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'group' => 'agent'
                ]);

            }
            $receiver_msg = 'sent to '.$count.' agents';
            break;
            case 'Senders':
            $sender_model = new Sender();
            $senders = $sender_model->getSendersEmail();
            foreach ($senders as $sender) {
                $count = $count +1;
                CronEmail::create([
                    'from' => ($sender->application_id == null) ? $applications_admin_email[0] : $applications_admin_email[$sender->application_id],
                    'to' => strtolower($sender->email),
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'group' => 'sender'
                ]);
            }
            $receiver_msg = 'sent to '.$count.' senders';
            break;
            case 'cash-nepal':
            $added_by = 53;
            $sender_model = new Sender();
            $senders = $sender_model->getSendersEmailByAddBy($added_by);

            foreach ($senders as $sender) {
                $count = $count + 1;
                CronEmail::create([
                    'from' => ($sender->application_id == null) ? $applications_admin_email[0] : $applications_admin_email[$sender->application_id],
                    'to' => strtolower($sender->email),
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'group' => 'cash-nepal-sender'
                ]);
            }
            $receiver_msg = 'sent to '.$count.' cash-nepal senders';
            break;
            case 'nepal-paisa':
            $added_by = 120;
            $sender_model = new Sender();
            $senders = $sender_model->getSendersEmailByAddBy($added_by);
            foreach ($senders as $sender) {
                $count = $count + 1;
                CronEmail::create([
                    'from' => ($sender->application_id == null) ? $applications_admin_email[0] : $applications_admin_email[$sender->application_id],
                    'to' => strtolower($sender->email),
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'group' => 'nepal-paisa-sender'
                ]);
            }
            $receiver_msg = 'sent to '.$count.' nepal-paisa senders';
            break;
            case 'dollar-rupiya':
            $added_by = 122;
            $sender_model = new Sender();
            $senders = $sender_model->getSendersEmailByAddBy($added_by);
            foreach ($senders as $sender) {
                $count = $count + 1;
                CronEmail::create([
                    'from' => ($sender->application_id == null) ? $applications_admin_email[0] : $applications_admin_email[$sender->application_id],
                    'to' => strtolower($sender->email),
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'group' => 'dollar-rupiya-sender'
                ]);
            }
            $receiver_msg = 'sent to '.$count.' dollar-rupiya senders';
            break;
            default:
            if(substr( $request->receiver, 0, 12 ) === "application-") {
                $appId = substr($request->receiver, 12, strlen($request->receiver));
                $application = Application::find($appId);
                $admin_email = $application->email;
                $senders = User::where('application_id', $appId)->where('user_status_id', 2)->get();
                foreach ($senders as $sender) {
                    $count = $count + 1;
                    CronEmail::create([
                        'from' => $admin_email,
                        'to' => strtolower($sender->email),
                        'subject' => $request->subject,
                        'message' => $request->message,
                        'group' => $request->receiver.'-users'
                    ]);
                }
                $receiver_msg = 'sent to '.$count.' '.$application->name.' users';
            } else {
                $receiver_msg = 'not send';
            }
            break;
        }
        EmailLogs::create([
            'from' => $admin_email,
            'receiver' => 'sent to ' . $count . ' senders',
            'subject' => $request->subject,
            'email_message' => $request->message,

        ]);
        $notification = array(
            'message' => 'Bulk Email has been croned successfully!',
            'alert-type' => 'success'
        );
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


    public function emailLog()
    {
          if(!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )) {
              abort(403, 'Unauthorized action.');
          }
        /*  $sms = sms::orderBy('id', 'desc')->get();*/
        //  return view("Email::emaillog", compact('sms'));
        $email = EmailLogs::get();
        return view("Email::emaillog",compact('email'));
    }

    public function uploadCkEditor(Request $request)
    {

        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->storeAs('public/promos', $fileName);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('storage/promos/' . $fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
