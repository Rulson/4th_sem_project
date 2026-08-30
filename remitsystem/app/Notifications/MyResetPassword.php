<?php

namespace App\Notifications;

use App\Modules\Application\Service\GetApplicationService;
use App\Modules\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MyResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        $token,
    )
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $getApplicationService = app(GetApplicationService::class);
        $code = substr($this->token, 3, 5);
        $password_resets = DB::table('password_resets')->get();
        foreach($password_resets as $data){
            if (Hash::check( $this->token, $data->token)) {
                $user = $data;
                break;
            }
            else{
                continue;
            }
        }
        if (isset($user)) {
            $user = User::leftjoin('person','person.id','=','users.person_id')->where('person.email', $user->email)->first();
        }
        $link = url('password/reset/' . $this->token) . "  ";
        $application = getAppDetailsForWeb();
        $email_template = getEmailTemplate('type','reset password',$application);

        if($email_template){
            $subject = $email_template->subject;
            $body = $email_template->message;
            $data_array_parse = array(
                'FULL_NAME'  => $user['first_name'] . ' ' . $user['last_name'],
                'CODE'  => $code,
                'RESET_BTN'  => '<a href="'.$link.'">click here</a>',
                'RESET_URL'  => '<a href="'.$link.'">'.$link.'</a>',
            );
            $data_array_parse = format_template_array($application,$data_array_parse);
            $subject = parseTemplate($subject,$data_array_parse);
            $body = parseTemplate($body,$data_array_parse);
            $view = 'EmailTemplate::Email/email';
            $param = [
                'body' => $body,
                'subject' => $subject,
                'heading' => $getApplicationService->getApplication()->name,
                'subheading' => 'Transfer Money Easily',
            ];
        }
        else{

            $subject = 'Password Reset';
            $body = <<<EOD
        <p>You recently requested to reset your account password. Please <a href="{$link}">click here</a> to change your password or follow the link below.</p>
        <a href="{$link}">$link</a>
EOD;
            $view = 'vendor.notifications.email';
            $param = [
                'content' => $body,
                'subject' => $subject,
                'heading' => $getApplicationService->getApplication()->name,
                'subheading' => 'Transfer Money Easily',
            ];
        }
     /*   if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }*/



        return (new MailMessage)
            ->view($view, $param)
            ->from(env('MAIL_FROM_ADDRESS'),env('APP_NAME'))
            ->subject($subject);
    }
       /* return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }*/

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
