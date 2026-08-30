<?php

namespace App\Modules\Agent\Models;

use Illuminate\Database\Eloquent\Model;

class AgentTransaction extends Model
{
    //
    protected $table ='agent_transactions';
    protected $fillable = ['agents_id','transactions_id','exchange_rate','service_charge','total_commission'];
    public $timestamps = false;
}
