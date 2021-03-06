<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VoteController;
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
    Route::group(['prefix' => 'users'], function () {
        Route::get('account', [UserController::class, 'getAccount']);
        Route::post('change-password', [UserController::class, 'changePassword']);
        Route::post('create-vote', [UserController::class, 'createVote']);
        Route::post('offer-vote', [UserController::class, 'offerVote']);
        Route::post('up-vote', [UserController::class, 'upVote']);
        Route::delete('delete-vote/{id}', [UserController::class, 'deleteVote']);
        Route::get('list-votes', [UserController::class, 'listVote']);
        Route::get('{id}/list-votes', [UserController::class, 'listVoteOfUser']);
        Route::post('cancel-up-vote', [UserController::class, 'unUpVote']);
    });

    Route::group(['prefix' => 'votes'], function () {
        Route::get('{id}', [VoteController::class, 'show']);
        Route::put('{id}/update-title', [VoteController::class, 'updateVoteTitle']);
        Route::put('{id}/add-options', [VoteController::class, 'addOptions']);
        Route::get('', [VoteController::class, 'listVotes']);
    });

    Route::group(['prefix' => 'options'], function () {
        Route::put('{id}', [OptionController::class, 'updateOptions']) ;
        Route::delete('{id}', [OptionController::class, 'deleteOption']);
    });
});
