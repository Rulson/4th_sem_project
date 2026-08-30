<?php

namespace App\Modules\Agent\Models;

use Illuminate\Database\Eloquent\Model;

class AgentExchangeRate extends Model
{
    //
       protected $table ='agent_exchange_rate';
    protected $fillable = ['less_than_service_charge','more_than_service_charge','sending_amount_threshold'];
    public $timestamps = false;

}
