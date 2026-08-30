<?php

namespace App\Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    //
    protected $table = 'notes';
    protected $fillable = [
        'comment', 'added_by', 'created_at','transactions_id','updated_at'
    ];

}
