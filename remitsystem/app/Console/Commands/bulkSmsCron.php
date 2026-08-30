<?php

namespace App\Console\Commands;

use App\Modules\Settings\Models\Settings;
use App\Modules\SMS\Models\SMSCron;
use Illuminate\Console\Command;

class bulkSmsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulkSms:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
            $sms_log = SMSCron::where('status', 0)->take(100)->get();
            $username = '';
            $password = '';
            foreach ($sms_log as $sms) {
                $content = 'username=' . rawurlencode($username) .
                    '&password=' . rawurlencode($password) .
                    '&to=' . rawurlencode($sms->destination) .
                    '&from=' . rawurlencode($sms->source) .
                    '&message=' . rawurlencode($sms->sms);
                $ch = curl_init('https://api.smsbroadcast.com.au/api.php');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_exec ($ch);
                curl_close ($ch);
                SMSCron::where('status', 0)->where('id',$sms->id)->update([
                    'status'=>1
                ]);


        }




    }
}
