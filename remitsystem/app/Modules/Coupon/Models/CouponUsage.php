<?php

namespace App\Modules\Coupon\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupon_usage';
    public $timestamps = true;

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
         'user_id',
         'transaction_id',
         'coupon_id',
    ];

    public function coupon(){
        return $this->belongsTo(Coupon::class,'coupon_id');

    }
}

