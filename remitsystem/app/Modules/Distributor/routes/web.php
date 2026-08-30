<?php

Route::group(['module' => 'Distributor', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Distributor\Controllers'], function() {

    /* Distributor List */
    Route::get('distributors', 'DistributorController@index')->name('distributors.index');

    Route::get('distributor/create', 'DistributorController@create')->name('distributor.create');
    Route::post('distributor/create', 'DistributorController@store')->name('distributor.store');

    Route::get('distributors/show/{id}', 'DistributorController@show')->name('distributor.show');
    Route::get('distributors/edit/{id}','DistributorController@edit')->name('distributor.edit');
    Route::post('distributor/update/{id}','DistributorController@update')->name('distributor.update');

    Route::get('distributor/payment/edit/{payment_id}','DistributorController@editPayment')->name('distributor.payment.edit');
    Route::post('distributor/payment/update/{payment_id}','DistributorController@updatePayment')->name('distributor.payment.update');


    Route::get('distributor/payment/create','PaymentController@create')->name('distributor.payment.create');
    Route::post('distributor/payment/create','PaymentController@store')->name('distributor.payment.store');
    Route::get('distributor/payment/create/{company_id}','PaymentController@createPayment')->name('distributor.payment.create.individual');
    Route::get('distributor/summary','DistributorController@balanceSummary')->name('distributor.balance.summary');
});
