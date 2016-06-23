<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'HomeController@auth');

Route::get('/home/callback', 'HomeController@callback');

Route::get('/home', 'HomeController@index');

Route::get('/field', 'HomeController@field');

Route::get('/prizes', 'PrizeController@index');

Route::get('/prize/winners', 'PrizeController@winner');

Route::get('/reset', 'SettingController@reset');

Route::post('/settings', 'SettingController@index');
Route::get('/settings', 'SettingController@index');

Route::post('/setting', 'SettingController@store');

Route::delete('/setting/{prize}', 'SettingController@destroy');