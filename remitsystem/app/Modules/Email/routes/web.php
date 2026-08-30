<?php

Route::group(['module' => 'Email', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Email\Controllers'], function() {

    Route::get('email-logs','EmailController@emailLog')->name('email.log');
    Route::get('email/compose','EmailController@composeEmail')->name('email.compose');
    Route::post('email/send','EmailController@store')->name('email.send');
    Route::get('email/order-modal','EmailController@orderModal')->name('email.order.modal');
    Route::post('email/order-send','EmailController@orderSend')->name('email.order.send');
    Route::get('email/bulk-compose','EmailController@bulkCompose')->name('email.bulk.compose');
    Route::post('email/bulk-send','EmailController@bulkSend')->name('email.bulk.send');
    Route::post('email/ckeditor-upload','EmailController@uploadCkEditor')->name('ckeditor.upload.email');

});
