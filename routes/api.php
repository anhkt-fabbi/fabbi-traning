<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
    Route::get('{social}/login', [AuthController::class, 'redirectSocial']);
    Route::get('{social}/callback', [AuthController::class, 'callbackSocial']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::group(['prefix' => 'users'], function() {
        Route::post('change-password', [UserController::class, 'changePassword']);
    });
});
