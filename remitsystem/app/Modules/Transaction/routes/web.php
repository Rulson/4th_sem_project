<?php

Route::group(['module' => 'Transaction', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Transaction\Controllers'], function () {

    Route::get('send-money', ['as' => 'transaction.sendmoney', 'uses' => 'SendMoneyController@index']);
    Route::get('send-money/edit/{id}', ['as' => 'transaction.sendmoney.edit', 'uses' => 'SendMoneyController@editsendmoney']);
    Route::post('send-money', ['as' => 'transaction.post.sendmoney', 'uses' => 'SendMoneyController@store']);
    Route::get('fetch-sender-data', ['as' => 'fetch.sender.data', 'uses' => 'SendMoneyController@fetchSenderData']);
    Route::get('fetch-service-charge', ['as' => 'fetch.sender.service.charge', 'uses' => 'SendMoneyController@fetchSenderServiceCharge']);
    Route::get('fetch-beneficiary-data', ['as' => 'fetch.beneficiary.data', 'uses' => 'SendMoneyController@fetchBeneficiaryData']);
    Route::get('fetch-beneficiaries', ['as' => 'fetch.beneficiaries', 'uses' => 'SendMoneyController@fetchBeneficiaries']);
    Route::get('senders/getSendersDropDownData', ['as' => 'sender.data.ajax', 'uses' => 'SendMoneyController@getSendersDropDownDataByAjax']);
    Route::get('transactions/senders/getSendersDropDownDataForSearch', ['as' => 'sender.data.ajax.search', 'uses' => 'TransactionController@getSendersDropDownDataByAjaxForSearch']);
    Route::get('transactions/beneficiaries/getBeneficiariesDropDownDataForSearch', ['as' => 'beneficiary.data.ajax.search', 'uses' => 'TransactionController@getBeneficiaryDropDownDataByAjaxForSearch']);
    Route::get('/orders', ['as' => 'transactions.orders', 'uses' => 'TransactionController@orders']);
    Route::post('/orders', ['as' => 'transactions.orders.post', 'uses' => 'TransactionController@orders']);
    Route::get('/getOrders', ['as' => 'transactions.getOrders', 'uses' => 'TransactionController@getOrdersByAjax']);
    Route::get('/getStatusSum', ['as' => 'transactions.getStatusSum', 'uses' => 'TransactionController@getStatusSumByAjax']);
    Route::get('/rates', ['as' => 'transactions.rates', 'uses' => 'TransactionController@rates']);
    Route::get('/add-rate', ['as' => 'add.rate', 'uses' => 'TransactionController@addRate']);
    Route::get('/edit-rate/{id}', ['as' => 'edit.rate', 'uses' => 'TransactionController@editRate']);
    Route::post('/store/rate', ['as' => 'store.rate', 'uses' => 'TransactionController@storeRate']);
    Route::post('/update/rate/{id}', ['as' => 'update.rate', 'uses' => 'TransactionController@updateRate']);
    Route::get('/statusChangeModal/{transaction_id}', ['as' => 'changeStatus.modal', 'uses' => 'TransactionController@changeStatusModal']);
    Route::post('/statusChange', ['as' => 'changeStatusModal', 'uses' => 'TransactionController@changeStatusModalStore']);
    Route::get('/updateAgentRateModal/', ['as' => 'updateAgentRate.modal', 'uses' => 'TransactionController@AgentRateModal']);
    Route::post('/updateAgentRateStore/', ['as' => 'updateAgentRate.store', 'uses' => 'TransactionController@StoreAgentRateModal']);
    Route::get('/updateCostRateModal/', ['as' => 'updateCostRate.modal', 'uses' => 'TransactionController@CostRateModal']);
    Route::post('/updateCostRateStore/', ['as' => 'updateCostRate.store', 'uses' => 'TransactionController@StoreCostRateModal']);
    Route::get('/statusVerified/{transaction_id}', ['as' => 'changeIsVerified.modal', 'uses' => 'TransactionController@changeIsVerifiedModal']);
    Route::post('/statusVerified', ['as' => 'changeIsVerified', 'uses' => 'TransactionController@changeIsVerifiedModalStore']);


    Route::get('/orderStatusModal/', ['as' => 'orderStatus.modal', 'uses' => 'TransactionController@orderStatusModal']);
    Route::post('/updateOrderStatus/', ['as' => 'updateOrderStatus.store', 'uses' => 'TransactionController@updateOrderStatusModal']);
    Route::get('/assignDistributorMultiple/', ['as' => 'multipleDistributorAssign.modal', 'uses' => 'TransactionController@MultipleDistributorAssignModal']);
    Route::post('/assignDistributorMultipleStore/', ['as' => 'assignMultipleDistributor.store', 'uses' => 'TransactionController@StoreMultipleDistributorAssign']);

    Route::get('transactions/transaction-dashboard', ['as' => 'transactions.dashboard', 'uses' => 'TransactionController@dashboard']);
    Route::get('transactions/profit-per-day', ['as' => 'transactions.profit-per-day', 'uses' => 'TransactionController@profitPerDay']);

    /*Tracker Routes*/
    Route::get('transactions/tracker/unconfirmed', ['as' => 'transactions.tracker.unconfirmed', 'uses' => 'TransactionController@unconfirmed']);
    Route::get('transactions/tracker/confirmed', ['as' => 'transactions.tracker.confirmed', 'uses' => 'TransactionController@confirmed']);
    Route::get('transactions/tracker/send-for-collection', ['as' => 'transactions.tracker.sendForCollection', 'uses' => 'TransactionController@sendForCollection']);
    Route::get('transactions/tracker/payment-in-progress', ['as' => 'transactions.tracker.paymentInProgress', 'uses' => 'TransactionController@paymentInProgress']);
    Route::get('transactions/tracker/delivered', ['as' => 'transactions.tracker.delivered', 'uses' => 'TransactionController@delivered']);
    Route::get('transactions/tracker/cancelled', ['as' => 'transactions.tracker.cancelled', 'uses' => 'TransactionController@cancelled']);
    Route::get('transactions/tracker/on-hold', ['as' => 'transactions.tracker.onHold', 'uses' => 'TransactionController@onHold']);

    Route::post('change-sender-status/{id}', ['as' => 'transaction.post.changesenderstatus', 'uses' => 'TransactionController@changesenderStatus']);

    Route::get('transactions/getTransactionsData/{status_id}', ['as' => 'transactions.data.ajax', 'uses' => 'TransactionController@transactionDataByAjax']);
    Route::get('transactions/all-orders', ['as' => 'transactions.all-orders', 'uses' => 'TransactionController@allOrders']);

    /**/
    Route::get('transactions/show/{id}', ['as' => 'transactions.show', 'uses' => 'TransactionController@show']);
    Route::get('transactions/changeStatus/{transaction_id}/{status_id}', ['as' => 'transactions.changeStatus', 'uses' => 'TransactionController@changeStatus']);
    Route::get('transactions/changeStatusMultiple/{status_id}', ['as' => 'transactions.changeStatusMultiple', 'uses' => 'TransactionController@changeStatusMultiple']);
    Route::get('transactions/assignDistributorMultiple/{company_id}', ['as' => 'transactions.assignDistributorMultiple', 'uses' => 'TransactionController@assignDistributorMultiple']);
    Route::get('transactions/assignStaffMultiple/{user_id}', ['as' => 'transactions.assignStaffMultiple', 'uses' => 'TransactionController@assignStaffMultiple']);
    Route::post('transactions/assignDistributors', ['as' => 'transactions.assign.distributors', 'uses' => 'TransactionController@assignDistributors']);
    Route::get('transactions/assignAgent/{transaction_id}', ['as' => 'transactions.assign.agent', 'uses' => 'TransactionController@assignAgent']);
    Route::post('transactions/assignAgentStore/{transaction_id}', ['as' => 'transactions.store.assign.agent', 'uses' => 'TransactionController@storeAssignAgent']);
    Route::get('transactions/assignDistributors/edit/{transaction_id}', ['as' => 'transactions.assign.distributors.edit', 'uses' => 'TransactionController@editAssignDistributors']);

    Route::post('change-transaction-status/{transaction_id}', ['as' => 'transactions.post.changeStatus', 'uses' => 'TransactionController@changetransactionStatus']);
    Route::get('transaction/editsender/{sender_id}', ['as' => 'transaction.sender.edit', 'uses' => 'TransactionController@editsender']);

    Route::get('transaction/editbeneficiary/{beneficiary_id}', ['as' => 'transaction.beneficiary.edit', 'uses' => 'TransactionController@editbeneficiary']);

    Route::get('transaction/addsender', ['as' => 'transaction.sender.add', 'uses' => 'TransactionController@addsender']);
    Route::get('transaction/staff-notes/{transaction_id}', ['as' => 'transaction.staffNotes.add', 'uses' => 'TransactionController@addStaffNote']);
    Route::get('transaction/comment/{transaction_id}', ['as' => 'transaction.comment.add', 'uses' => 'TransactionController@addCommentNote']);
    Route::post('transaction/staff-notes/{transaction_id}', ['as' => 'transaction.staffNotes.store', 'uses' => 'TransactionController@storeStaffNote']);
    Route::post('transaction/admin-staff-notes/{transaction_id}', ['as' => 'transaction.adminStaffNotes.store', 'uses' => 'TransactionController@storeAdminStaffNote']);
    Route::post('transaction/comment/{transaction_id}', ['as' => 'transaction.comment.store', 'uses' => 'TransactionController@storeCommentNote']);
    Route::get('transaction/addbeneficiary', ['as' => 'transaction.beneficiary.add', 'uses' => 'TransactionController@addbeneficiary']);

    Route::get('transactions/search', ['as' => 'transactions.search.view', 'uses' => 'TransactionController@viewSearch']);
    Route::post('transactions/search', ['as' => 'transactions.search.result', 'uses' => 'TransactionController@viewSearch']);
    Route::get('transactions/report', ['as' => 'transactions.report.view', 'uses' => 'TransactionController@transactionReport']);
    Route::post('transactions/report', ['as' => 'transactions.report.result', 'uses' => 'TransactionController@transactionReport']);

    Route::get('transaction/receipt-download/{transaction_id}', ['as' => 'transaction.receipt.download', 'uses' => 'TransactionController@downloadReceipt']);
    Route::get('transaction/receipt-view/{document_id}', ['as' => 'transaction.receipt.view', 'uses' => 'TransactionController@viewReceipt']);
    Route::get('transaction/edit-transaction/{transaction_id}', ['as' => 'transaction.transaction.edit', 'uses' => 'TransactionController@edittransaction']);

    Route::post('transaction/save-edit-transaction/{transaction_id}', ['as' => 'transaction.transaction.edit.save', 'uses' => 'TransactionController@saveedittransaction']);

    Route::get('transaction/edit-agent/{transaction_id}', ['as' => 'transaction.agent.edit', 'uses' => 'TransactionController@editAgentModal']);
    Route::post('transaction/save-edit-agent/{transaction_id}/{agent_id}', ['as' => 'transaction.agent.saveEdit', 'uses' => 'TransactionController@saveEditAgentModal']);
    Route::get('invoice/{transaction_id}', array('as' => 'invoice.pdf', 'uses' => 'TransactionController@pdfInvoice'));
    /*Export EXcel*/
    Route::post('transactions/export', ['as' => 'transactions.export', 'uses' => 'TransactionController@excelExport']);
    Route::get('download/export1', ['as' => 'transactions.export1.download', 'uses' => 'TransactionController@downloadExcel1']);
    Route::get('download/export2', ['as' => 'transactions.export2.download', 'uses' => 'TransactionController@downloadExcel2']);
    Route::get('download/export3', ['as' => 'transactions.export3.download', 'uses' => 'TransactionController@downloadExcel3']);
    Route::get('download/export4', ['as' => 'transactions.export4.download', 'uses' => 'TransactionController@downloadExcel4']);
    Route::get('download/export5', ['as' => 'transactions.export5.download', 'uses' => 'TransactionController@downloadExcel5']);
    Route::get('download/export6', ['as' => 'transactions.export6.download', 'uses' => 'TransactionController@downloadExcel6']);
    Route::get('download/export7', ['as' => 'transactions.export7.download', 'uses' => 'TransactionController@downloadExcel7']);
    Route::get('download/export8', ['as' => 'transactions.export8.download', 'uses' => 'TransactionController@downloadExcel8']);
    Route::get('download/austrac', ['as' => 'transactions.exportAustrac.download', 'uses' => 'TransactionController@downloadAustrac']);
    Route::post('transactions/export2', ['as' => 'transactions.export2', 'uses' => 'TransactionController@excelExport2']);
    Route::post('transactions/export3', ['as' => 'transactions.export3', 'uses' => 'TransactionController@excelExport3']);
    Route::post('transactions/export4', ['as' => 'transactions.export4', 'uses' => 'TransactionController@excelExport4']);
    Route::post('transactions/export5', ['as' => 'transactions.export5', 'uses' => 'TransactionController@excelExport5']);
    Route::post('transactions/export6', ['as' => 'transactions.export6', 'uses' => 'TransactionController@excelExport6']);
    Route::post('transactions/export7', ['as' => 'transactions.export7', 'uses' => 'TransactionController@excelExport7']);
    Route::post('transactions/export8', ['as' => 'transactions.export8', 'uses' => 'TransactionController@excelExport8']);
    Route::post('transactions/austrac-export', ['as' => 'transactions.austrac.export', 'uses' => 'TransactionController@excelAustracExport']);


    Route::get('fetchcomments', 'TransactionController@fetchComments');
    Route::get('fetchLineChartData', 'TransactionController@getLineChartData');
    Route::get('fetchTransactionByUsersChartData', 'TransactionController@getTransactionByUserData');
    Route::get('fetchTransactionByDistributorsChartData', 'TransactionController@getTransactionByDistributorsData');
    Route::get('fetchTransactionByAgentsChartData', 'TransactionController@getTransactionByAgentsData');

    Route::get('changeBeneficiary/{sender_id}/{beneficiary_id}/{transaction_id}', 'TransactionController@changeBeneficiary')->name('change.beneficiary');
    Route::post('changeBeneficiary/{transaction_id}', 'TransactionController@storeChangeBeneficiary')->name('store.beneficiary.change');
    Route::get('transaction/view_receipt/{transaction_id}', ['as' => 'transaction.transaction.view_receipt', 'uses' => 'TransactionController@viewReceiptByTransactionId']);

    Route::get('transaction/sender/toggle-restriction/{sender_id}',['as' => 'senders.toggle-restriction', 'uses' => 'TransactionController@toggleMaxAmountRestrictionForSender']);
    Route::get('transaction/agent/toggle-restriction/{agent_id}',['as' => 'agents.toggle-restriction', 'uses' => 'TransactionController@toggleMaxAmountRestrictionForAgent']);


});
