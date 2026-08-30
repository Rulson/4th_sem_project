<?php

use App\Modules\Agent\Models\Agent;
use App\Modules\User\Models\State;

/**
 *    User Helper
 */
function getCountryList()
{
    $countries = \App\Modules\User\Models\CountryList::pluck('name', 'id')->toArray();
    return $countries;
}

function getCountryListObject()
{
    $countries = \App\Modules\User\Models\CountryList::select('name', 'id')->get();
    return $countries;
}

function AusStates()
{
    $states = \App\Modules\User\Models\AusStates::select('name', 'id')->where('type','au_state')->get();
    return $states;
}

function agentList(){
    $agents = Agent::join('person','person.id','=','agents.person_id')->select(DB::raw("CONCAT(person.first_name,' ',person.last_name) as name"),'agents.id as id')->pluck('name','id')->toArray();
    return $agents;

}

function agentUserList(){
    $agents = Agent::join('person','person.id','=','agents.person_id')->join('users','users.person_id','person.id')->select(DB::raw("CONCAT(person.first_name,' ',person.last_name) as name"),'users.id as id')->pluck('name','users.id')->toArray();
    return $agents;

}
