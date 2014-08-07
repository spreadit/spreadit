<?php
Route::pattern('post_id', '[0-9]+');
Route::pattern('post_title', '[a-zA-Z0-9_-]+');
Route::pattern('section_title', '[a-zA-Z0-9_-]+');

Route::get('/about', 'PageController@about');
Route::get('/contact', 'PageController@contact');
Route::get('/threats', 'PageController@threats');
Route::get('/login', 'PageController@login');

Route::any('/logout', ['before' => 'auth', 'uses' => 'UserController@logout']);
Route::post('/login', ['before' => 'csrf', 'uses' => 'UserController@login']);
Route::post('/register', ['before' => 'csrf', 'uses' => 'UserController@register']);
Route::get('/notifications', ['before' => 'auth', 'uses' => 'UserController@notifications']);
Route::get('/notifications/.json', ['before' => 'auth', 'uses' => 'UserController@notificationsJson']);
Route::get('/unotifications', ['before' => 'auth', 'uses' => 'UserController@unreadNotifications']);

Route::get('/', 'SectionController@get');
Route::get('/.rss', 'FeedController@rss');
Route::get('/.atom', 'FeedController@atom');

Route::group(['prefix' => '/s'], function()
{
	Route::group(['prefix' => '/{section_title}'], function($section_title)
	{
		Route::get('/', 'SectionController@get');
        Route::get('/.rss', 'FeedController@rss'); 
        Route::get('/.atom', 'FeedController@atom'); 

		Route::get('/hot', 'SectionController@hot');
		Route::get('/new', 'SectionController@new_');
		Route::get('/top/{timeframe}', 'SectionController@top');
		Route::get('/controversial/{timeframe}', 'SectionController@controversial');
        
        Route::get('/posts/{post_id}/{post_title?}', 'PostController@get');
        Route::post('/posts/{post_id}/{post_title?}', ['before' => 'auth', 'uses' =>'CommentController@post']);

	    Route::get('/add', ['before' => 'auth', 'uses' =>'SectionController@add']);
	    Route::post('/add', ['before' => 'auth|csrf', 'uses' => 'PostController@post']);
    });
});
Route::any('/update_post/{post_id}', ['before' => 'auth', 'uses' => 'PostController@update']);

Route::group(['prefix' => '/util'], function()
{
    Route::get('/imagewrapper', 'UtilityController@imagewrapper');
    Route::get('/titlefromurl', 'UtilityController@titlefromurl');
    Route::post('/preview', 'UtilityController@preview');
    Route::get('/generate_view/{view}', 'UtilityController@generate_view');
});


Route::group(['prefix' => '/u/{username}'], function($username)
{
    Route::get('/', 'UserController@mainVote');
    Route::get('/comments', 'UserController@comments');
    Route::get('/posts', 'UserController@posts');
    Route::get('/votes/comments', 'UserController@commentsVotes');
    Route::get('/votes/posts', 'UserController@postsVotes');
});

Route::group(['prefix' => '/comments/{comment_id}'], function($comment_id)
{
    Route::get('/', 'CommentController@get');
	Route::get('/source', 'CommentController@getSourceFromId');
	Route::any('/update', 'CommentController@update');
});

Route::group(['prefix' => 'vote', 'before' => 'auth'], function()
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

Route::group(['prefix' => '/api'], function()
{
	Route::get('/', 'SwaggerController@index');
	Route::get('/license', 'SwaggerController@license');
	Route::get('/routes', 'SwaggerController@routes');
	Route::get('/routes/{type}', 'SwaggerController@getRoute');
});

Route::group(['prefix' => '.json'], function()
{
	Route::get('/', function()
	{
		$json = SectionController::get();
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

App::missing(function(Exception $exception)
{
	$message = $exception->getMessage();
	if(Request::is('.json/*')) {
		return Response::make(json_encode(['error' => $message]), 404)->header('Content-Type', 'application/json');
	}
	$sections = Section::get();

	return View::make('404', [
		'message' => $message,
		'sections' => $sections
	]);
});
