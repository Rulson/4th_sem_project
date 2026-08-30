<?php
Route::group(['module' => 'Slider', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Slider\Controllers'], function() {
    Route::get('slider/create', ['as' => 'slider.create', 'uses' => 'SliderController@create']);
    Route::post('slider/store', ['as' => 'slider.store', 'uses' => 'SliderController@store']);
    Route::get('slider/{id}/edit', ['as' => 'slider.edit', 'uses' => 'SliderController@edit']);
    Route::post('slider/{id}/update', ['as' => 'slider.update', 'uses' => 'SliderController@update']);
    Route::get('slider', ['as' => 'sliders.index', 'uses' => 'SliderController@index']);
    Route::get('slider/{id}/delete', ['as' => 'slider.delete', 'uses' => 'SliderController@destroy']);
});
