<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesapalController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('login', 'App\Http\Controllers\HomeApiController@loginApi');
Route::post('register', 'App\Http\Controllers\HomeApiController@store_newuser');
Route::post('sendPasswordMessage', 'App\Http\Controllers\EmailController@sendPasswordMessage');

Route::get('fetchcountries', 'App\Http\Controllers\HomeApiController@get_countries');
Route::get('fetchprice', 'App\Http\Controllers\HomeApiController@get_pricelist');


Route::put('updatepassword', 'App\Http\Controllers\UpdateApiController@update_password');
Route::put('updatecurrency', 'App\Http\Controllers\UpdateApiController@update_currency');
Route::put('forgotpassword', 'App\Http\Controllers\UpdateApiController@forgot_password');

//home2controller
Route::post('withdraw', 'App\Http\Controllers\Home2Controller@store_withdrawals');
Route::post('createfeedback', 'App\Http\Controllers\Home2Controller@store_response');

//GetApiController
Route::get('dashboard/{pid}', 'App\Http\Controllers\GetApiController@dashboard');

Route::get('availablesurveys/{pid}', 'App\Http\Controllers\GetApiController@availablesurvey');
Route::get('referrals/{pid}', 'App\Http\Controllers\GetApiController@referrals');
Route::get('transactions/{pid}', 'App\Http\Controllers\GetApiController@transactions');
Route::get('completedsurveys/{pid}', 'App\Http\Controllers\GetApiController@completedSurvey');


// Pesapal Routes

Route::post('/pesapal/submit-order', 'App\Http\Controllers\PesapalController@submitOrderRequest');
Route::get('/pesapal/callback', 'App\Http\Controllers\PesapalController@handleCallback');
Route::get('/pesapal/ipn', 'App\Http\Controllers\PesapalController@handleIPN');
Route::get('/pesapal/check-status', 'App\Http\Controllers\PesapalController@checkTransactionStatus');



// RevenueCat Routes - For Google Pay and other payment methods
Route::post('revenuecat/updateSubscription', 'App\Http\Controllers\RevenueCatController@updateBusinessSubscription');
Route::post('revenuecat/webhook', 'App\Http\Controllers\RevenueCatController@handleRevenueCatWebhook');
Route::get('revenuecat/subscriptionHistory', 'App\Http\Controllers\RevenueCatController@getSubscriptionHistory');
Route::get('revenuecat/getUserById', 'App\Http\Controllers\RevenueCatController@getBusinessById');
Route::post('revenuecat/activateManually', 'App\Http\Controllers\RevenueCatController@activateSubscriptionManually');
