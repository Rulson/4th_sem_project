<?php

namespace App\Modules\Transaction\Service\Otp;

use App\Otp;

class GenerateOtpService
{
    public function __construct(){}
    public function generate($email, $type)
    {
        $otpValue = random_int(100000, 999999);
        $otp = Otp::where('email', $email)
            ->where('type', $type)
            ->where('status', 0)
            ->latest()
            ->first();
        if ($otp) {
            $otp->update([
                'otp' => $otpValue,
            ]);
        } else {
            $otp = Otp::create([
                'otp' => $otpValue,
                'email' => $email,
                'type' => $type,
                'status' => 0,
            ]);
        }
        return $otp;
    }
}
