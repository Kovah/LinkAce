<?php

use App\Http\Controllers\API\LinkController;
use App\Http\Controllers\API\LinkNotesController;
use App\Http\Controllers\API\ListController;
use App\Http\Controllers\API\ListLinksController;
use App\Http\Controllers\API\NoteController;
use App\Http\Controllers\API\TagController;
use App\Http\Controllers\API\TagLinksController;

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

        Route::get('links/{link}/notes', LinkNotesController::class)
            ->name('api.links.notes');

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

        Route::apiResource('tags', TagController::class)
            ->names([
                'index' => 'api.tags.index',
                'show' => 'api.tags.show',
                'store' => 'api.tags.store',
                'update' => 'api.tags.update',
                'destroy' => 'api.tags.destroy',
            ]);

        Route::get('tags/{tag}/links', TagLinksController::class)
            ->name('api.tags.links');

        Route::apiResource('notes', NoteController::class)
            ->names([
                'store' => 'api.notes.store',
                'update' => 'api.notes.update',
                'destroy' => 'api.notes.destroy',
            ])
            ->except(['index', 'show']);
    });
});
