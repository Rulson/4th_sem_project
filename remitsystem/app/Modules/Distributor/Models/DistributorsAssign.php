<?php

namespace App\Modules\Distributor\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorsAssign extends Model
{
    //
    protected $table ='assign_distributors';
    protected $fillable = ['transactions_id','distributor_office_id','amount'];
    public $timestamps = false;

}
