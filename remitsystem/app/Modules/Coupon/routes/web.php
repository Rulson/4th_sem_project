<?php
Route::group(['module' => 'Coupon', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Coupon\Controllers'], function() {
    Route::get('coupon/create', ['as' => 'coupon.create', 'uses' => 'CouponController@create']);
    Route::post('coupon/store', ['as' => 'coupon.store', 'uses' => 'CouponController@store']);
    Route::get('coupon/{id}/show', ['as' => 'coupon.show', 'uses' => 'CouponController@show']);
    Route::get('coupon/{id}/edit', ['as' => 'coupon.edit', 'uses' => 'CouponController@edit']);
    Route::post('coupon/{id}/update', ['as' => 'coupon.update', 'uses' => 'CouponController@update']);
    Route::get('coupons', ['as' => 'coupons.index', 'uses' => 'CouponController@index']);
    Route::any('coupon/verify',['as' => 'coupon.verify', 'uses' => 'CouponController@verifyCoupon']);

});
