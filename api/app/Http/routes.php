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

Route::resource('institutions', 'InstitutionController', [
	'only' => ['index', 'store', 'show', 'update', 'destroy']]);

Route::resource('provinces', 'ProvinceController', [
	'only' => ['index']]);
		  
Route::resource('facilities', 'FacilityController', [
	'only' => ['index', 'store', 'show', 'update', 'destroy']]);

Route::resource('facilities.contacts', 'FacilityContactController', [
	'only' => ['index']]);

Route::resource('facilities.equipment', 'FacilityEquipmentController', [
	'only' => ['index', 'show']]);

Route::resource('equipment', 'EquipmentController', [
	'only' => ['index', 'show']]);
