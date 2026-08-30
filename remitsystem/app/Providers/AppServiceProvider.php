<?php

namespace App\Providers;

use App\Modules\Application\Models\Application;
use App\Modules\Application\Service\GetApplicationService;
use App\Modules\Sender\Models\Sender;
use App\Modules\User\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('applications') && Application::count() > 0) {
            $getApplicationService = app(GetApplicationService::class);
            View::share('application_obj', $getApplicationService->getApplication());
        }
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFour();

    }
}
