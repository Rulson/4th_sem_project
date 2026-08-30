<?php

Route::group(['module' => 'SMS', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\SMS\Controllers'], function() {

    Route::get('smslog','SmsController@smslog')->name('sms.log');
    Route::get('sms/create','SmsController@composeSMS')->name('sms.compose');
    Route::get('sms/bulk-compose','SmsController@bulkCompose')->name('sms.compose.bulk');
    Route::post('sms/send','SmsController@store')->name('sms.send');
    Route::post('sms/bulk-send','SmsController@bulkSmsSend')->name('sms.send.bulk');
    Route::get('sms-payments','SmsController@smsPayment')->name('sms.payments');
    Route::get('sms/purchase-credit/', 'SmsController@viewPurchase')->name('sms.credit.purchase.show');
    Route::post('sms/purchase-credit/', 'SmsController@postPurchase')->name('sms.credit.purchase');
    Route::post('sms/get_purchase_amount','SmsController@getSMSAmount');

});
