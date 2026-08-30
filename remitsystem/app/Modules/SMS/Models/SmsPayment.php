<?php

namespace App\Modules\SMS\Models;

use Illuminate\Database\Eloquent\Model;

class SmsPayment extends Model
{
    //
    protected $table='sms_payments';
    protected $fillable=['sms_payment_id','amount','payment_type','payment_date','stripe_transaction_id','sms_credit','user_id'];
}
