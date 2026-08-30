<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    //
    protected $table ='bank_details';
    protected $fillable = ['account_name','account_no','bsb','bank_name','description','current'];
    public $timestamps = false;


}
