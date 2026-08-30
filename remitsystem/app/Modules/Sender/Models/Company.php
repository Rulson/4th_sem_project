<?php

namespace App\Modules\Sender\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

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
        'company_name', 'abn', 'phone_no', 'email', 'addresses_id', 'website', 'logo'
    ];

    /**
     * Disable Laravel's Eloquent timestamps
     */
    public $timestamps = false;
}

