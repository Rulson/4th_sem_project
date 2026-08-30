<?php

namespace App\Modules\Application\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applications';
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
        'description',
        'package_name',
        'agent_id',
        'email',
        'domain_url',
        'playstore_url',
        'appstore_url',
        'logo',
        'alert',
        'alert_link',
        'published',
        'firebase_key',
        'phone_number',
        'bank_account_name',
        'bank_bsb',
        'bank_account_number',
        'bank_name',
        'service_charge',
        'contact_email',
        'street',
        'suburb',
        'state',
        'postcode',
        'country_id',
        'notices',
        'discount_percent',
        'viber_info',
        'facebook_info',
        'whatsapp_info',
        'call_info',
        'payment_info',
        'bank_notes',
        'pay_id',
        'terms_and_conditions',
        'contact_person',
        'designation',
        'company_name',
    ];

    protected $casts = [
        'notices' => 'array', //casting for json
        'payment_info' => 'array', //casting for json
    ];

}
