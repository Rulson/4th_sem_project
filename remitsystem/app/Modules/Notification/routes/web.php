<?php

Route::group(['module' => 'Notification', 'middleware' => ['web', 'auth'], 'namespace' => 'App\Modules\Notification\Controllers'], function() {

    Route::get('notification-logs','NotificationController@notificationLog')->name('notification.log');
    Route::get('notification/send-notification','NotificationController@sendNotification')->name('notification.send_notification');
    Route::post('notification/send','NotificationController@store')->name('notification.send');

    Route::get('/mark-as-read', 'NotificationController@markNotification')->name('sender.markNotification');

});
