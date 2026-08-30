<?php

namespace App\Modules\Sender\Models;

use Illuminate\Database\Eloquent\Model;

class IdentificationTypes extends Model
{
    //
    protected $table = 'identification_types';
    protected $fillable = [
        'name', 'points', 'description'
    ];
    public $timestamps = false;
}
