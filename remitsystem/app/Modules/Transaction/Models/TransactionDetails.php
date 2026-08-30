<?php

namespace App\Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transaction_details';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'transaction_details_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_date','cost_rate','sending_amount', 'exchange_rate','payment_amount', 'service_charge', 'total_to_pay', 'receive_type', 'payment_type', 'receive_bank_name', 'purpose_of_transfer', 'staff_notes','admin_staff_notes'
    ];

    /**
     * Disable Laravel's Eloquent timestamps
     */
    public $timestamps = false;
}

