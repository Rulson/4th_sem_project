<?php

namespace App\Modules\Api\Controllers;


use App\Modules\Beneficiary\Models\BankList;
use App\Modules\User\Models\CountryList;
use Illuminate\Http\Request;

class CountryController extends ApiController
{
    public function index(Request $request)
    {
        if ($request) {
            $countrylist = CountryList::select('id','name')->get();
            if($countrylist){
                return response()->json(['response' => $countrylist, 'message' => 'Success', 'status' => 200]);
            } else {
                return response()->json(['message' => 'Countries not found', 'stauts' =>404]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

}
