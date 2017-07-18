<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/password/new', 'AuthController@newPassword');
Route::post('/password/new', 'AuthController@genPassword');

// REST API PARTS
Route::group(['prefix' => '/api/v1'], function () {

  Route::get('/getUpdates', 'BoxesController@getUpdates');
  Route::get('/uploadScanData', 'ClientsController@uploadScanData');
  Route::get('/uploadUserData', 'ClientsController@uploadUserData');
  Route::get('/uploadAPData', 'APController@uploadAPData');
  Route::post('/uploadAPData', 'APController@uploadAPData');
  Route::post('/test', 'APController@test');
});

// LARAVEL SOCIALITE AUTHENTICATION ROUTES
Route::get('/login/social/handle/{provider}', [
	'as' 	=> 'social.handle',
	'uses' 	=> 'GuestsController@getSocialHandle'
]);

Route::get('/login/social/{id}/{provider}', [
	'as' 	=> 'social.redirect',
	'uses' 	=> 'GuestsController@getSocialRedirect'
]);

Route::get('/login', 'UsersController@login');

Route::post('/login', 'AuthController@login');

// ROUTE FOR SPLASH PAGES
Route::get('/{id}', 'GuestsController@login')->where('id', '[0-9]+');
Route::post('/{id}', 'GuestsController@doLogin')->where('id', '[0-9]+');

//Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/token', 'UsersController@getToken');

Route::group(['middleware' => 'auth'], function () {

  Route::get('/logout', 'AuthController@logout');

  // ROUTE FOR USERS
  Route::resource('/users/me', 'UsersController@me');
  Route::resource('/users', 'UsersController');

  // ROUTE FOR INVITES
  Route::patch('/invites', 'InvitesController@patch');
  Route::post('/invites', 'InvitesController@store');

  // ROUTE FOR LOCATIONS
  Route::resource('/locations', 'LocationsController');
  Route::get('/locations/{id}/clients', 'LocationsController@clients');
  Route::get('/locations/{id}/splash_codes', 'LocationsController@splash_codes');
  Route::get('/locations/{id}/users', 'InvitesController@index');
  Route::post('/locations/{id}/invites', 'InvitesController@store');
  Route::delete('/locations/{id}/invites', 'InvitesController@destroy2');

  // ROUTE FOR NETWORKS
  Route::resource('/locations/{location_id}/networks', 'NetworksController');

  // ROUTE FOR SPLASH PAGES
  Route::resource('/locations/{location_id}/splash_pages', 'SplashPagesController');

  // ROUTE FOR REGISTRATION FORMS
  Route::resource('/locations/{location_id}/registration_forms', 'RegistrationFormsController');

  // ROUTE FOR VOUCHERS
  Route::resource('/locations/{location_id}/vouchers', 'VouchersController');
  Route::get('/locations/{location_id}/vouchers/{id}/pdf', 'VouchersController@getCodePDF')->name('vouchers.getCodePDF');
  Route::get('/locations/{location_id}/vouchers/{id}/csv', 'VouchersController@getCodeCSV')->name('vouchers.getCodeCSV');

  // ROUTE FOR VOUCHER CODES
  Route::resource('/locations/{location_id}/vouchers/{voucher_id}/codes', 'VoucherCodesController');
  Route::patch('/locations/{location_id}/clients/{client_id}/codes/{code_id}', 'VoucherCodesController@update');

  // ROUTE FOR ZONES
  Route::resource('/locations/{location_id}/zones', 'ZonesController');

  // ROUTE FOR GROUP_POLICIES
  Route::resource('/locations/{location_id}/group_policies', 'GroupPoliciesController');

  // ROUTE FOR ZONES
  Route::resource('/locations/{location_id}/versions', 'VersionsController');

  // ROUTE FOR TRIGGERS
  Route::resource('/{category}/{category_id}/triggers', 'TriggersController');

  // ROUTE FOR TRIGGER HISTORIES
  Route::resource('/{category}/{category_id}/triggers/{trigger_id}/trigger_history', 'TriggerHistoriesController');

  // ROUTE FOR REPORTS
  Route::resource('/reports', 'ReportsController');

  // ROUTE FOR EVENTS
  Route::resource('/events', 'EventsController');

  // ROUTE FOR GUESTS
  Route::resource('/guests', 'GuestsController');

  // ROUTE FOR BRANDS
  Route::resource('/brands', 'BrandsController');
  Route::get('/brands/{id}/projects', 'BrandsController@projects');

  // ROUTE FOR DEVICE TYPES
  Route::resource('/device-types', 'DeviceTypesController');

  // ROUTE FOR PROJECTS
  Route::resource('/projects', 'ProjectsController');

  // ROUTE FOR SOCIAL
  Route::resource('/social', 'SocialController');

  // ROUTE FOR BOXES
  Route::resource('/boxes', 'BoxesController');

  // ROUTE FOR CLIENTS
  Route::resource('/clients', 'ClientsController');

  // ROUTE FOR SPLASH CODES
  Route::resource('/splash_codes', 'SplashCodesController');

  // ROUTE FOR INVENTORIES
  Route::resource('/inventories', 'InventoriesController');

  // ROUTE FOR OPERATIONS
  Route::resource('/boxes/{box_id}/operations', 'OperationsController');

});
