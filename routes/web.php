<?php

use App\Http\Controllers\FetchController;
use App\Http\Controllers\API\CronController;
use App\Http\Controllers\App\BookmarkletController;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\App\ExportController;
use App\Http\Controllers\App\ImportController;
use App\Http\Controllers\App\SearchController;
use App\Http\Controllers\App\SystemSettingsController;
use App\Http\Controllers\App\TrashController;
use App\Http\Controllers\App\UserSettingsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Guest\LinkController as GuestLinkController;
use App\Http\Controllers\Guest\ListController as GuestListController;
use App\Http\Controllers\Guest\TagController as GuestTagController;
use App\Http\Controllers\Models\LinkController;
use App\Http\Controllers\Models\ListController;
use App\Http\Controllers\Models\NoteController;
use App\Http\Controllers\Models\TagController;
use App\Http\Controllers\Setup\AccountController;
use App\Http\Controllers\Setup\DatabaseController;
use App\Http\Controllers\Setup\MetaController;
use App\Http\Controllers\Setup\RequirementsController;
use Illuminate\Support\Facades\Route;

// Frontpage
Route::get('/', FrontController::class)->name('front');

// Setup routes
Route::get('setup/start', [MetaController::class, 'welcome'])
    ->name('setup.welcome');
Route::get('setup/requirements', [RequirementsController::class, 'index'])
    ->name('setup.requirements');
Route::get('setup/database', [DatabaseController::class, 'index'])
    ->name('setup.database');
Route::post('setup/database', [DatabaseController::class, 'configure'])
    ->name('setup.database');
Route::get('setup/account', [AccountController::class, 'index'])
    ->name('setup.account');
Route::post('setup/account', [AccountController::class, 'register'])
    ->name('setup.account');
Route::get('setup/complete', [MetaController::class, 'complete'])
    ->name('setup.complete');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes (disabled, use the `artisan registeruser` command)
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset']);

// Bookmarklet routes
Route::prefix('bookmarklet')->group(function () {
    Route::get('add', [BookmarkletController::class, 'getLinkAddForm'])
        ->name('bookmarklet-add');
    Route::get('show', [BookmarkletController::class, 'getCompleteView'])
        ->name('bookmarklet-complete');
    Route::get('login', [BookmarkletController::class, 'getLoginForm'])
        ->name('bookmarklet-login');
});

Route::get('cron/{token}', CronController::class)->name('cron');

// Model routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('links', LinkController::class);
    Route::resource('lists', ListController::class);
    Route::resource('tags', TagController::class);
    Route::resource('notes', NoteController::class)
        ->except(['index', 'show']);

    Route::post('links/toggle-check/{id}', [LinkController::class, 'updateCheckToggle'])
        ->name('links.toggle-check');

    Route::get('search', [SearchController::class, 'getSearch'])
        ->name('get-search');
    Route::post('search', [SearchController::class, 'doSearch'])
        ->name('do-search');

    Route::get('import', [ImportController::class, 'getImport'])
        ->name('get-import');
    Route::post('import', [ImportController::class, 'doImport'])
        ->name('do-import');

    Route::get('export', [ExportController::class, 'getExport'])
        ->name('get-export');
    Route::post('export', [ExportController::class, 'doExport'])
        ->name('do-export');

    Route::get('trash', [TrashController::class, 'index'])
        ->name('get-trash');
    Route::get('trash/clear/{model}', [TrashController::class, 'clearTrash'])
        ->name('clear-trash');
    Route::get('trash/restore/{model}/{id}', [TrashController::class, 'restoreEntry'])
        ->name('trash-restore');

    Route::get('settings', [UserSettingsController::class, 'getUserSettings'])
        ->name('get-usersettings');
    Route::post('settings/account', [UserSettingsController::class, 'saveAccountSettings'])
        ->name('save-settings-account');
    Route::post('settings/app', [UserSettingsController::class, 'saveAppSettings'])
        ->name('save-settings-app');
    Route::post('settings/change-password', [UserSettingsController::class, 'changeUserPassword'])
        ->name('change-user-password');
    Route::post('settings/generate-api-token', [UserSettingsController::class, 'generateApiToken'])
        ->name('generate-api-token');

    Route::get('settings/system', [SystemSettingsController::class, 'getSystemSettings'])
        ->name('get-sysstemsettings');
    Route::post('settings/system', [SystemSettingsController::class, 'saveSystemSettings'])
        ->name('save-settings-system');
    Route::post('settings/generate-cron-token', [SystemSettingsController::class, 'generateCronToken'])
        ->name('generate-cron-token');

    Route::post('fetch/tags', [FetchController::class, 'getTags'])
        ->name('fetch-tags');
    Route::post('fetch/lists', [FetchController::class, 'getLists'])
        ->name('fetch-lists');
    Route::post('fetch/existing-links', [FetchController::class, 'searchExistingUrls'])
        ->name('fetch-existing-links');
});

// Guest access routes
Route::prefix('guest')->middleware(['guestaccess'])->group(function () {

    Route::resource('lists', GuestListController::class)
        ->only(['index', 'show'])
        ->names([
            'index' => 'guest.lists.index',
            'show' => 'guest.lists.show',
        ]);

    Route::resource('links', GuestLinkController::class)
        ->only(['index'])
        ->names([
            'index' => 'guest.links.index',
        ]);

    Route::resource('tags', GuestTagController::class)
        ->only(['show'])
        ->names([
            'show' => 'guest.tags.show',
        ]);
});
