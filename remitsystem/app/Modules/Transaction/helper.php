<?php

/**
 *	User Helper  
 */

function getBeneficiaryName($beneficiary_id){

    $beneficiary = \App\Modules\Beneficiary\Models\Beneficiary::leftJoin('person','person.id','=','beneficiaries.person_id')
        ->where('beneficiaries.beneficiary_id',$beneficiary_id)
        ->select('person.*',Illuminate\Support\Facades\DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS full_name'))
        ->first();

    return $beneficiary['full_name'];

}
function getSenderName($sender_id){
    $sender = \App\Modules\Sender\Models\Sender::leftJoin('person','person.id','=','senders.person_id')
        ->where('senders.id',$sender_id)
        ->select('person.*',Illuminate\Support\Facades\DB::raw('CONCAT_WS(" ", person.first_name, NULLIF(person.middle_name,""), person.last_name) AS full_name'))
        ->first();
    return $sender->full_name;
}

