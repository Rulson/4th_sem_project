<?php

namespace App\Modules\Sender\Models;

use Illuminate\Database\Eloquent\Model;

class IdentificationDocument extends Model
{
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'identification_documents';

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
        'document_id'
    ];
    public $timestamps = false;
}
