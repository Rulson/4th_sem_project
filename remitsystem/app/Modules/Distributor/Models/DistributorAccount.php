<?php

namespace App\Modules\Distributor\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorAccount extends Model
{
    //
    protected $table ='distributor_accounts';
    protected $fillable = ['distributor_transactions_id','distributor_payments_id','text','created_at'];
    public $timestamps = false;
}
