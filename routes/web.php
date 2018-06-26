<?php
Route::get('/', function () {
	return view('auth.login');
});

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
	Route::get('/home', 'HomeController@index');

	Route::get('statemaster/getData', 'StateController@getData');
	Route::resource("statemaster", "StateController");

	Route::get('lgamaster/getData', 'LgaController@getData');
	Route::resource("lgamaster", "LgaController");

	Route::get('wardmaster/getData', 'WardController@getData');
	Route::resource("wardmaster", "WardController");

	Route::get('categorymaster/getData', 'CategoryController@getData');
	Route::resource("categorymaster", "CategoryController");

	Route::get('gsmmaster/getData', 'GsmController@getData');
	Route::resource("gsmmaster", "GsmController");

	Route::get('payment/getData', 'PaymentController@getData');
	Route::get('payment/payrecord', 'PaymentController@payrecord');
	Route::post('payment/payrecordsave', 'PaymentController@payrecordsave');
	Route::get('payment/GetPayList', 'PaymentController@GetPayList');
	Route::get('payment/exportexcel', 'PaymentController@exportexcel');
	Route::resource("payment", "PaymentController");

	Route::get('Helpers/CommonClass/lgsist/{id}', function ($state_id) {
    	return CommonClass::LGAList($state_id);
	});
	Route::get('Helpers/CommonClass/wardlist/{id}', function ($lga_id) {
	    return CommonClass::WardList($lga_id);
	});
});