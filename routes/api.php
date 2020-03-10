<?php

use App\Http\Controllers\API\LinkController;
use App\Http\Controllers\API\ListController;

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

Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('links', LinkController::class);
        Route::apiResource('lists', ListController::class);
    });
});
