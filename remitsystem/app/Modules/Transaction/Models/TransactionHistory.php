<?php

namespace App\Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    //
    protected $table = 'transaction_histories';
    protected $fillable = [
        'transaction_id', 'status'
    ];

}
