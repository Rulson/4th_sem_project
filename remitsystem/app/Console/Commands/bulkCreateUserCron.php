<?php

namespace App\Console\Commands;

use App\Modules\Application\Service\GetApplicationService;
use App\Modules\Email\Models\CronEmail;
use App\Modules\Sender\Models\Document;
use App\Modules\Sender\Models\Sender;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class bulkCreateUserCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulkCreateUserCron:cron';

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
    public function __construct(
        private GetApplicationService $getApplicationService,
    )
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
        $senders= Sender::inRandomOrder()->take(50)->get();
        foreach($senders as $sender){
            $user_check = User::leftJoin('senders', 'senders.person_id', '=', 'users.person_id')->where('senders.id', $sender->id)->first();
            if(!$user_check){
                $person = Person::find($sender->person_id);

                $user_checkk = User::where('email', $person->email)->first();
                if (!$user_checkk) {
                    $user = User::create([
                        'level_id' => 5,
                        'user_status_id' => 1,
                        'person_id' => $sender->person_id,
                        'email' => strtolower($person->email),
                        'password' => '',
                        'api_token' => getApiToken()
                    ]);
                    $user->auth_code = uniqid() . md5($user->id);
                    $user->save();
                    if ($user) {
                        $sender->update(['sender_status_id'=>2]);
                        $userName = Person::where('id', $sender->person_id)->first();

                        $url = 'https://remit.nepalpaisa.com.au/sender/set-password/'.$user['auth_code'];

                        $body = 'Welcome to Nepal Paisa. We are glad to announce you that we have recently launched our mobile Apps on iOS & Andriod of Nepal Paisa. Now Sending Money to Nepal is more easier and secure with us. Please activate the account by clicking on link below.<br><br> <a href="' . $url . '">Activate Now</a><br><br>';

                        $param = [
                            'to' => strtolower($person->email),
                            'toName' => $userName['first_name'] . ' ' . $userName['last_name'],
                            'body' => $body,
                            'subject' => 'Activate your account',
                            'fromEmail' => env('MAIL_FROM_ADDRESS'),
                            'fromName' => env('FROM_NAME', $this->getApplicationService->getApplication()->name)
                        ];

                        Mail::send('Auth::Email/activation', $param, function ($message) use ($param) {
                            $message->to($param['to'], $param['toName'])
                                ->from($param['fromEmail'], $param['fromName'])
                                ->subject($param['subject']);
                        });
                    }
                }
            }
        }
    }
}
