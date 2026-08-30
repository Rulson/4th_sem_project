<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    //
    protected $table ='phones';
    protected $fillable = ['number'];
    public $timestamps = false;

    /*
     * Add phone number
     * Output phone id
     * return int
     */
    public function add($number, $type = 1, $area_code='')
    {
        $phone = Phone::create([
            'number' => $number,
            'type' => $type,
            'area_code' => $area_code
        ]);

        return $phone->id;
    }
}
