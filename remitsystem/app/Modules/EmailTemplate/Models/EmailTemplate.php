<?php

namespace App\Modules\EmailTemplate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailTemplate extends Model {

    protected $table = 'email_templates';
    protected $primaryKey = 'id';
    protected $fillable = ['application_id', 'type', 'subject', 'active','message','created_at','updated_at'];

    public function add(array $request)
    {
        DB::beginTransaction();

        try {
            $email_template = EmailTemplate::create([
                'application_id'=>$request['application_id'],
                'type'=>$request['type'],
                'subject'=>$request['subject'],
                'message'=>$request['message'],
            ]);

            DB::commit();
            return $email_template;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }
    public function edit(array $request ,$id)
    {
        DB::beginTransaction();

        try {
            $email_template = EmailTemplate::find($id);

            $email_template->application_id=$request['application_id'];
            $email_template->type=$request['type'];
            $email_template->subject=$request['subject'];
            $email_template->message=$request['message'];
            $email_template->active = isset($request['active']);
            $email_template->save();


            DB::commit();
            return $email_template;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

}
