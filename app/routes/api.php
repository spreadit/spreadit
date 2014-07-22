<?php
/***********************************************
 *
 * JSON API
 *
 **********************************************/
Route::group(['prefix' => '.json'], function()
{
	Route::get('/', function()
	{
		$json = SectionController::get();
		return Response::make($json)->header('Content-Type', 'application/json');
	});

	Route::get('/notifications', function() {
		$json = json_encode(iterator_to_array(NotificationController::get()));
		NotificationController::markAllAsRead();
		return Response::make($json)->header('Content-Type', 'application/json');
	});

	Route::get('/s/{section_title}', function($section_title)
	{
		$json = json_encode(iterator_to_array(PostController::getNewList(SectionController::getId($section_title), $section_title)));
		return Response::make($json)->header('Content-Type', 'application/json');
	});

	Route::get('/s/{section_title}/posts/{post_id}/{post_title?}', function($section_title, $post_id)
	{
		$post = PostController::get($post_id);
		$my_votes = VoteController::getMatchingVotes(VoteController::POST_TYPE, [$post]);
		$post->selected = isset($my_votes[$post_id]) ? $my_votes[$post_id] : 0;

		$json = json_encode([
			'post' => $post,
			'comments' => CommentController::getNewList($post_id),
		]);

		return Response::make($json)->header('Content-Type', 'application/json');
	});

    
	Route::group(['prefix' => 'vote'], function()
	{
        //Route::get('/section/{id}', function($id) {
         //   $json = VoteController::getSectionVotes($id);
		  //  return Response::make($json)->header('Content-Type', 'application/json');
        //});

        Route::get('/post/{id}', function($id) {
            $json = VoteController::getPostVotes($id);
		    return Response::make($json)->header('Content-Type', 'application/json');
        });
        
        Route::get('/comment/{id}', function($id) {
            $json = VoteController::getCommentVotes($id);
		    return Response::make($json)->header('Content-Type', 'application/json');
        });
    });
});


