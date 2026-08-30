<?php

Route::group(['module' => 'Beneficiary', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Beneficiary\Controllers'], function() {

    Route::get('beneficiaries', ['as' => 'beneficiaries.index', 'uses' => 'BeneficiaryController@index']);
    Route::get('beneficiary/create', ['as' => 'beneficiary.create', 'uses' => 'BeneficiaryController@create']);
    Route::get('bank/create', ['as' => 'bank.create', 'uses' => 'BeneficiaryController@createBank']);
    Route::post('bank/store', ['as' => 'bank.store', 'uses' => 'BeneficiaryController@storeBank']);
    Route::get('banks/{bank_id}/edit', ['as' => 'bank.edit', 'uses' => 'BeneficiaryController@editBank']);
    Route::post('banks/{bank_id}/update', ['as' => 'bank.update', 'uses' => 'BeneficiaryController@updateBank']);
    Route::get('banks', ['as' => 'banks.index', 'uses' => 'BeneficiaryController@banks']);
    Route::get('bank/{bank_id}/status/{status_id}', ['as' => 'bank.change.status', 'uses' => 'BeneficiaryController@changeBankStatus']);


    Route::post('beneficiary', ['as' => 'beneficiary.store', 'uses' => 'BeneficiaryController@store']);
    Route::get('beneficiary/{beneficiary_id}', ['as' => 'beneficiary.show', 'uses' => 'BeneficiaryController@show']);
    Route::get('beneficiary/{beneficiary_id}/edit', ['as' => 'beneficiary.edit', 'uses' => 'BeneficiaryController@edit']);
    Route::get('beneficiary/{beneficiary_id}/status', ['as' => 'beneficiary.changeStatus', 'uses' => 'BeneficiaryController@changeStatus']);
    Route::post('beneficiary/{beneficiary_id}', ['as' => 'beneficiary.update', 'uses' => 'BeneficiaryController@update']);

    Route::post('beneficiary/additional-account/{beneficiary_id}',['as'=>'addition.account.add','uses'=>'BeneficiaryController@addAdditionalAccount']);

    Route::get('updateAccount/{beneficiary_id}','BeneficiaryController@changeAccount')->name('change.account');
    Route::post('updateAccount/{beneficiary_id}','BeneficiaryController@storeChangeAccount')->name('store.account.change');

    Route::get('beneficiaries/search',['as'=>'beneficiary.search.view','uses'=>'BeneficiaryController@viewSearch']);
    Route::post('beneficiaries/search',['as'=>'beneficiary.search.result','uses'=>'BeneficiaryController@viewSearch']);

    Route::get('beneficiaries/getBeneficiariesData',['as'=>'beneficiaries.data.ajax','uses'=>'BeneficiaryController@getBeneficiariesDataByAjax']);
    Route::get('beneficiaries/delete-beneficiary/{beneficiary_id}','BeneficiaryController@deleteBeneficiary')->name('beneficiary.delete');

    Route::get('beneficiary/getState/{data}', ['as' => 'beneficiary.getState', 'uses' => 'BeneficiaryController@getState']);

});
