<?php

namespace App\Http\Middleware;

use App\Modules\Application\Models\Application;
use App\Modules\User\Models\User;
use Closure;

class MobileAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $pkg_name = $request->header('X-APP-PKG');
        $application = Application::where('package_name',$pkg_name)->first();
        if(empty($application)){
            return response()->json(['error' => 'Unauthorized Action','status' => 403],403);
        }

        $access_token = $request->header('X-Access-Token');

        if(!$access_token){
            return response()->json(['error' => 'Unauthorized Action','status' => 403],403);
        }

        $user = User::where('api_token', $access_token)
            ->where('level_id',5)
            ->where('application_id',$application->id)
            ->where('user_status_id',2)
            ->first();
        if (empty($user)) {
            return response()->json(['error' => 'Unauthorized Action','status' => 403],403);
        }

        return $next($request);
    }
}
