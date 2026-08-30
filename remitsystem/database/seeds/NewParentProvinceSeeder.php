<?php

use App\Modules\Beneficiary\Constants\StateTypeConstant;
use App\Modules\User\Models\AusStates;
use Illuminate\Database\Seeder;

class NewParentProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Run NewProvinceSeeder before this
     *
     * @return void
     */
    public function run()
    {
        $koshi = AusStates::where('name', 'Koshi')->first();
        $madhesh = AusStates::where('name', 'Madhesh')->first();
        $bagmati = AusStates::where('name', 'Bagmati')->first();
        $gandaki = AusStates::where('name', 'Gandaki')->first();
        $lumbini = AusStates::where('name', 'Lumbini')->first();
        $karnali = AusStates::where('name', 'Karnali')->first();
        $sudurpashchim = AusStates::where('name', 'Sudurpashchim')->first();
        $npStates = AusStates::where('type', StateTypeConstant::DISTRICT)->get();
        foreach ($npStates as $npState){
            // for koshi
            if(in_array($npState->id, [9,10,11,12,13,14,15,16,17,18,19,20,21,22])){
                $npState->new_state_parent_id = $koshi->id;
                $npState->save();
            }
            /// for madhesh
            if(in_array($npState->id, [31, 25,27,28,30,23,24,26,29])){
                $npState->new_state_parent_id = $madhesh->id;
                $npState->save();
            }
            // for bagmati
            if(in_array($npState->id, [ 31, 32,33,34,35,36,37,38,39,40,41,42,43,32921])){
                $npState->new_state_parent_id = $bagmati->id;
                $npState->save();
            }
            // gandaki
            if(in_array($npState->id, [44,45,46,47,48,49,50,51,52,53,54])){
                $npState->new_state_parent_id = $gandaki->id;
                $npState->save();
            }
            // lumbini
            if(in_array($npState->id, [55,56,57,58,59,60,61,62,63,64,65,66])){
                $npState->new_state_parent_id = $lumbini->id;
                $npState->save();
            }
            // karnali
            if(in_array($npState->id, [67,68,69,70,71,72,73,74,75,76])){
                $npState->new_state_parent_id = $karnali->id;
                $npState->save();
            }
            // sudurpashchim
            if(in_array($npState->id, [78,84,80,81,83,85,79,77,82])){
                $npState->new_state_parent_id = $sudurpashchim->id;
                $npState->save();
            }
        }
    }
}
