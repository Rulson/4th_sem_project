<?php

namespace App\Modules\Email\Models;

use Illuminate\Database\Eloquent\Model;

class CronEmail extends Model
{
    //
    protected $table = 'cron_email';
    protected $primaryKey = 'id';
    protected $fillable = ['from', 'to', 'subject', 'message','status','created_at','updated_at','group'];
}
