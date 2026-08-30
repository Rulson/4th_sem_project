<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class AdressStatus extends Model
{
    //
    protected $table ='address_status';
    protected $fillable = ['name','description'];
    public $timestamps = false;

}
