<?php
/***********************************************
 *
 * COMMENT FUNCTIONS
 *
 **********************************************/
Route::group(['prefix' => '/comments/{comment_id}'], function($comment_id)
{
	//todo go to specific comment tree
	Route::get('/', function($comment_id)
	{
		$data = CommentController::getPathDataFromId($comment_id);
		return Redirect::to('/s/'.$data->section_title.'/posts/'.$data->post_id.'#comment_'.$comment_id);
	});

	Route::get('/source', function($comment_id)
	{
		return CommentController::getSourceFromId($comment_id);
	});

	Route::any('/update', function($comment_id)
	{
		return CommentController::update($comment_id);
	});
});


