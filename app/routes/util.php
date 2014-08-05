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

    Route::any('/generate_view', function()
    {
        $allowed_views = ['commentreplybox', 'commentsourcebox', 'commenteditbox', 'postreplybox', 'postsourcebox', 'posteditbox'];
        if(!in_array(Input::get('view'), $allowed_views)) return "not allowed";
        return View::make(Input::get('view'), Input::all());
    });

    Route::get('/titlefromurl', function()
    {
        $result = Input::has('url') ? UtilController::titleFromUrl(Input::get('url')) : "url not given";
        return Response::make(json_encode(['response' => $result]))->header('Content-Type', 'application/json');
    });
});
