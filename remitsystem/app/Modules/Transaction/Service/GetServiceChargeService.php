<?php

namespace App\Modules\Transaction\Service;

use App\Modules\Application\Service\GetApplicationService;
use App\Modules\Settings\Models\Settings;

readonly class GetServiceChargeService{
    public function __construct(
        private GetApplicationService $getApplicationService,
    ){

    }
    public function get() : float{
        $application = $this->getApplicationService->getApplication();
        $serviceCharge = $application->service_charge;
        if(empty($serviceCharge)){
            $setting = Settings::first();
            $serviceCharge = $setting->service_charge;
        }
        return $serviceCharge;
    }
}
