<?php

namespace App\Modules\User\Models;

use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Sender\Models\Sender;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'person';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'dob', 'sex', 'email','nationality'
    ];

   // protected $dates = ['dob'];

    public $timestamps = false;

    public function sender(){
        return $this->belongsTo(Sender::class,'person_id');

    } public function beneficiary(){
    return $this->belongsTo(Beneficiary::class,'person_id');

    }
}

