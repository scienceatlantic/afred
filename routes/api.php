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

// directories/{id}/forms/{id}/reports/{id}/generate
Route::post(
    'directories/{directory}/forms/{form}/reports/{report}/generate',
    'FormReportController@generate'
)->name('directories.forms.reports.generate');

// directories/{id}/forms/{id}/reports
Route::resource('directories.forms.reports', 'FormReportController', [
    'only' => ['index']
]);

// directories/{id}/forms/{id}/search-sections
Route::resource('directories.forms.search-sections', 'SearchSectionController', [
    'only' => ['index']
]);

// directories/{id}/forms/{id}/entries
Route::post(
    'directories/{directory}/forms/{form}/entries',
    'FormEntryController@action'
)->name('directories.forms.entries.store');

// directories/{id}/forms/{id}/entries/{id}
Route::put(
    'directories/{directory}/forms/{form}/entries/{entry}',
    'FormEntryController@action'
)->name('directories.forms.entries.update');

Route::delete(
    'directories/{directory}/forms/{form}/entries/{entry}',
    'FormEntryController@action'
)->name('directories.forms.entries.destroy');

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
)->name('directories.forms.tokens.search');

// directories/{id}/forms/{id}/entries/{id}/tokens/open
Route::post(
    'directories/{directory}/forms/{form}/entries/{entry}/tokens/open',
    'FormEntryTokenController@open'
)->name('directories.forms.entries.tokens.open');

// directories/{id}/forms/{id}/entries/{id}/tokens/{id}/close
Route::put(
    'directories/{directory}/forms/{form}/entries/{entry}/tokens/{token}/close',
    'FormEntryTokenController@close'
)->name('directories.forms.entries.tokens.close');

// directories/{id}/forms/{id}/entries/{id}/tokens
Route::resource('directories.forms.entries.tokens', 'FormEntryTokenController', [
    'only' => ['index']
]);

// directories/{id}/forms/{id}/entries/{id}/listings/{id}
Route::resource(
    'directories.forms.entries.listings',
    'ListingController',
    ['only' => ['show']]
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
    'only' => ['store', 'update', 'destroy']
]);

// login
Route::post(
    'login',
    'LoginController@login'
)->name('login');

// ping
Route::get(
    'ping',
    'LoginController@ping'
)->name('ping');

// logout
Route::post(
    'logout',
    'LoginController@logout'
)->name('logout');
