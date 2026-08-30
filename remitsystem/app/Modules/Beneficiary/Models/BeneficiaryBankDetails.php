<?php

namespace App\Modules\Beneficiary\Models;

use Illuminate\Database\Eloquent\Model;

class BeneficiaryBankDetails extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'beneficiary_bank_details';

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
        'bank_details_id', 'beneficiaries_beneficiary_id', 'current'
    ];

    /**
     * Disable Laravel's Eloquent timestamps
     */
    public $timestamps = false;
}

