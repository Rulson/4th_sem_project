<?php

namespace App\Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    //
    protected $table = 'status';
    protected $fillable = [
        'name', 'description'
    ];

}
