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

Route::post('/register', 'Auth\RegisterController@registerJson')->middleware('auth.client');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/user', function (Request $request) {return $request->user();});

    Route::get('/users', 'Api\UserController@getAll');

    Route::post('/update-current', 'Api\UserController@updateCurrent');

    Route::get('/user/{user}', 'Api\UserController@show');

});