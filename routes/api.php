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

// directories
Route::resource('directories', 'DirectoryController', [
    'only' => ['index']
]);

// directories/forms
Route::resource('directories.forms', 'FormController', [
    'only' => ['index', 'show']
]);

// directories/forms/entries
Route::post(
    '/directories/{directory}/forms/{form}/entries',
    'FormEntryController@action'
);
Route::put(
    '/directories/{directory}/forms/{form}/entries/{entry}',
    'FormEntryController@action'
);
Route::delete(
    '/directories/{directory}/forms/{form}/entries/{entry}',
    'FormEntryController@action'
);
Route::resource('directories.forms.entries', 'FormEntryController', [
    'only' => ['index', 'show']
]);

// form-entry-statuses
Route::resource('/form-entry-statuses', 'FormEntryStatusController', [
    'only' => ['index']
]);
