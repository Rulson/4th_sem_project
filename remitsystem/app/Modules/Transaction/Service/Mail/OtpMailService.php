<?php

namespace App\Modules\Transaction\Service\Mail;

use App\Mail\SendOtp;
use Illuminate\Support\Facades\Mail;

class OtpMailService
{
    public function __construct() {}

    public function send(string $destinationEmail,string $otp, string $subject)
    {
        Mail::to($destinationEmail)->send(new SendOtp($otp, $subject));
    }
}
