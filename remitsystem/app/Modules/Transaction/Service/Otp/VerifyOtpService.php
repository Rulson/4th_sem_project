<?php

namespace App\Modules\Transaction\Service\Otp;

use App\Otp;

class VerifyOtpService
{
    public function verify(string $otp, string $type, string $email)
    {
        $otpRecord = Otp::where('otp', $otp)
            ->where('status', 0)
            ->where('type', $type)
            ->where('email', $email)->first();

        if (!$otpRecord) {
            return false;
        }

        $expirationTime = $otpRecord->created_at->addMinutes(10);
        if (now()->greaterThan($expirationTime)) {
            return false;
        }

        $otpRecord->update(['status' => 1]);
        return true;
    }
}
