<?php

use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;

class ReferralCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = User::where('referral_code','=',null)->get();
        foreach($users as $user){
            User::where('id',$user->id)->update([
                'referral_code' => generateReferralCode($user->application_id)
            ]);
        }
    }
}
