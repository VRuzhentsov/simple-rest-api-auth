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

Route::group(['middleware' => 'auth.client'], function () {
    Route::get('/users', 'Api\UserController@getAll')->middleware('throttle');

    Route::post('/register', 'Auth\RegisterController@registerJson')->middleware('throttle');
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/user', function (Request $request) {return $request->user();});

    Route::post('/update-current', 'Api\UserController@updateCurrent');

    Route::get('/user/{user}', 'Api\UserController@show');
});