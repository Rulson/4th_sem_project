<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Modules\User\Models\Person;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Auth;

if (\Config::get('app.env') !== 'local') {
    \URL::forceScheme('https');
}

Route::get('genref', function() {
	$u = User::where('level_id', 5)->whereNull('referral_code')->first();
	$r = generateReferralCode($u->application_id);
	$u->referral_code = $r;
	$u->save();
});
Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/data_migrate/{offset}', 'HomeController@data_migrate')->name('data_migrate');
Route::get('/logout', 'Auth\LogOutController@getLogout')->name('loggout');
Route::get('index',function(){
   return view('index');
});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->middleware('auth');
Route::get('terms-and-conditions', 'WebsiteController@termsAndConditions')->name('terms.and.conditions');

