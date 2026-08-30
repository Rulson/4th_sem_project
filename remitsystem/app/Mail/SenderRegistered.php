<?php

namespace App\Mail;

use App\Modules\Sender\Models\Sender;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SenderRegistered extends Mailable {
    use Queueable, SerializesModels;

    /**
     * $senderDetails from the GetSenderDetailsService
     * @param Sender $senderDetails
     */
    public function __construct(
        public Sender $senderDetails,
    ){
    }

    /**
     * Build the message.
     *
     * @return SenderRegistered
     */
    public function build(): SenderRegistered
    {
        return $this->view('Auth::Email.sender_registered')->subject("New Sender Registered!");
    }
}
