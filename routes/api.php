<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//CRICKET
Route::group(['prefix' => 'cricket'], function () {

    Route::match(['get', 'post'],'login', 'ApiController@login');

    Route::match(['get', 'post'],'socialMediaLogin', 'ApiController@social_media_login');

    Route::match(['get', 'post'],'register', 'ApiController@register');

    Route::match(['get', 'post'],'forgot_password', 'ApiController@forgot_password');

    //Facebook Login
    Route::get('login/facebook', 'Auth\SocialController@redirectToProvider');
    Route::get('login/facebook/callback', 'Auth\SocialController@handleProviderCallback');
    
//    cricket
    Route::get('defaultSports', 'SportsController@defaultSports');
    Route::match(['get', 'post'],'cricketInfo', 'SportsController@cricketInfo');
    Route::get('newsDetail', 'SportsController@newsDetail');
    Route::match(['get', 'post'],'newsList', 'SportsController@newsList');
    Route::match(['get', 'post'],'newsList2', 'SportsController@newsList2');
    Route::match(['get', 'post'],'scoreBoard', 'SportsController@scoreBoard');
    Route::match(['get', 'post'],'summary', 'SportsController@summary');
    Route::match(['get', 'post'],'commentary', 'SportsController@commentary');
    Route::match(['get', 'post'],'squad', 'SportsController@squad');
    Route::match(['get', 'post'],'top5', 'SportsController@top5');
    Route::match(['get', 'post'],'series', 'SportsController@series');
    Route::match(['get', 'post'],'seriesDetail', 'SportsController@seriesDetail');
    

});

//    BASKETBALL
Route::group(['prefix' => 'nba'], function () {
    Route::get('newsList', 'NbaController@newsList');
    Route::get('newsDetail', 'NbaController@newsDetail');
    Route::get('cups', 'NbaController@cups');
    Route::get('cupScores', 'NbaController@cupScores');
    Route::get('summary', 'NbaController@summary');
    Route::get('commentary', 'NbaController@commentary');
    Route::get('squad', 'NbaController@squad');
    Route::get('top5', 'NbaController@top5');
    
});

//    FOOTBALL
Route::group(['prefix' => 'football'], function () {
    Route::get('newsList', 'FootballController@newsList');
    Route::get('newsDetail', 'FootballController@newsDetail');
    Route::get('cups', 'FootballController@cups');
    Route::get('cupScores', 'FootballController@cupScores');
    Route::get('matchSummary', 'FootballController@matchSummary');
    Route::get('commentary', 'FootballController@commentary');
    Route::get('squad', 'FootballController@squad');
    Route::get('summary', 'FootballController@summary');
    Route::get('allSeries', 'FootballController@allSeries');
    Route::get('seriesDetails', 'FootballController@seriesDetails');
    Route::get('top5', 'FootballController@top5');
    
});

