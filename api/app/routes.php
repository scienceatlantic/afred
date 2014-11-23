<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::resource('facility', 'FacilityController',
		array('only' => array('index', 'store', 'show', 'update', 'destroy')));
Route::resource('facility.contacts', 'FacilityContactController',
		array('only' => 'index'));
Route::resource('facility.equipment', 'FacilityEquipmentController',
		array('only' => array('index', 'show')));
Route::resource('equipment', 'EquipmentController',
		array('only' => array('index', 'show')));