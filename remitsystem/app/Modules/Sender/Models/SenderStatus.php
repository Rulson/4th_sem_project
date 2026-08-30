<?php

namespace App\Modules\Sender\Models;

use Illuminate\Database\Eloquent\Model;

class SenderStatus extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sender_status';

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
        'name', 'description'
    ];

    /**
     * Disable Laravel's Eloquent timestamps
     */
    public $timestamps = false;
}

