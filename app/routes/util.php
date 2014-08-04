<?php
/***********************************************
 *
 * UTILITY PAGES
 *
 **********************************************/
Route::group(['prefix' => '/util'], function()
{
    Route::get('/imagewrapper', function()
    {
        return View::make('imagewrapper');
    });
});

Route::any('/generate_view', function()
{
    $allowed_views = ['commentreplybox', 'commentsourcebox', 'commenteditbox', 'postreplybox', 'postsourcebox', 'posteditbox'];
    if(!in_array(Input::get('view'), $allowed_views)) return "not allowed";
    return View::make(Input::get('view'), Input::all());
});

Route::get('/titlefromurl', function()
{
	if(!Input::has('url')) {
		return "url not given";
	}

	return UtilController::titleFromUrl(Input::get('url'));
});
