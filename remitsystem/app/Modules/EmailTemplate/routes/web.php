<?php

Route::group(['prefix'=>'email-template','module' => 'EmailTemplate', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\EmailTemplate\Controllers'], function() {

    Route::get('/', 'EmailTemplateController@index')->name('email-template.index');
    Route::get('create', 'EmailTemplateController@create')->name('email-template.create');
    Route::post('store', 'EmailTemplateController@store')->name('email-template.store');
    Route::get('edit/{id}', 'EmailTemplateController@edit')->name('email-template.edit');
    Route::post('update/{id}', 'EmailTemplateController@update')->name('email-template.update');
});
