<?php
require_once(dirname(__FILE__) . '/validators.php');

Route::pattern('post_id', '[0-9]+');
Route::pattern('post_title', '[a-zA-Z0-9_-]+');
Route::pattern('section_title', '[a-zA-Z0-9_-]+');

Route::get('/', 'SectionController@get');
Route::get('/.json', 'SectionController@getSpreaditsJson');
Route::get('/.rss', 'FeedController@rss');
Route::get('/.atom', 'FeedController@atom');

Route::get('/about', 'PageController@about');
Route::get('/contact', 'PageController@contact');
Route::get('/threats', 'PageController@threats');
Route::get('/login', 'PageController@login');
Route::post('/login', ['before' => 'csrf', 'uses' => 'UserController@login']);

Route::any('/logout', ['before' => 'auth', 'uses' => 'UserController@logout']);

Route::post('/register', ['before' => 'csrf', 'uses' => 'UserController@register']);

Route::get('/notifications', ['before' => 'auth', 'uses' => 'UserController@notifications']);
Route::get('/notifications/.json', ['before' => 'auth', 'uses' => 'UserController@notificationsJson']);

Route::get('/preferences', ['before' => 'auth', 'uses' => 'UserController@preferences']);
Route::get('/preferences/.json', ['before' => 'auth', 'uses' => 'UserController@preferencesJson']);

Route::group(['prefix' => '/s'], function()
{
	Route::group(['prefix' => '/{section_title}'], function($section_title)
	{
		Route::get('/', 'SectionController@get');
        Route::get('/.json', 'SectionController@getJson');
        Route::get('/.rss', 'FeedController@rss'); 
        Route::get('/.atom', 'FeedController@atom'); 

		Route::get('/hot', 'SectionController@hot');
		Route::get('/hot/.json', 'SectionController@hotJson');
		Route::get('/new', 'SectionController@new_');
		Route::get('/new/.json', 'SectionController@new_Json');
		Route::get('/top/{timeframe}', 'SectionController@top');
		Route::get('/top/{timeframe}/.json', 'SectionController@topJson');
		Route::get('/controversial/{timeframe}', 'SectionController@controversial');
		Route::get('/controversial/{timeframe}/.json', 'SectionController@controversialJson');
        
        Route::get('/posts/{post_id}/{post_title?}', 'PostController@get');
    	Route::get('/posts/{post_id}/.json', 'PostController@getJson');
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
    Route::get('/comments/.json', 'UserController@commentsJson');
    Route::get('/votes/comments', 'UserController@commentsVotes');
    Route::get('/votes/comments/.json', 'UserController@commentsVotesJson');
    Route::get('/posts', 'UserController@posts');
    Route::get('/posts/.json', 'UserController@postsJson');
    Route::get('/votes/posts', 'UserController@postsVotes');
    Route::get('/votes/posts/.json', 'UserController@postsVotesJson');
});

Route::group(['prefix' => '/comments/{comment_id}'], function($comment_id)
{
    Route::get('/', 'CommentController@get');
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
		Route::get('/{id}/.json', 'VoteController@postJson');
		Route::post('/{id}/up',   'VoteController@postUp');
		Route::post('/{id}/down', 'VoteController@postDown');
	});

	Route::group(['prefix' => 'comment'], function()
	{
		Route::get('/{id}',       'VoteController@commentView');
		Route::get('/{id}/.json', 'VoteController@commentJson');
		Route::post('/{id}/up',   'VoteController@commentUp');
		Route::post('/{id}/down', 'VoteController@commentDown');
	});
});

Route::group(['prefix' => '/api'], function()
{
	Route::get('/', 'SwaggerController@index');
	Route::get('/terms', 'SwaggerController@terms');
	Route::get('/license', 'SwaggerController@license');
	Route::get('/routes', 'SwaggerController@routes');
	Route::get('/routes/{type}', 'SwaggerController@getRoute');
});

App::missing(function(Exception $exception)
{
    if(Request::is('*/.json')) {
		return Response::json(['error' => 'not found'], 404);
	}

	return View::make('404', [
		'message' => $exception->getMessage(),
		'sections' => Section::get()
	]);
});
