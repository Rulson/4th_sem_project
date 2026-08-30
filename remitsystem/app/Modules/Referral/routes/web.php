<?php

Route::group(['module' => 'Referral', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\Referral\Controllers'], function() {

    Route::get('referral', 'ReferralController@index')->name('referral');
    Route::get('referrals/list',['as'=>'referrals.list','uses'=>'ReferralController@getReferrals']);

});
