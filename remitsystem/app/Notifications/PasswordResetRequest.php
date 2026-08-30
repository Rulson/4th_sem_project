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

class PasswordResetRequest extends Notification
{
    use Queueable;

    protected $token;
    protected $application;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token,$application,
    private GetApplicationService $getApplicationService,
    )
    {
        $this->token = $token;
        $this->application = $application;
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
        $code = substr($this->token, 3, 5);
        $link = url('password/reset/' . $this->token) . "  ";
        $password_resets = DB::table('password_resets')->get();
        foreach($password_resets as $data){
            if ($this->token == $data->token) {
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
        $email_template = getEmailTemplate('type','reset password',$this->application);

        if($email_template){
            $subject = $email_template->subject;
            $body = $email_template->message;
            $data_array_parse = array(
                'FULL_NAME'  => $user['first_name'] . ' ' . $user['last_name'],
                'CODE'  => $code,
                'RESET_BTN'  => '<a href="'.$link.'">click here</a>',
                'RESET_URL'  => '<a href="'.$link.'">'.$link.'</a>',
            );
            $data_array_parse = format_template_array($this->application,$data_array_parse);
            $subject = parseTemplate($subject,$data_array_parse);
            $body = parseTemplate($body,$data_array_parse);
            $view = 'EmailTemplate::Email/email';
            $param = [
                'body' => $body,
                'subject' => $subject,
                'heading' => $this->getApplicationService->getApplication()->name,
                'subheading' => 'Transfer Money Easily',
            ];
        }
        else{

            $subject = 'Password Reset';
            $body = <<<EOD
      <p>Dear Sir/Madam,<br><br>You recently requested to reset your account password. <br><br>Please enter this verification code <b>$code</b> to change your password.
EOD;
            $view = 'vendor.notifications.email';
            $param = [
                'content' => $body,
                'subject' => $subject,
                'heading' => $this->getApplicationService->getApplication()->name,
                'subheading' => 'Transfer Money Easily',
            ];
        }


        return (new MailMessage)
            ->view($view, $param)
            ->from($this->application->email, $this->getApplicationService->getApplication()->name)
            ->subject('Reset Password');
    }

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
