<?php

namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    //
    protected $table ='settings';
    protected $fillable = ['favcon','sms_fee','sms_credit','service_charge','company_name','abn','logo','phone_number','email_address','street','suburb','state','postcode','country','account_name','account_no','bsb','bank_name','description','notes'];
    public $timestamps = false;

    function getSMSFee()
    {
        $data = Settings::first();

        return $data->sms_fee;
    }

}
