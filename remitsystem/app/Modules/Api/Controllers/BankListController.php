<?php

namespace App\Modules\Api\Controllers;

use App\Http\Controllers\BaseController;
use App\Modules\Beneficiary\Models\BankDetails;
use App\Modules\Beneficiary\Models\BankList;
use App\Modules\Beneficiary\Models\Beneficiary;
use App\Modules\Beneficiary\Models\BeneficiaryBankDetails;
use App\Modules\Sender\Models\Identification;
use App\Modules\Sender\Models\Sender;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Models\TransactionDetails;
use App\Modules\Transaction\Models\TransactionDocument;
use App\Modules\User\Models\Address;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\PersonAddress;
use App\Modules\User\Models\PersonPhone;
use App\Modules\User\Models\Phone;
use App\Modules\User\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BankListController extends ApiController
{
    function __construct(BankList $banklist)
    {
        $this->banklist = $banklist;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request) {
            $banklist = BankList::select('id','name')->where('active',1)->get();
            if($banklist){
                return response()->json(['response' => $banklist, 'message' => 'Success', 'status' => 200]);
            } else {
                return response()->json(['message' => 'Bank not found', 'stauts' =>404]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

}
