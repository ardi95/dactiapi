<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => 'cors'], function(){
//     Route::post('/login','AuthController@login');
// });
Route::post('/login','AuthController@login');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/detail-user', 'AuthController@detail');
    Route::get('/check-token', 'AuthController@checktoken');
    Route::post('/contohpost', 'AuthController@contohpost');
    Route::post('/logout','AuthController@logout');

    Route::resource('/users', 'UsersController');
});
