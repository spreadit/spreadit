<?php
/***********************************************
 *
 * SWAGGER API DEMO
 *
 **********************************************/
Route::group(['prefix' => '/api'], function()
{
	Route::get('/', function()
	{
		return View::make('swagger/index');
	});

	Route::get('/license', function()
	{
		return View::make('swagger/license');
	});
	
	Route::group(['prefix' => '/routes'], function()
	{
		Route::get('/', function()
		{
			return Response::view('swagger/json/routes')->header('Content-Type', 'application/json');
		});

		Route::get('/{type}', function($type)
		{
			return Response::view("swagger/json/$type")->header('Content-Type', 'application/json');
		});
	});
});

