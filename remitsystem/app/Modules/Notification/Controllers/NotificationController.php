<?php

namespace App\Modules\Notification\Controllers;


use App\Modules\Application\Models\Application;
use App\Modules\Notification\Models\SendNotification;
use App\Modules\Sender\Models\Sender;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;

class NotificationController extends BaseController
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function sendNotification()
    {
        if (!in_array(Auth::user()->level_id,[1,2])) {
            abort(403, 'Unauthorized action.');
        }
        $applications = Application::where('firebase_key','<>','')->select('id','name')->pluck('name','id')->toArray();
        return view("Notification::send_notification",compact('applications'));
    }



    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'url' => 'sometimes|nullable|url',
            'message' => 'required',
        ]);
        $user_id = $this->current_user()->id;
        $pushnoticiation = SendNotification::create([
            'title'=>$request->title,
            'notification_message'=>$request->message,
            'url'=>$request->url,
            'user_id'=>$user_id
        ]);
        if($request->application_id){
            $application = Application::where('id',$request->application_id)->first();
            $pushnoticiation->application_name = $application->name;
            $pushnoticiation->save();
            _sendPushNotification($request->title,$request->message,$request->url,'','',$application->firebase_key);
        } else {
            $pushnoticiation->application_name = 'All';
            $pushnoticiation->save();
            $applications = Application::get();
            foreach ($applications as $application){
                _sendPushNotification($request->title,$request->message,$request->url,'','',$application->firebase_key);
            }
        }

        $notification = array(
          'message' => 'Notification sent successfully!',
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


    public function notificationLog()
    {
        /*  if(!(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 )) {
              abort(403, 'Unauthorized action.');
          }
          $sms = sms::orderBy('id', 'desc')->get();*/
        //  return view("Email::emaillog", compact('sms'));
        $notifications = SendNotification::get();
        return view("Notification::notificationLog",compact('notifications'));
    }

    public function markNotification(Request $request)
    {
       auth()->user()->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }
}
