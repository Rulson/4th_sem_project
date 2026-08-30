<?php

use App\Modules\Api\Controllers\ApiController;
use App\Modules\Api\Controllers\BeneficiaryController;
use App\Modules\Api\Controllers\HomeController;
use App\Modules\Api\Controllers\PinAuthController;
use App\Modules\Api\Controllers\AuthController;
use App\Modules\Api\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::group(['module' => 'Api', 'middleware' => ['api'], 'namespace' => 'App\Modules\Api\Controllers'], function() {
    Route::group(['prefix' => 'api'], function () {
        // Route::resource('Api', 'ApiController');
        Route::post('/transaction/store', 'TransactionController@store');
        Route::post('/transaction/update/{transaction_id}','TransactionController@update');
        Route::post('transactions','TransactionController@index');
        Route::post('/login','AuthController@login');
        Route::post('/verify-email','AuthController@verifyEmail');
        Route::post('/register','AuthController@register');
        Route::post('/beneficiary/store', 'BeneficiaryController@store');
        Route::post('/beneficiary/update/{beneficiary_id}','BeneficiaryController@update');
        Route::post('/beneficiaries','BeneficiaryController@index');
        Route::post('/beneficiary/delete/{beneficiary_id}','BeneficiaryController@destroy');
        Route::post('/beneficiary/show','BeneficiaryController@show');
        Route::post('/home','HomeController@home');
        Route::post('/profile','HomeController@profile');
        Route::post('/profile/edit','AuthController@profileEdit');
        Route::post('/password/email','AuthController@sendResetLinkEmail');
        Route::post('/password/reset','AuthController@reset');
        Route::post('/forgot-password-otp',[AuthController::class,'forgotPasswordOtp']);
        Route::post('/forgot-password-change',[AuthController::class,'forgotPasswordChange']);

        Route::post('/banks','BankListController@index');
        Route::post('/countries','CountryController@index');
        Route::post('/aus_state','StateController@index');
        Route::post('/np_state','StateController@nepaliState');
        Route::post('/districts','StateController@district');
        Route::post('/about_us','HomeController@about_us');
        Route::get('/getSuburb','StateController@getSuburb');
        Route::get('/ausStatePostcode/{data}','StateController@getAusStatePostcode');
        Route::post('/verifyCoupon','CouponController@verifyCoupon');

        Route::post('/logout','AuthController@logout');
        Route::post('/checkExistingUser','AuthController@checkExistingUser');
        Route::post('/push_notificaton','HomeController@pushNotification');
        Route::post('/password/change','AuthController@changePassword');
        //notification
        Route::post('/notification/list','NotificationController@notificationList');
        Route::post('/notification/mark-as-read','NotificationController@markAsRead');

        Route::post('referrals','ReferralController@referralList');
        Route::post('referral-transactions','ReferralController@referralTransactionList');
        Route::post('/verifyReferralDiscount','ReferralController@verifyReferralDiscount');

        Route::post('/social/data','HomeController@socialMediaData');


    });
});

Route::group(['module' => 'Api', 'middleware' => ['api'], 'namespace' => 'App\Modules\Api\Controllers'], function() {
    Route::group(['prefix' => 'api1'], function () {
        Route::post('/login',[AuthController::class,'login']);
        Route::post('/verify-email','AuthController@verifyEmail');
        Route::post('/register',[AuthController::class,'register']);
        Route::post('/password/email','AuthController@sendResetLinkEmail');
        Route::post('/password/reset','AuthController@reset');
        Route::post('/checkExistingUser','AuthController@checkExistingUser');

        Route::post('/social/data','HomeController@socialMediaData');
        Route::post('/forgot-password-otp',[AuthController::class,'forgotPasswordOtp']);
        Route::post('/forgot-password-change',[AuthController::class,'forgotPasswordChange']);

        Route::post('/resend-otp',[AuthController::class,'resendOtp'])->middleware('throttle:5,1');
        Route::post('/resend-activation-code',[AuthController::class,'resendActivationCode']);

        Route::post('/countries','CountryController@index');
        Route::post('/aus_state','StateController@index');
        Route::post('/getSuburb','StateController@getSuburb');
        Route::get('/ausStatePostcode/{data}','StateController@getAusStatePostcode');
        Route::get('/getExchangeRate',[ApiController::class,'getExchangeRate'])->middleware('disable_cors');

        Route::post('/check-email-availability-and-send-otp',[AuthController::class ,'checkEmailAvailability']);
        Route::post('/verify-email-new',[AuthController::class,'verifyEmailNew']);
    });
});

Route::group(['module' => 'Api', 'middleware' => ['api','auth.mobile'], 'namespace' => 'App\Modules\Api\Controllers'], function() {
    Route::group(['prefix' => 'api1'], function () {

        Route::post('/set-pin',[PinAuthController::class,'setPin']);
        Route::post('/validate-pin',[PinAuthController::class,'validatePin']);

        Route::post('/transaction/store', [TransactionController::class,'store']);
        Route::post('/transaction/update/{transaction_id}','TransactionController@update');
        Route::post('transactions','TransactionController@index');
        Route::post('/home',[HomeController::class,'home']);
        Route::post('/profile','HomeController@profile');
        Route::post('/profile/edit','AuthController@profileEdit');

        Route::post('/about_us','HomeController@about_us');

        Route::post('/verifyCoupon','CouponController@verifyCoupon');

        Route::post('/logout','AuthController@logout');

        Route::post('/push_notificaton','HomeController@pushNotification');
        Route::post('/password/change','AuthController@changePassword');
        //notification
        Route::post('/notification/list','NotificationController@notificationList');
        Route::post('/notification/mark-as-read','NotificationController@markAsRead');

        Route::post('referrals','ReferralController@referralList');
        Route::post('referral-transactions','ReferralController@referralTransactionList');
        Route::post('/verifyReferralDiscount','ReferralController@verifyReferralDiscount');

        Route::post('/beneficiary/store', [BeneficiaryController::class,'store']);
        Route::post('/beneficiary/update/{beneficiary_id}','BeneficiaryController@update');
        Route::post('/beneficiaries','BeneficiaryController@index');
        Route::post('/beneficiary/delete/{beneficiary_id}','BeneficiaryController@destroy');
        Route::post('/beneficiary/show','BeneficiaryController@show');

        Route::post('/banks','BankListController@index');
        Route::post('/np_state','StateController@nepaliState');
        Route::post('/districts','StateController@district');

        Route::post('exchange-rates',[HomeController::class,'getExchangeRates']);

    });
});

