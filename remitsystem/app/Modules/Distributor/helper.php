<?php



/**
 *    User Helper
 */

function distributorList(){
    $distributors = \App\Modules\Distributor\Models\Company::pluck('company_name','id')->toArray();
    return $distributors;

}function distributorListObject(){
    $distributors = \App\Modules\Distributor\Models\Company::select('company_name','id')->get();
    return $distributors;

}

