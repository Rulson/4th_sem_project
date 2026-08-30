<?php

namespace App\Modules\Coupon\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupons';
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
         'name',
         'code',
        'discount_value',
        'discount_unit',
        'start_date',
        'end_date',
        'uses_total',
        'published',
        'application_id',
        'user_type',
    ];

    public function couponUsage(){
        return $this->hasMany(CouponUsage::class,'coupon_id');
    }
}

