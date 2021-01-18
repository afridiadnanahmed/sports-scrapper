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

Route::get('test', 'TestController@index');
Route::get('importBlog', 'TestController@importBlog');
Route::get('/', 'HomeController@index');
Route::get('cricketHome', [ 'as' => 'cricketHome', 'uses' => 'HomeController@cricketHome']);

Route::get('nbaHome', [ 'as' => 'nbaHome', 'uses' => 'HomeController@nbaHome']);
Route::get('scoreBoard', [ 'as' => 'scoreBoard', 'uses' => 'HomeController@scoreBoard']);
Route::get('summary', [ 'as' => 'summary', 'uses' => 'HomeController@summary']);
Route::get('newsDetail', [ 'as' => 'newsDetail', 'uses' => 'HomeController@newsDetail']);
Route::get('allUsers', [ 'as' => 'allUsers', 'uses' => 'HomeController@allUsers']);

//    FOOTBALL
Route::group(['prefix' => 'football'], function () {
    Route::get('footBallHome', [ 'as' => 'footBallHome', 'uses' => 'HomeController@footBallHome']);
    Route::get('summary', [ 'as' => 'summary', 'uses' => 'HomeController@footBallSummary']);
    Route::get('squad', [ 'as' => 'squad', 'uses' => 'HomeController@footBallSummary']);
    Route::get('commentary', [ 'as' => 'commentary', 'uses' => 'HomeController@footBallSummary']);
});

//    NBA
Route::group(['prefix' => 'nba'], function () {
    Route::get('nbaHome', [ 'as' => 'nbaHome', 'uses' => 'HomeController@nbaHome']);
    Route::get('summary', [ 'as' => 'summary', 'uses' => 'HomeController@nbaSummary']);
    Route::get('squad', [ 'as' => 'squad', 'uses' => 'HomeController@nbaSummary']);
    Route::get('commentary', [ 'as' => 'commentary', 'uses' => 'HomeController@nbaSummary']);
});



// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');


Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

