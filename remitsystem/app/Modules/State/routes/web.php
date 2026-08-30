<?php
Route::group(['module' => 'State', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\State\Controllers'], function() {
    Route::get('state/create', ['as' => 'state.create', 'uses' => 'StateController@create']);
    Route::post('state/store', ['as' => 'state.store', 'uses' => 'StateController@store']);
    Route::get('state/{id}/edit', ['as' => 'state.edit', 'uses' => 'StateController@edit']);
    Route::post('state/{id}/update', ['as' => 'state.update', 'uses' => 'StateController@update']);
    Route::get('states', ['as' => 'states.index', 'uses' => 'StateController@index']);
});
