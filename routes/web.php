<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'FrontController@index')->name('front');

// Setup routes
Route::get('setup/start', 'Setup\MetaController@welcome')->name('setup.welcome');
Route::get('setup/requirements', 'Setup\RequirementsController@index')->name('setup.requirements');
Route::get('setup/database', 'Setup\DatabaseController@index')->name('setup.database');
Route::post('setup/database', 'Setup\DatabaseController@configure')->name('setup.database');
Route::get('setup/account', 'Setup\AccountController@index')->name('setup.account');
Route::post('setup/account', 'Setup\AccountController@register')->name('setup.account');
Route::get('setup/complete', 'Setup\MetaController@complete')->name('setup.complete');

// Authentication Routes
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes (disabled, use the `artisan registeruser` command)
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Bookmarklet routes
Route::prefix('bookmarklet')->group(function () {
    Route::get('add', 'App\BookmarkletController@getLinkAddForm')->name('bookmarklet-add');
    Route::get('show', 'App\BookmarkletController@getCompleteView')->name('bookmarklet-complete');
    Route::get('login', 'App\BookmarkletController@getLoginForm')->name('bookmarklet-login');
});

Route::get('cron/{token}', 'API\CronController@run')->name('cron');

// Model routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', 'App\DashboardController@index')->name('dashboard');

    Route::resource('links', 'Models\LinkController');
    Route::resource('lists', 'Models\ListController');
    Route::resource('tags', 'Models\TagController');
    Route::resource('notes', 'Models\NoteController')->except(['index', 'show']);

    Route::get('search', 'App\SearchController@getSearch')->name('get-search');
    Route::post('search', 'App\SearchController@doSearch')->name('do-search');

    Route::get('import', 'App\ImportController@getImport')->name('get-import');
    Route::post('import', 'App\ImportController@doImport')->name('do-import');

    Route::get('export', 'App\ExportController@getExport')->name('get-export');
    Route::post('export', 'App\ExportController@doExport')->name('do-export');

    Route::get('trash', 'App\TrashController@index')->name('get-trash');
    Route::get('trash/clear/{model}', 'App\TrashController@clearTrash')->name('clear-trash');
    Route::get('trash/restore/{model}/{id}', 'App\TrashController@restoreEntry')->name('trash-restore');

    Route::get('settings', 'App\UserSettingsController@getUserSettings')->name('get-usersettings');
    Route::post('settings/account', 'App\UserSettingsController@saveAccountSettings')->name('save-settings-account');
    Route::post('settings/app', 'App\UserSettingsController@saveAppSettings')->name('save-settings-app');
    Route::post('settings/change-password',
        'App\UserSettingsController@changeUserPassword')->name('change-user-password');
    Route::post('settings/generate-api-token',
        'App\UserSettingsController@generateApiToken')->name('generate-api-token');

    Route::get('settings/system', 'App\SystemSettingsController@getSystemSettings')->name('get-sysstemsettings');
    Route::post('settings/system', 'App\SystemSettingsController@saveSystemSettings')->name('save-settings-system');
    Route::post('settings/generate-cron-token',
        'App\SystemSettingsController@generateCronToken')->name('generate-cron-token');

    Route::post('ajax/tags', 'API\AjaxController@getTags')->name('ajax-tags');
    Route::post('ajax/lists', 'API\AjaxController@getLists')->name('ajax-lists');
    Route::post('ajax/existing-links', 'API\AjaxController@searchExistingUrls')->name('ajax-existing-links');
});

// Guest access routes
Route::prefix('guest')->middleware(['guestaccess'])->group(function () {

    Route::resource('lists', 'Guest\ListController')
        ->only(['index', 'show'])
        ->names([
            'index' => 'guest.lists.index',
            'show' => 'guest.lists.show',
        ]);

    Route::resource('links', 'Guest\LinkController')
        ->only(['index'])
        ->names([
            'index' => 'guest.links.index',
        ]);

    Route::resource('tags', 'Guest\TagController')
        ->only(['show'])
        ->names([
            'show' => 'guest.tags.show',
        ]);
});
