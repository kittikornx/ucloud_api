<?php

use App\Member;
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

Route::post('/login', 'UserApiController@login');

Route::middleware(\App\Http\Middleware\ApiToken::class)->group(function () {

    Route::post('/logout', 'UserApiController@logout');
    Route::post('/reset_password', 'UserApiController@resetPassword');

    Route::prefix('me')->group(function () {
        Route::get('/', 'UserApiController@info');
    });

});