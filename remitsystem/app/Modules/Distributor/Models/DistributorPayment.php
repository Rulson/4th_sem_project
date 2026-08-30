<?php

namespace App\Modules\Distributor\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorPayment extends Model
{
    //
    protected $table ='distributor_payments';
    protected $fillable = ['distributor_company_id','date','amount','method','description','created_by','added_by','cost_rate'];
    public $timestamps = false;
}
