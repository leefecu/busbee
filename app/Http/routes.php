<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function (){

	Route::get('/', function () {
	    return view('welcome');
	});
	
	Route::resource('users', 'UserController');
	Route::resource('favorite', 'FavoriteController');
	Route::resource('alarm', 'AlarmController');
	Route::get('stop/listById/{id}', array('middleware' => 'cors', 'uses' => 'StopController@getStopListById'));
	Route::get('stop/listNearMeById/{id}', array('middleware' => 'cors', 'uses' => 'StopController@getStopListNearMeById'));
	Route::get('stop/listNearMeBylocation/{location}', array('middleware' => 'cors', 'uses' => 'StopController@getStopListNearMeBylatlon'));

});

Route::group(['prefix' => 'api'], function()
{
	Route::get('search/list/{id}', array('middleware' => 'cors', 'uses' => 'SearchController@getList'));
	Route::get('search/timetable/{id}', array('middleware' => 'cors', 'uses' => 'SearchController@getTimeTable'));
	Route::get('search/stopList', array('middleware' => 'cors', 'uses' => 'SearchController@getStopList'));
	Route::get('user/list/{id}', array('middleware' => 'cors', 'uses' => 'SearchController@getList'));
	Route::get('search/stopListByRouteId/{routeId}', array('middleware' => 'cors', 'uses' => 'SearchController@getStopListByRouteId'));
	
});

