<?php

namespace App\Modules\Sender\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'documents';

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
        'type','user_id','name','name1','shelf_location','description'
    ];
    public $timestamps = false;
}
