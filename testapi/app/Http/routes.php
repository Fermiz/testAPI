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

// Route::get('/', function () {

// 	$prizes= Prize::orderBy('created_at', 'asc')->get();

//     return view('prizes', [
//         'prizes' => $prizes
//     ]);
// });

// /**
//  * Add Prize
//  */
// Route::post('/prize', function (Request $request) {
//     $validator = Validator::make($request->all(), [
//         'name' => 'required|max:255',
//     ]);

//     if ($validator->fails()) {
//         return redirect('/')
//             ->withInput()
//             ->withErrors($validator);
//     }

//     // Create The Task...
//     $prize= new Prize;
//     $prize->name = $request->name;
//     $prize->number = $request->number;
//     $prize->save();

//     return redirect('/');
// });

// /**
//  * Delete Prize
//  */
// Route::delete('/prize{prize}', function (Prize $prize) {
//     $prize->delete();
//     return redirect('/');
// 
Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        return view('welcome');
    })->middleware('guest');

    Route::get('/prizes', 'PrizeController@index');
    Route::post('/prize', 'PrizeController@store');
    Route::delete('/prize/{prize}', 'PrizeController@destroy');

    Route::auth();

    //Route::get('/home', 'HomeController@index');

});
