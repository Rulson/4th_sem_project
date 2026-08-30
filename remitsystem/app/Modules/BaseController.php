<?php

namespace App\Modules;

use App\Http\Controllers\Controller;

use App\Modules\User\Models\CountryList;
use App\Modules\User\Models\Person;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserLevel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use DB;

class BaseController extends Controller{

    protected $layout = 'main';
    function __construct()
    {
        View::share('current_url', Route::current());
        View::share('current_user', $this->current_user());
        View::share('countries', $this->get_country_list());
        View::share('controller', $this->getControllerDetails());
    }

    /**
     * get country array
     * @return array
     */
    public function get_country_list()
    {
        $list = CountryList::orderBy('name', 'asc')->pluck('name','id');
        return $list;
    }

    public function current_user()
    {
        $current_user = Auth::user();

        if(!empty($current_user)) {
            $level = User::find($current_user->id);
            $profile = Person::find($current_user->person_id);
            $current_user->full_name = $profile->first_name . ' ' . $profile->last_name;
            $current_user->first_name = $profile->first_name;
            $current_user->last_name = $profile->last_name;
            $current_user->profile = $profile;
            $current_user->level_type = $level->name;
            $current_user->level_value = $level->value;
            $current_user->level = $level->level_id;
            $current_user->isAdmin = ($level->level_id == 1);
            $current_user->isStaff = ($level->level_id == 2);
            $current_user->isAgent = ($level->level_id == 3);
            $current_user->isDistributor = ($current_user->role == 4);
            $current_user->isClient = ($current_user->role == 5);
        }
        return $current_user;
    }

    /**
     * return success json data to view
     * @param array $data
     * @return mixed
     */
    function success(array $data = array())
    {
        $response = ['status' => 1, 'data' => $data];
        return Response::json($response);
    }


    /**
     * return failed json data to view
     * @param array $data
     * @return mixed
     */
    function fail(array $data = array())
    {
        $response = ['status' => 0, 'data' => $data];
        return Response::json($response);
    }

    function getControllerDetails()
    {
        $action = app('request')->route()->getAction();
        $controller = class_basename($action['controller']);
        list($controller, $action) = explode('@', $controller);
        return $controller;
    }

}