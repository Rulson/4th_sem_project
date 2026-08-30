<?php

use App\Modules\Auth\Controllers\RegisterController;

Route::group(['module' => 'Auth', 'middleware' => ['web', 'guest'], 'namespace' => 'App\Modules\Auth\Controllers'], function () {

    // route to show the login form
    Route::get('login', array('as' => 'login', 'uses' => 'LoginController@index'));

    // route to process the form
    Route::post('login', array('uses' => 'LoginController@postLogin'));

    // route to logout
    Route::post('logout', array('as' => 'logout', 'uses' => 'LoginController@logout'));

    // Registration routes
    Route::get('register', 'RegisterController@index');
    Route::post('register', 'RegisterController@postRegister');
    Route::post('register/initial', 'RegisterController@handleInitialRegistration')->name('register.handle-initial-registration');
    Route::get('/register/new', 'RegisterController@newClient')->name('register.newClient');
    Route::post('/register/storeUserClient', [RegisterController::class,'storeUserClient'])->name('register.storeUserClient');

    //Activate User
    Route::get('register/activate/{token}', 'RegisterController@activate')->name('activate');

    Route::get('forgot-password','ForgotPasswordController@showLinkRequestForm')->name('email.request');

    Route::get('sender/set-password/{token}','RegisterController@setSenderPassword')->name('set.sender.password');
    Route::post('sender/activate-sender/{token}', 'RegisterController@activateSender')->name('activate.sender.user');

    Route::get('send-activation-email','LoginController@sendActionEmail')->name('send-activation-email');
    Route::post('resend-email','LoginController@resendEmail')->name('resend-email');
    Route::get('verify-email','RegisterController@verifyEmail')->name('verifyEmail');
    Route::post('verifed-email','RegisterController@verifiedEmail')->name('verified-email');
});
