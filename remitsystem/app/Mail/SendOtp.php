<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public string $otp,
        public string $mailSubject
    )
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SendOtp
    {
        return $this->view('Transaction::Email.email_verification_mail')->subject('#' . $this->otp .' '. $this->mailSubject);
    }
}
