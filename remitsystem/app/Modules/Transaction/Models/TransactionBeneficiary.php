<?php

namespace App\Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionBeneficiary extends Model
{
    //
    protected $table = 'transactions_beneficiaries';
    protected $fillable = [
        'transaction_id', 'name','address','phone_number','bank_name','account_number','branch_name','account_name','pickup_district'
    ];

}
