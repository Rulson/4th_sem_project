<?php

namespace App\Modules\Distributor\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorUser extends Model
{
    //
    protected $table ='distributor_users';
    protected $fillable = ['user_id','distributor_office_id','role_id','notes'];
    public $timestamps = false;

}
