<?php

namespace App\Modules\Sender\Models;

use Illuminate\Database\Eloquent\Model;

class Identification extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'identifications';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'identification_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expiry_date', 'issued_by', 'id_number', 'identification_documents_id', 'senders_id', 'identification_status_id', 'current', 'identification_types_id'
    ];

   // protected $dates = ['expiry_date'];
}

