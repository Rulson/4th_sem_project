<?php

namespace App\Modules\Distributor\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table ='companies';
    protected $fillable = ['company_name','phone_no','email','addresses_id','website','logo','abn'];
    public $timestamps = false;
    //
}
