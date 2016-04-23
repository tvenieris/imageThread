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

/*
Route::get('/', function () {
    return view('welcome');
});
 */

Route::get('/', 'ImageThreadController@index');
Route::post('/api/posts/create', 'ImageThreadAPIController@create');
Route::get('/api/stats/get', 'ImageThreadAPIController@getStats');

Route::get('/api/posts/export_csv', 'ImageThreadAPIController@exportCSV');
Route::get('/api/posts/export_xls', 'ImageThreadAPIController@exportXLS');

Route::get('/api/posts/get/all', 'ImageThreadAPIController@showAll');
Route::get('/api/posts/get/{id}', 'ImageThreadAPIController@show');
