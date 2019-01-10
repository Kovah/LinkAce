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

Route::get('/', 'FrontController@index')->name('front');

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

// Model routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', 'App\DashboardController@index')->name('dashboard');

    Route::resource('categories', 'Models\CategoryController');
    Route::resource('links', 'Models\LinkController');
    Route::resource('notes', 'Models\NoteController');
    Route::resource('tags', 'Models\TagController');

    Route::get('search', 'App\SearchController@getSearch')->name('get-search');
    Route::post('search', 'App\SearchController@doSearch')->name('do-search');

    Route::get('settings', 'App\UserSettingsController@getUserSettings')
        ->name('get-usersettings');
    Route::post('settings', 'App\UserSettingsController@saveUserSettings')
        ->name('save-usersettings');
    Route::post('settings/change-password', 'App\UserSettingsController@changeUserPassword')
        ->name('change-user-password');

    Route::post('ajax/tags', 'API\AjaxController@getTags')->name('ajax-tags');
});

// Guest access routes
Route::prefix('guest')->middleware(['guest'])->group(function () {

    Route::resource('categories', 'Guest\CategoryController')
        ->only(['index', 'show'])
        ->names([
            'index' => 'guest.categories.index',
            'show' => 'guest.categories.show',
        ]);

    Route::resource('links', 'Guest\LinkController')
        ->only(['index'])
        ->names([
            'index' => 'guest.links.index',
        ]);

    Route::resource('tags', 'Guest\TagController')
        ->only(['index', 'show'])
        ->names([
            'index' => 'guest.tags.index',
            'show' => 'guest.tags.show',
        ]);
});
