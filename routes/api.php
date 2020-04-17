<?php

use App\Http\Controllers\API\LinkController;
use App\Http\Controllers\API\ListController;
use App\Http\Controllers\API\ListLinksController;

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
        Route::apiResource('links', LinkController::class)
            ->names([
                'index' => 'api.links.index',
                'show' => 'api.links.show',
                'store' => 'api.links.store',
                'update' => 'api.links.update',
                'destroy' => 'api.links.destroy',
            ]);

        Route::apiResource('lists', ListController::class)
            ->names([
                'index' => 'api.lists.index',
                'show' => 'api.lists.show',
                'store' => 'api.lists.store',
                'update' => 'api.lists.update',
                'destroy' => 'api.lists.destroy',
            ]);

        Route::get('lists/{list}/links', ListLinksController::class)
            ->name('api.lists.links');
    });
});
