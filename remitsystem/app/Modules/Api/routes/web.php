<?php

Route::group(['module' => 'Api', 'middleware' => ['web'], 'namespace' => 'App\Modules\Api\Controllers'], function() {
   Route::resource('Api', 'ApiController');
});
