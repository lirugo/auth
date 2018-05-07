<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/auth/token', 'Auth\AuthTokenController@getToken');
Route::post('/auth/token', 'Auth\AuthTokenController@postToken');

Route::get('/auth/token/resend', 'Auth\AuthTokenController@getResend');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/settings/twofactor', 'Auth\TwoFactorSettingController@index');
    Route::post('/settings/twofactor', 'Auth\TwoFactorSettingController@update');
});