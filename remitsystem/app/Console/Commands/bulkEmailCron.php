<?php

namespace App\Console\Commands;

use App\Modules\Email\Models\CronEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class bulkEmailCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulkEmail:cron';

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
        $mails = CronEmail::where('status', 0)->take(100)->get();
        foreach($mails as $mail){
            try{
            $data = array(
                'to' => str_replace(' ','',strtolower($mail->to)),
                'subject' => $mail->subject,
                'emailmessage'=>$mail->message,
                'from'=>$mail->from
            );
            if($mail != '' && strpos($mail, "@") !== false){
                Mail::send('Email::email_template',$data,function($message) use($data,$mail){
                    $message->to($data['to']);
                    $message->subject($data['subject']);
                    $message->from($data['from']);
                });
                $mail->status = 1;
                $mail->save();
            }
            }catch(\Exception $e){
                CronEmail::where('id', $mail->id)->update([
                    'status' => 3
                ]);
                continue;
            }
        }
    }
}
