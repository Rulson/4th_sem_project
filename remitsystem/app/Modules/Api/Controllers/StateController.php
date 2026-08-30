<?php

namespace App\Modules\Api\Controllers;


use App\Modules\Beneficiary\Constants\StateTypeConstant;
use App\Modules\Beneficiary\Models\BankList;
use App\Modules\User\Models\AusStates;
use App\Modules\User\Models\CountryList;
use Illuminate\Http\Request;

class StateController extends ApiController
{

    public function index(Request $request)
    {
        if ($request) {
            $aus_states = AusStates::select('id','name')->where('type','au_state')->get();
            if($aus_states){
                return response()->json(['response' => $aus_states, 'message' => 'Success', 'status' => 200]);
            } else {
                return response()->json(['message' => 'Countries not found', 'stauts' =>404]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    public function nepaliState(Request $request)
    {
        if ($request) {
            $np_states = AusStates::select('id','name')->where('type',StateTypeConstant::NEW_NP_STATES)->get();
            if($np_states){
                return response()->json(['response' => $np_states, 'message' => 'Success', 'status' => 200]);
            } else {
                return response()->json(['message' => 'Nepali state not found', 'stauts' =>404]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    public function district(Request $request)
    {
        if ($request) {
            $districts = AusStates::select('id','name','new_state_parent_id as province')->where('type','district')->get();
            foreach($districts as $district){
                $district->province = AusStates::where('id',$district->province)->first()->name;
            }
            if($districts){
                return response()->json(['response' => $districts, 'message' => 'Success', 'status' => 200]);
            } else {
                return response()->json(['message' => 'District not found', 'status' =>404]);
            }
        }
        return response()->json(['message' => 'Failed', 'status' => 404]);
    }

    public function getSuburb(Request $request){
        $search = $request->search;
        if(!empty($search)){
            $suburbs = AusStates::select('name')
                ->where('name', 'like', '%' .$search . '%')
                ->where('type','aus_suburb')
                ->get();
        }

        $response = array();
        if(isset($suburbs)){
            foreach($suburbs as $suburb){
                $response[] = array(
                    "id"=>$suburb->name,
                    "text"=>$suburb->name
                );
            }
        }
        return response()->json(['response'=>$response]);
    }

    public function getAusStatePostcode(Request $request,$id){
        $state = AusStates::where('name',$id)->first();
        $data = [];
        if($state){
            if($request->ajax()){
                $data['selected_state'] = AusStates::select('name')->where('id',$state->parent_id)->first();
                $data['au_state'] = AusStates::select('name')->where('type','au_state')->pluck('name','name');
                $data['postcode'] = $state->postcode;
            } else {
                $data['name'] = AusStates::select('name')->where('id',$state->parent_id)->first()->name;
                $data['postcode'] = $state->postcode;
            }

        }
        return response()->json(['response'=>$data,'message' => 'success', 'status' => 200]);
    }

}
