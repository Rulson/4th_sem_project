<?php

namespace App\Modules\Beneficiary\Models;

use Illuminate\Database\Eloquent\Model;

class BankList extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bank_lists';

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
        'active'
    ];

    /**
     * Disable Laravel's Eloquent timestamps
     */
    public $timestamps = false;
}

