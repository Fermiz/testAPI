<?php

use App\Prize;
use Illuminate\Http\Request;
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

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        //使用方法  
        $client_id = 'b4bf49696999fae5056acacf659b449f73fe9639c03213a673d763e0c5f04088';
        $response_type = 'code';
        $redirect_uri = 'http://localhost/home/callback';
        $scope = 'public%20forms%20read_entries%20form_setting';

        return redirect("https://account.jinshuju.net/oauth/authorize?client_id={$client_id}&response_type={$response_type}&redirect_uri={$redirect_uri}&scope={$scope}");
    });
    //Route::get('/', 'HomeController@call');
    //Route::get('/home/callback', 'HomeController@test2');
    Route::get('/home/callback', 'HomeController@call');
    Route::get('/home', 'HomeController@index');

    Route::get('/field', 'HomeController@field');

    Route::get('/prize/winners', 'PrizeController@winner');
    Route::post('/prize', 'PrizeController@index');
    //Route::delete('/prize/{prize}', 'PrizeController@destroy');

    //Route::auth();

});
