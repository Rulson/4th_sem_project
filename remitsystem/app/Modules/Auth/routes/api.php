<?php

Route::group(['module' => 'Auth', 'middleware' => ['api'], 'namespace' => 'App\Modules\Auth\Controllers'], function() {

    Route::resource('Auth', 'AuthController');

});
