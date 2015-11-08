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

Route::resource('institutions', 'InstitutionController',
    array('only' => array('index', 'store', 'show', 'update', 'destroy'))
);

Route::resource('facilities', 'FacilityController',
	array('only' => array('index', 'store', 'show', 'update', 'destroy'))
);

Route::resource('facilities.contacts', 'FacilityContactController',
	array('only' => 'index')
);

Route::resource('facilities.equipment', 'FacilityEquipmentController',
	array('only' => array('index', 'show'))
);

Route::resource('equipment', 'EquipmentController',
	array('only' => array('index', 'show'))
);    
