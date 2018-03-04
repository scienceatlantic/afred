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

// directories/{id}/forms/{id}
Route::resource('directories.forms', 'FormController', [
    'only' => ['index', 'show']
]);

// directories/{id}/forms/{id}/search-sections
Route::resource('directories.forms.search-sections', 'SearchSectionController', [
    'only' => ['index']
]);

// directories/{id}/forms/{id}/entries
Route::post(
    'directories/{directory}/forms/{form}/entries',
    'FormEntryController@action'
);

// directories/{id}/forms/{id}/entries/{id}
Route::put(
    'directories/{directory}/forms/{form}/entries/{entry}',
    'FormEntryController@action'
);
Route::delete(
    'directories/{directory}/forms/{form}/entries/{entry}',
    'FormEntryController@action'
);
Route::resource('directories.forms.entries', 'FormEntryController', [
    'only' => ['index', 'show']
]);

// directories/{id}/forms/{id}/metrics
Route::resource('directories.forms.metrics', 'FormMetricController', [
    'only' => ['index']
]);

// directories/{id}/forms/{id}/tokens/search
Route::get(
    'directories/{directory}/forms/{form}/tokens/search',
    'FormEntryTokenController@search'
);

// directories/{id}/forms/{id}/entries/{id}/tokens
Route::get(
    'directories/{directory}/forms/{form}/entries/{entry}/tokens',
        'FormEntryTokenController@index'
);

// directories/{id}/forms/{id}/entries/{id}/tokens/open
Route::post(
    'directories/{directory}/forms/{form}/entries/{entry}/tokens/open',
    'FormEntryTokenController@open'
);

// directories/{id}/forms/{id}/entries/{id}/tokens/{id}/close
Route::put(
    'directories/{directory}/forms/{form}/entries/{entry}/tokens/{token}/close',
    'FormEntryTokenController@close'
);

// directories/{id}/forms/{id}/entries/{id}/listings/{id}
Route::get(
    'directories/{directory}/forms/{form}/entries/{entry}/listings/{listing}',
    'ListingController@show'
);

// form-entry-statuses
Route::resource('form-entry-statuses', 'FormEntryStatusController', [
    'only' => ['index']
]);

// users
Route::post(
    'users/is-username-unique',
    'UserController@isUsernameUnique'
);
Route::post(
    'users/is-email-unique',
    'UserController@isEmailUnique'
);

// users/{id}
Route::resource('users', 'UserController', [
    'only' => ['index', 'show', 'store', 'update', 'destroy']
]);

// login
Route::post(
    'login',
    'LoginController@login'
);
// ping
Route::get(
    'ping',
    'LoginController@ping'
);
// logout
Route::post(
    'logout',
    'LoginController@logout'
);
