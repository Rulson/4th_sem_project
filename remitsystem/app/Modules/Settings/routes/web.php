<?php

Route::group(['module' => 'Settings', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Settings\Controllers'], function() {

    /* Settings List */
    Route::get('settings', 'SettingsController@index')->name('settings.index');
    Route::get('settings/create', 'SettingsController@create')->name('settings.create');
    Route::post('settings/store', 'SettingsController@store')->name('settings.store');
    Route::post('settings/update/{id}','SettingsController@update')->name('settings.update');

});
