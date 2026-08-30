<?php

Route::group(['module' => 'Agent', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Agent\Controllers'], function() {

    /* Agent List */
    Route::get('agents', 'AgentController@index')->name('agents.index');
    Route::get('agents/dashboard','AgentController@dashboard')->name('agents.dashboard');

    Route::get('agent/create', 'AgentController@create')->name('agent.create');
    Route::post('agent/create', 'AgentController@store')->name('agent.store');

    Route::get('agents/show/{id}', 'AgentController@show')->name('agent.show');
    Route::get('agents/edit/{id}','AgentController@edit')->name('agent.edit');
    Route::post('agent/update/{id}','AgentController@update')->name('agent.update');
    Route::get('agents/payment/edit/{payment_id}','AgentController@editPayment')->name('agent.payment.edit');
    Route::post('agent/payment/update/{payment_id}','AgentController@updatePayment')->name('agent.payment.update');

    Route::get('agent/payment/create','PaymentController@create')->name('agent.payment.create');
    Route::get('agent/payment/create/{agent_id}','PaymentController@createPayment')->name('agent.payment.create.individual');
    Route::post('agent/payment/create','PaymentController@store')->name('agent.payment.store');

    Route::any('agent/{id}/create-user/','AgentController@createUser')->name('agent.createuser');

    Route::get('agent/summary','AgentController@paymentSummary')->name('agent.payment.summary');
    Route::get('fetchAgentLineChartData','AgentController@getLineChartData');
    Route::get('fetchAgentBarChartData','AgentController@getAgentBarChartData');


});
