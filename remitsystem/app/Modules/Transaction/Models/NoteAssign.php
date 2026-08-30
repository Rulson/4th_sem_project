<?php

namespace App\Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;

class NoteAssign extends Model
{
    //
    protected $table = 'notes_assign';
    protected $fillable = [
        'id', 'notes_id', 'created_at','user_id','updated_at','is_read'
    ];
}
