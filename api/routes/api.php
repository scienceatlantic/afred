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
 * User routes.
 *****************************************************************************/	  
Route::resource('users', 'UserController', [
	'only' => ['index',
			   		 'show',
			   		 'store',
			   		 'update',
			   		 'destroy']]);

/******************************************************************************
 * Role routes.
 *****************************************************************************/	  
Route::resource('roles', 'RoleController', [
	'only' => ['index']]);

			   
/******************************************************************************
 * Facility repository routes.
 *****************************************************************************/
 
// Merging the functions of 'store' and 'update' into 'update'.
Route::post('facility-repository', 'FacilityRepositoryController@update');

Route::resource('facility-repository', 'FacilityRepositoryController', [
	'only' => ['index',
			   		 'show',
			   		 'update']]);

               
/******************************************************************************
 * Setting routes.
 *****************************************************************************/
 Route::resource('settings', 'SettingController', [
	'only' => ['index',
             'update']]);


/******************************************************************************
 * Facility update link routes.
 *****************************************************************************/
 Route::resource('facility-update-links', 'FacilityUpdateLinkController', [
	'only' => ['index',
             'store',
             'update',
             'destroy']]);


/******************************************************************************
 * Emails routes.
 *****************************************************************************/
Route::post('email/', 'EmailController@store');


/******************************************************************************
 * Misc routes.
 *****************************************************************************/
Route::get('reports/', 'ReportController@index');


/******************************************************************************
 * Misc routes.
 *****************************************************************************/
Route::get('misc/', 'MiscController@index');
