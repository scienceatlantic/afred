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

Route:get('csrf', 'CsrfController@show');

/******************************************************************************
 * Authentication routes.
 *****************************************************************************/
// Log in.
Route::post('auth/login', 'AuthController@login');

// Check if user is logged in.
Route::get('auth/ping', 'AuthController@ping');

// Logout.
Route::get('auth/logout', 'AuthController@logout'); 


/******************************************************************************
 * Facility edit request routes.
 *****************************************************************************/
// Returns a list of facilities that have contacts (primary or regular)
// matching the email address provided.
Route::get('facility-update-links',
		   'FacilityUpdateLinkController@indexMatchingFacilities');

// Generates an edit 'token' that will be emailed to the requesting user.
Route::post('facility-update-links',
			'FacilityUpdateLinkController@generateToken');

// TODO
Route::put('facility-update-links',
		   'FacilityUpdateLinkController@verifyToken');

			
/******************************************************************************
 * Institution routes.
 *****************************************************************************/
Route::resource('organizations', 'OrganizationController', [
	'only' => ['index',
			   'store',
			   'show',
			   'update',
			   'destroy']]);

			   
/******************************************************************************
 * Province routes.
 *****************************************************************************/
Route::resource('provinces', 'ProvinceController', [
	'only' => ['index',
			   'show']]);

			   
/******************************************************************************
 * Facility routes.
 *****************************************************************************/	  
Route::resource('facilities', 'FacilityController', [
	'only' => ['index',
			   'store',
			   'show',
			   'update',
			   'destroy']]);

			   
/******************************************************************************
 * Facility revision history routes.
 *****************************************************************************/
// Merging the functions of 'store' and 'update' into 'update'.
Route::post('facility-repository',
	'FacilityRepositoryController@update');

Route::resource('facility-repository',
	'FacilityRepositoryController', [
	'only' => ['index',
			   'show',
			   'update',
			   'destroy']]);
