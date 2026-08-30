<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class AusStates extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'australian_states';
    public $timestamps = false;

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
         'name',
         'type',
        'parent_id',
        'postcode'
    ];
}

