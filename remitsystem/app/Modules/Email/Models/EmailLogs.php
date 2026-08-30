<?php

namespace App\Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLogs extends Model
{
    //
    protected $table = 'email_logs';
    protected $primaryKey = 'id';
    protected $fillable = ['from', 'receiver', 'subject', 'email_message','created_at','updated_at','status'];
}
