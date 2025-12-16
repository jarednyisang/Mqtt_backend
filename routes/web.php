<?php

use Illuminate\Support\Facades\Route;


Route::get('/', 'App\Http\Controllers\HomeController@index')->name('welcome');


Route::post('login', 'App\Http\Controllers\HomeController@loginweb');

Route::get('settings', 'App\Http\Controllers\HomeController@settings')->name('settings');

Route::get('admindashboard', 'App\Http\Controllers\AdminController@admindashboard')->name('admindashboard');
Route::get('viewsurvey/{id}', 'App\Http\Controllers\AdminController@viewsurvey')->name('viewsurvey');
Route::get('systemusers', 'App\Http\Controllers\AdminController@systemusers')->name('systemusers');
Route::post('createsurvey', 'App\Http\Controllers\AdminController@store_surveys');


Route::get('/logout', function () {
    Auth::logout(); // logs out user
    session()->invalidate(); // invalidates session
    session()->regenerateToken(); // regenerate CSRF token for safety
    return redirect('/')->with('status', 'You have been logged out successfully.');
});





Route::post('/webhook/lemon-squeezy', 'App\Http\Controllers\SqueezyController@webhook');
Route::post('checkout', 'App\Http\Controllers\SqueezyController@createCheckout');



