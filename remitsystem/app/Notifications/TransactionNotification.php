<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TransactionNotification extends Notification
{
    use Queueable;

    protected $transaction;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {

        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data=[
            'email' => $this->transaction->email,
            'name' => $this->transaction->name,
            'transaction_id' => $this->transaction->id,
            'status_id' => $this->transaction->transaction_status_id,
        ];
        if($data['status_id'] == 14){
            $data['new'] = true;
        }
        return $data;
    }
}
