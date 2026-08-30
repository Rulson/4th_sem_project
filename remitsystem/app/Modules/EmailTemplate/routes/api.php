<?php

Route::group(['module' => 'EmailTemplate', 'middleware' => ['api'], 'namespace' => 'App\Modules\EmailTemplate\Controllers'], function() {

    Route::resource('EmailTemplate', 'EmailTemplateController');

});
