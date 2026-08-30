<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class CountryList extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'country_list';

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
        'country_code', 'name'
    ];
}

