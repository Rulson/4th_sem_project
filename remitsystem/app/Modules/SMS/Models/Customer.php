<?php

namespace App\Modules\SMS\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = ['first_name', 'last_name', 'stripe_customer_id', 'email'];
}
