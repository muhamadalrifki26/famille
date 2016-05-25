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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/register', 'ApiController@register');
Route::get('/api/login', 'ApiController@login');
Route::get('/api/profile','ApiController@profile');
Route::get('/api/logout','ApiController@logout');
Route::get('/api/simpanlokasi','ApiController@simpanlokasi');
Route::get('/api/undangankeluarga','ApiController@undangankeluarga');
Route::get('/api/ambillokasi','ApiController@ambilLokasi');
