<?php

namespace App\Modules\SMS\Models;

use App\Modules\Settings\Models\Settings;
use App\Modules\SMS\Models\Customer;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class SMSCron extends Model
{
    protected $table='cron_sms';
    protected $fillable = ['id','source','destination','message','created_at','updated_at','group','status'];
}
