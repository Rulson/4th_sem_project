<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    //
    protected $table ='exchange_rates';
    protected $fillable = ['created_at','exchange_rate','cost_rate','agent_rate','threshold_amount'];
    public $timestamps = false;
}
