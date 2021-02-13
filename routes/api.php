<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('registration', 'AuthController@registration')->name('registration');
});

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('account', 'BankController@index');
	Route::post('account', 'BankController@store');
	Route::put('account', 'BankController@update');
    Route::delete('account/{id}', 'BankController@destroy');
	Route::get('organization', 'BankController@organization');
});

