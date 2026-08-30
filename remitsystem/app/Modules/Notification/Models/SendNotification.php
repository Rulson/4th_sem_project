<?php

namespace App\Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;

class SendNotification extends Model
{
    //
    protected $table = 'push_notifications';
    protected $primaryKey = 'id';
    protected $fillable = ['title', 'url', 'notification_message','user_id','created_at','updated_at'];

    public function createNotification($data,$user_id = false){

        $user_id = ($user_id == false) ?  current_user_id() :$user_id;
        $notification = SendNotification::create([
            'title'=>$data['title'],
            'notification_message'=>$data['notification_message'],
            'url'=>isset($data['url'])?$data['url']:'',
            'user_id'=>$user_id
        ]);
        return $notification;
    }
}
