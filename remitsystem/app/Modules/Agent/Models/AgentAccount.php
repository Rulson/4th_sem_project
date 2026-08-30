<?php

namespace App\Modules\Agent\Models;

use Illuminate\Database\Eloquent\Model;

class AgentAccount extends Model
{
    //
    protected $table ='agent_accounts';
    protected $fillable = ['agent_transactions_id','agent_payments_id','created_at'];
    public $timestamps = false;

}
