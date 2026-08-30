<?php

namespace App\Modules\Distributor\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyBankDetails extends Model
{
    //
    protected $table ='company_bank_details';
    protected $fillable = ['bank_details_id','companies_id','current'];
    public $timestamps = false;
}
