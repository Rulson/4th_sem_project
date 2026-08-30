<?php
/*Route::group(['module' => 'User','namespace' => 'App\Modules\User\Controllers'], function() {
    Route::get('/new','UserController@new');
});*/



Route::group(['module' => 'User', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\User\Controllers'], function() {

    /*Route::resource('users', 'UserController');*/

    Route::get('users', ['as' => 'users.index', 'uses' => 'UserController@index']);
    Route::get('user/create', ['as' => 'user.create', 'uses' => 'UserController@create']);
    Route::post('user', ['as' => 'user.store', 'uses' => 'UserController@store']);
    Route::get('user/{user_id}', ['as' => 'user.show', 'uses' => 'UserController@show']);
    Route::get('user/{user_id}/edit', ['as' => 'user.edit', 'uses' => 'UserController@edit']);
    Route::get('user/{user_id}/status', ['as' => 'user.changeStatus', 'uses' => 'UserController@changeStatus']);
    Route::post('user/{user_id}', ['as' => 'user.update', 'uses' => 'UserController@update']);
    Route::get('user/{user_id}/reset-password',['as' => 'user.getPasswordReset','uses' => 'UserController@getResetPassword']);
    Route::post('user/{user_id}/reset-password',['as' => 'user.passwordReset','uses' => 'UserController@resetPassword']);
    Route::get('user/{user_id}/credential',['as' => 'user.credential.edit','uses' => 'UserController@credentialPage']);
    Route::any('user/{user_id}/credential-store',['as' => 'user.credential.edit.store','uses' => 'UserController@credentialStore']);


    Route::post('download-agent-qr/{agent_id}',['as' => 'user.download-agent-qr','uses' => 'UserController@downloadQRCode']);

    Route::get('dashboard', array('as' => 'user.dashboard', 'uses' => 'UserController@dashboard'));
    Route::post('exchange-rate', array('as' => 'dashboard.exchangeRate', 'uses' => 'UserController@exchangeRate'));

    Route::get('notification/read/{note_assign_id}','UserController@markReadNoteAssigned')->name('note.assigned.mark.read');
    //Approve client
    Route::post('client/approve','UserController@approveClient')->name('client.approve');
    //Decline client
    Route::post('client/decline','UserController@declineClient')->name('client.decline');

    Route::get('/test1','UserController@test')->name('test');
    Route::post('/test-store','UserController@storeTest')->name('store.test');
    Route::get('/import','UserController@import')->name('import');
    Route::post('/import-store','UserController@storeImport')->name('store.import');

    Route::post('/store-note-navbar','UserController@storeNoteNavbar')->name('store.note.navbar');
    Route::get('email-test','UserController@emailtest');


});
Route::group(['module' => 'User', 'middleware' => ['web'], 'namespace' => 'App\Modules\User\Controllers'], function() {
    Route::get('/delete-account', 'DeleteUserController@showDeletePage')->name('delete.user.show');
    Route::get('/delete-account/dollar-rupiya', 'DeleteUserController@showDeletePage')->name('delete.user.show.dollar-rupiya');
    Route::delete('/delete-user/account', 'DeleteUserController@deleteAccount')->name('delete.user.account');
});
