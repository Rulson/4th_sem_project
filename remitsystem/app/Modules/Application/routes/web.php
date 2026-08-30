<?php

Route::group(['module' => 'Application', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Application\Controllers'], function() {

    /* Application List */
    Route::get('applications', 'ApplicationController@index')->name('applications.index');

    Route::get('application/create', 'ApplicationController@create')->name('application.create');
    Route::post('application/create', 'ApplicationController@store')->name('application.store');

    Route::get('applications/show/{id}', 'ApplicationController@show')->name('application.show');
    Route::get('applications/edit/{id}','ApplicationController@edit')->name('application.edit');
    Route::post('application/update/{id}','ApplicationController@update')->name('application.update');
    Route::get('application/delete/{id}','ApplicationController@destroy')->name('application.delete');
});
