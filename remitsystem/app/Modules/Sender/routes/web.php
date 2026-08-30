<?php

use App\Modules\Sender\Controllers\SenderController;

Route::group(['module' => 'Sender', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Sender\Controllers'], function() {

    //Route::get('import-customers/{id}',['as' => 'senders.import', 'uses' => 'SenderController@importSender']);
    Route::get('senders', ['as' => 'senders.index', 'uses' => 'SenderController@index']);
    Route::get('sender/create', ['as' => 'sender.create', 'uses' => 'SenderController@create']);
    Route::post('sender', ['as' => 'sender.store', 'uses' => 'SenderController@store']);
    Route::get('sender/{sender_id}', [SenderController::class,'show'])->name('sender.show');
    Route::get('sender/{sender_id}/edit', ['as' => 'sender.edit', 'uses' => 'SenderController@edit']);
    Route::post('sender/{sender_id}/status', ['as' => 'sender.changeStatus', 'uses' => 'SenderController@changeStatus']);
    Route::post('sender/{sender_id}/update-sender', ['as' => 'sender.update', 'uses' => 'SenderController@update']);
    Route::post('sender/{sender_id}/update_identification', ['as' => 'identification.update', 'uses' => 'SenderController@update_identification']);
    Route::get('sender/{sender_id}/get_identification', ['as' => 'identification.get', 'uses' => 'SenderController@getIdentification']);
    Route::get('sender/{sender_id}/view_identification', ['as' => 'identification.view', 'uses' => 'SenderController@viewIdentification']);
    Route::get('sender/{sender_id}/address',['as'=>'sender.add.address','uses'=>'SenderController@showAddAddress']);
    Route::post('sender/{sender_id}/address',['as'=>'sender.store.address','uses'=>'SenderController@addNewAddress']);
    Route::get('sender/{sender_id}/identifications',['as'=>'sender.add.new.identification','uses'=>'SenderController@showAddNewIdentifications']);
    Route::post('sender/{sender_id}/identifications',['as'=>'sender.store.new.identification','uses'=>'SenderController@addNewIdentifications']);
    Route::get('sender/changeSenderStatus/{sender_id}',['as'=>'sender.ajax.changeStatus','uses'=>'SenderController@showChangeStatusModal']);

    //Approve identification
    Route::post('identification/approve','SenderController@approveIdentification')->name('sender.identification.approve');
    //Decline identificatoin
    Route::post('identification/decline','SenderController@declineIdentification')->name('sender.identification.decline');

    Route::get('senders/search',['as'=>'sender.search.view','uses'=>'SenderController@viewSearch']);
    Route::post('senders/search',['as'=>'sender.search.result','uses'=>'SenderController@viewSearch']);
    Route::get('senders/getSendersData',['as'=>'sender.data.ajax','uses'=>'SenderController@getSendersDataByAjax']);
   /* Route::get('senders/getSendersDataTest',['as'=>'sender.data.ajax','uses'=>'SenderController@getSendersDataTest']);
    Route::get('senders/testperson',['as'=>'sender.data.test','uses'=>'SenderController@testperson']);*/

    Route::any('senders/{id}/create-user/','SenderController@createUser')->name('sender.createUser');

    Route::get('senders/delete-sender/{sender_id}','SenderController@deleteSender')->name('sender.delete');

    Route::get('senders/email-check','SenderController@emailCheck')->name('sender.email.check');
    Route::get('fetchExchangeRate','SenderController@getLineChartData');

    Route::get('sender/ausStatePostcode/{data}', ['as' => 'sender.ausStatePostcode', 'uses' => 'SenderController@getAusStatePostcode']);
    Route::get('sender/approve/{data}', ['as' => 'sender.approveSender', 'uses' => 'SenderController@approveSender']);

    Route::post('sender/send-email', ['as' => 'sender.send-email', 'uses' => 'SenderController@sendEmailTOSpecificSender']);
    Route::get('sender/{sender_id}/send-mail', ['as' => 'sender.send-mail', 'uses' => 'SenderController@sendEmailToSender']);
    Route::get('sender/{sender_id}/send-notification', ['as' => 'sender.push-notification', 'uses' => 'SenderController@sendNotification']);

    Route::post('sender/send-notification', ['as' => 'sender.send-notification', 'uses' => 'SenderController@sendNotificationToSender']);

    Route::get('senders/similar',['as'=>'senders.similar','uses' => 'SenderController@similarSenders']);
    Route::get('senders/compare/{new_sender_id}/{old_sender_id}',['as'=>'senders.compare','uses' => 'SenderController@compare']);

});
