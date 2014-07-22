<?php
/***********************************************
 *
 * ERROR HANDLING
 *
 **********************************************/
App::missing(function(Exception $exception)
{
	$message = $exception->getMessage();
	if(Request::is('.json/*')) {
		return Response::make(json_encode(['error' => $message]), 404)->header('Content-Type', 'application/json');
	}
	$sections = SectionController::get();

	return View::make('404', [
		'message' => $message,
		'sections' => $sections
	]);
});
