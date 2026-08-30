<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class PersonPhone extends Model
{
    //
    protected $table = 'person_phones';
    protected $fillable = ['phones_id', 'current', 'person_id'];
    public $timestamps = false;

}
