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


Route::put('updatepassword', 'App\Http\Controllers\UpdateApiController@update_password');
Route::put('forgotpassword', 'App\Http\Controllers\UpdateApiController@forgot_password');

