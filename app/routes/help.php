<?php
/***********************************************
 *
 * HELP PAGES
 *
 **********************************************/
Route::group(['prefix' => '/help'], function()
{
	Route::get('/', function()
	{
	        return;
        });
	Route::get('/markdown', function()
	{
	        return;
        });
	Route::get('/voting', function()
	{
	        return;
        });
});

