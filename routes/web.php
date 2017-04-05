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

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('http://78.26.174.133/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '3',
            'client_secret' => 'r8JOj2wQFZMy3x6oFRFbVkmyoh4tvxx84YZWVhdN',
            'redirect_uri' => 'http://78.26.174.133/callback',
            'code' => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => '3',
        'redirect_uri' => 'http://78.26.174.133/callback',
        'response_type' => 'code',
        'scope' => '',
    ]);

    return redirect('http://78.26.174.133/oauth/authorize?'.$query);
});