<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table ='addresses';
    protected $fillable = ['street','suburb','postcode','state','location_id','created_at','country_list_id'];
    public $timestamps = false;
}
