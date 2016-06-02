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
 * Organization routes.
 *****************************************************************************/
Route::resource('organizations', 'OrganizationController', [
	'only' => ['index',
			   'store',
			   'show',
			   'update',
			   'destroy']]);

               
/******************************************************************************
 * ILO routes.
 *****************************************************************************/
Route::resource('ilos', 'IloController', [
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
			   'store',
               'show',
               'update',
               'destroy']]);

               
/******************************************************************************
 * Discipline routes.
 *****************************************************************************/
Route::resource('disciplines', 'DisciplineController', [
	'only' => ['index',
			   'store',
               'show',
               'update',
               'destroy']]);

    
/******************************************************************************
 * Sector routes.
 *****************************************************************************/
Route::resource('sectors', 'SectorController', [
	'only' => ['index',
			   'store',
               'show',
               'update',
               'destroy']]);

			   
/******************************************************************************
 * Facility routes.
 *****************************************************************************/	  
Route::resource('facilities', 'FacilityController', [
	'only' => ['index',
			   'show',
			   'update',
			   'destroy']]);

			   
/******************************************************************************
 * Facility repository routes.
 *****************************************************************************/
 
// Merging the functions of 'store' and 'update' into 'update'.
Route::post('facility-repository', 'FacilityRepositoryController@update');

Route::resource('facility-repository', 'FacilityRepositoryController', [
	'only' => ['index',
			   'show',
			   'update',
			   'destroy']]);

               
/******************************************************************************
 * Facility update link routes.
 *****************************************************************************/
 Route::resource('facility-update-links', 'FacilityUpdateLinkController', [
	'only' => ['index',
               'store',
               'update',
               'destroy']]);


/******************************************************************************
 * Search routes
 *****************************************************************************/
Route::get('search/','SearchController@index');

/******************************************************************************
 * Dashboard routes.
 *****************************************************************************/
Route::get('dashboard/', 'DashboardController@index');