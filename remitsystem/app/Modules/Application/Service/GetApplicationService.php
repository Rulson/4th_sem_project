<?php


namespace App\Modules\Application\Service;
use App\Modules\Application\Models\Application;

class GetApplicationService {
    public function __construct()
    {
    }
    public function getApplication() : ?Application{
        $request = request();
        if(!str_contains($request->path(),'api1')){
            // web env
            $host = $request->getHost();
            $application = Application::where('domain_url', $host)->first();
            if($application) {
                return $application;
            }
            return Application::first();
        }
        // api mobile env
        $appHeader = $request->header('X-APP-PKG');
        $application = Application::where('package_name',$appHeader)->first();
        if($application) {
            return $application;
        }
        return Application::first();
    }
}
