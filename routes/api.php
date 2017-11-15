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

Route::resource('/forms', 'FormController');

Route::post('/entities', 'EntityController@action');
Route::put('/entities', 'EntityController@action');
Route::resource('/entities', 'EntityController', ['only' => [
    'index', 'show'
]]);
