<?php

use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $application  = [
            ['name'=>'Cash Nepal','description'=>'','email'=>'info@expressewa.com.au','agent_id'=>'53','package_name'=>'com.ideas.cashnepal','domain_url'=>'remit.cashnepal.com.au','published'=>1,'playstore_url'=>'','appstore_url'=>''],
            ['name'=>'Nepal Paisa','description'=>'','email'=>'info@nepalpaisa.com.au','agent_id'=>'120','package_name'=>'com.ideas.nepalpaisa','domain_url'=>'remit.nepalpaisa.com.au','published'=>1,'playstore_url'=>'https://play.google.com/store/apps/details?id=com.ideas.nepalpaisa','appstore_url'=>'https://apps.apple.com/us/app/id1505924929?ls=1'],
            ['name'=>'Dollar Rupiya','description'=>'','email'=>'info@dollarruipya.com.au','agent_id'=>'122','package_name'=>'com.ideas.dollarrupiya','domain_url'=>'remit.dollarrupiya.com.au’','published'=>1,'playstore_url'=>'','appstore_url'=>'']
        ];
        \Illuminate\Support\Facades\DB::table('applications')->insert($application);
    }
}
