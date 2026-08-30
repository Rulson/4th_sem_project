<?php

namespace App\Modules\Slider\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sliders';
    public $timestamps = false;

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
         'url',
        'image',
        'sort_order','application_id'
    ];
}

