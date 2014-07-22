<?php
/***********************************************
 *
 * AUTHENTICATION WALLED GARDEN
 *
 **********************************************/
Route::group(['before' => 'auth'], function()
{
	Route::get('/notifications', function() {
		$sections = SectionController::get();

		$view = View::make('notifications', [
			'sections' => $sections,
			'notifications' => NotificationController::get()
		]);

		NotificationController::markAllAsRead();

		return $view;
	});

	Route::get('/unotifications', function() {
		$json = NotificationController::getUnread();
		return Response::make($json)->header('Content-Type', 'application/json');
	});


	Route::any('/logout', function()
	{
		Auth::logout();
	    return Redirect::to('/');
	});

	Route::get('/s/{section_title}/add', function($section_title)
	{
		$section_id = SectionController::getId($section_title);
		$sections = SectionController::get();

		return View::make('newpost', [
			'sections' => $sections,
			'title' => $section_title,
			'formurl' => '/s/'.$section_title.'/add'
		]);
	});

	Route::post('/s/{section_title}/add', ['before' => 'csrf', 'uses' => 'PostController@post']);

	Route::post('/s/{section_title}/posts/{post_id}/{post_title?}', function($section_title, $post_id)
	{
		return CommentController::post($post_id);
	});

    Route::any('/update_post/{post_id}', function($post_id)
    {
        return PostController::update($post_id);
    });

	Route::group(['prefix' => 'vote'], function()
	{
		Route::group(['prefix' => 'section'], function()
		{
			//Route::get('/{id}',     'VoteController@sectionView');
			Route::post('/{id}/up',   'VoteController@sectionUp');
			Route::post('/{id}/down', 'VoteController@sectionDown');
		});

		Route::group(['prefix' => 'post'], function()
		{
			Route::get('/{id}',       'VoteController@postView');
			Route::post('/{id}/up',   'VoteController@postUp');
			Route::post('/{id}/down', 'VoteController@postDown');
		});

		Route::group(['prefix' => 'comment'], function()
		{
			Route::get('/{id}',       'VoteController@commentView');
			Route::post('/{id}/up',   'VoteController@commentUp');
			Route::post('/{id}/down', 'VoteController@commentDown');
		});
	});
});



