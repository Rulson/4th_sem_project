<?php

namespace App\Modules\Referral\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FreeServiceCharge extends Model {

    //
    protected $table = 'free_service_charges';

    protected $fillable = [
      'referrer_user_id',
      'referred_user_id',
      'redeemed_transaction_id',
      'used',
      'transaction_id'
    ];

    public  $timestamps = true;
}
