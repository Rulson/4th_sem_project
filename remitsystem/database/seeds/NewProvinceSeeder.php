<?php

use App\Modules\Beneficiary\Constants\StateTypeConstant;
use App\Modules\User\Models\AusStates;
use Illuminate\Database\Seeder;

class NewProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = ['Koshi', 'Madhesh', 'Bagmati', 'Gandaki', 'Lumbini', 'Karnali', 'Sudurpashchim'];
        foreach ($provinces as $province) {
            AusStates::firstOrCreate(
                ['name' => $province,'type' => StateTypeConstant::NEW_NP_STATES]
            );
        }
    }
}
