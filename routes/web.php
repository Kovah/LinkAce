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

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }

    return view('welcome');
})->name('front');

// Authentication Routes
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes (disabled, use the `artisan registeruser` command)
//$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/home', 'App\HomeController@index')->name('home');

// Model routes
Route::group(['middleware' => ['auth']], function () {
    Route::resource('categories', 'Models\CategoryController');
    Route::resource('links', 'Models\LinkController');
    Route::resource('notes', 'Models\NoteController');
    Route::resource('tags', 'Models\TagController');

    Route::get('search', 'App\SearchController@getSearch')->name('get-search');
    Route::post('search', 'App\SearchController@doSearch')->name('do-search');

    Route::post('ajax/tags', 'API\AjaxController@getTags')->name('ajax-tags');
});

