<?php

namespace App\Modules\Beneficiary\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bank_details';

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
        'account_name', 'account_no', 'bsb', 'bank_name', 'current', 'description'
    ];

    /**
     * Disable Laravel's Eloquent timestamps
     */
    public $timestamps = false;
}

