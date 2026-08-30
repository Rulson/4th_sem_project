<?php

namespace App\Modules\Auth\Service;

use App\Mail\SenderRegistered;
use App\Modules\Application\Service\GetApplicationService;
use App\Modules\Sender\Models\Sender;
use App\Modules\Sender\Service\GetSenderDetailsService;
use Illuminate\Support\Facades\Mail;

class SendSenderRegisteredEmailService {
    public function __construct(
        private GetApplicationService $getApplicationService,
        private GetSenderDetailsService $getSenderDetailsService,
    )
    {
    }
    public function sendToAdmin(Sender $sender): void
    {
        $application = $this->getApplicationService->getApplication();
        $destinationEmail = $application->email;
        $senderDetails = $this->getSenderDetailsService->getSenderDetails($sender->id);
        Mail::to($destinationEmail)->send(new SenderRegistered($senderDetails));
    }
}
