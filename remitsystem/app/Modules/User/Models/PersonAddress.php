<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class PersonAddress extends Model
{
    //
    protected $table = 'person_address';
    protected $fillable = ['address_id', 'person_id', 'current', 'address_status_id'];
    public $timestamps = false;

}
