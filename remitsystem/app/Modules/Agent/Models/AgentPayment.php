<?php

namespace App\Modules\Agent\Models;

use Illuminate\Database\Eloquent\Model;

class AgentPayment extends Model
{
    //
    protected $table ='agent_payments';
    protected $fillable = ['agent_id','date','amount','method','description','created_at','added_by'];
    public $timestamps = false;

}
