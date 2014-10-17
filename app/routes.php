<?php
require_once(dirname(__FILE__) . '/validators.php');

Route::pattern('post_id', '[0-9]+');
Route::pattern('post_title', '[a-zA-Z0-9_-]+');
Route::pattern('section_titles', '[a-zA-Z0-9_-\n]+');

Route::get('/', 'SectionController@get');
Route::get('/spreadits', 'SectionController@getSpreadits');
Route::get('/spreadits/.json', 'SectionController@getSpreaditsJson');
Route::get('/.rss', 'FeedController@rss');
Route::get('/.atom', 'FeedController@atom');

Route::get('/about', 'PageController@about');
Route::get('/contact', 'PageController@contact');
Route::get('/threats', 'PageController@threats');
Route::get('/login', 'PageController@login');
Route::post('/login', 'UserController@login');

Route::any('/logout', ['before' => 'auth', 'uses' => 'UserController@logout']);

Route::post('/register', 'UserController@register');

Route::get('/notifications', ['before' => 'auth', 'uses' => 'UserController@notifications']);
Route::get('/notifications/.json', ['before' => 'auth.token', 'uses' => 'UserController@notificationsJson']);

Route::get('/preferences', ['before' => 'auth', 'uses' => 'UserController@preferences']);
Route::post('/preferences', ['before' => 'auth', 'uses' => 'UserController@savePreferences']);
Route::get('/preferences/.json', ['before' => 'auth.token', 'uses' => 'UserController@preferencesJson']);

Route::group(['prefix' => '/s'], function()
{
    Route::get('/all', 'SectionController@get');

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
        Route::post('/posts/{post_id}/{post_title?}', 'CommentController@post');

	    Route::get('/add', 'SectionController@add');
	    Route::post('/add', 'PostController@post');
        Route::post('/add/.json', ['before' => 'auth.token', 'uses' => 'PostController@postJson']);
    });
});

Route::group(['prefix' => '/util'], function()
{
    Route::get('/imagewrapper', 'UtilityController@imagewrapper');
    Route::get('/titlefromurl', 'UtilityController@titlefromurl');
    Route::post('/preview', 'UtilityController@preview');
    Route::get('/thumbnail', 'UtilityController@thumbnail');
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

Route::group(['prefix' => '/comments'], function()
{
    Route::get('/pre/{post_id}/{parent_id}',  'CommentController@preReply');
    Route::get('/cur/{post_id}/{parent_id}',  'CommentController@curReply');
    Route::get('/post/{post_id}/{parent_id}', 'CommentController@postReply');

    Route::group(['prefix' => '/{comment_id}'], function($comment_id)
    {
        Route::get('/',        'CommentController@getRedir');
    	Route::post('/create', 'CommentController@make');
    	Route::post('/update', 'CommentController@update');
        Route::post('/delete', 'CommentController@delete');
    });
});

Route::group(['prefix' => '/posts/{post_id}'], function($post_id)
{
    Route::get('/', 'PostController@getRedir');
    Route::post('/update', ['before' => 'auth', 'uses' => 'PostController@update']);
    Route::post('/delete', ['before' => 'auth', 'uses' => 'PostController@delete']);

    Route::post('/tag/nsfw', ['before' => 'auth', 'uses' => 'TagController@nsfw']);
    Route::post('/tag/sfw',  ['before' => 'auth', 'uses' => 'TagController@sfw']);
    Route::post('/tag/nsfl', ['before' => 'auth', 'uses' => 'TagController@nsfl']);
    Route::post('/tag/sfl',  ['before' => 'auth', 'uses' => 'TagController@sfl']);
    Route::post('/tag/nsfw/.json', ['before' => 'auth', 'uses' => 'TagController@nsfwJson']);
    Route::post('/tag/sfw/.json',  ['before' => 'auth', 'uses' => 'TagController@sfwJson']);
    Route::post('/tag/nsfl/.json', ['before' => 'auth', 'uses' => 'TagController@nsflJson']);
    Route::post('/tag/sfl/.json',  ['before' => 'auth', 'uses' => 'TagController@sflJson']);
});


Route::group(['prefix' => 'vote'], function()
{
    Route::group(['before' => 'auth'], function()
    {
        //Route::get('/section/{id}',     'VoteController@sectionView'); //TODO
        Route::get('/section/{id}/up',    'VoteController@sectionUp');
        Route::get('/section/{id}/down',  'VoteController@sectionDown');
        Route::post('/section/{id}/up/.json',   'VoteController@sectionUpJson');
        Route::post('/section/{id}/down/.json', 'VoteController@sectionDownJson');

        Route::get('/post/{id}',          'VoteController@postView');
        Route::get('/post/{id}/.json',    'VoteController@postJson');
        Route::get('/post/{id}/up',       'VoteController@postUp');
        Route::get('/post/{id}/down',     'VoteController@postDown');
        Route::post('/post/{id}/up/.json',      'VoteController@postUpJson');
        Route::post('/post/{id}/down/.json',    'VoteController@postDownJson');

        Route::get('/comment/{id}',       'VoteController@commentView');
        Route::get('/comment/{id}/.json', 'VoteController@commentJson');
        Route::get('/comment/{id}/up',    'VoteController@commentUp');
        Route::get('/comment/{id}/down',  'VoteController@commentDown');
        Route::post('/comment/{id}/up/.json',   'VoteController@commentUpJson');
        Route::post('/comment/{id}/down/.json', 'VoteController@commentDownJson');
	});

});

Route::group(['prefix' => '/api'], function()
{
	Route::get('/', 'SwaggerController@index');
	Route::get('/terms', 'SwaggerController@terms');
	Route::get('/license', 'SwaggerController@license');
	Route::get('/routes', 'SwaggerController@routes');
	Route::get('/routes/{type}', 'SwaggerController@getRoute');

    Route::group(['prefix' => '/auth'], function()
    {
        Route::get('/.json',   'Tappleby\AuthToken\AuthTokenController@index');
        Route::post('/.json',  'Tappleby\AuthToken\AuthTokenController@store');
        Route::delete('.json', 'Tappleby\AuthToken\AuthTokenController@destroy');
    });

    Route::group(['before' => 'auth.token', 'prefix' => 'vote'], function()
    {
        Route::post('/section/{id}/up/.json',   'VoteController@sectionUpJson');
        Route::post('/section/{id}/down/.json', 'VoteController@sectionDownJson');

        Route::post('/post/{id}/up/.json',      'VoteController@postUpJson');
        Route::post('/post/{id}/down/.json',    'VoteController@postDownJson');
        
        Route::post('/comment/{id}/up/.json',   'VoteController@commentUpJson');
        Route::post('/comment/{id}/down/.json', 'VoteController@commentDownJson');
	});
});

Route::group(['prefix' => '/help'], function()
{
	Route::get('/', 'HelpController@index');
	Route::get('/feeds', 'HelpController@feeds');
	Route::get('/posting', 'HelpController@posting');
	Route::get('/formatting', 'HelpController@formatting');
	Route::get('/points', 'HelpController@points');
	Route::get('/moderation', 'HelpController@moderation');
	Route::get('/anonymity', 'HelpController@anonymity');
	Route::get('/help', 'HelpController@help');
});

Route::group(['prefix' => '/color'], function()
{
    Route::get('/', 'ColorSchemeController@index');
    Route::get('/dark', 'ColorSchemeController@dark');
    Route::get('/light', 'ColorSchemeController@light');
    Route::get('/tiles', 'ColorSchemeController@tiles');
});

Route::get('/assets/prod/{filename}', function($filename) {
    return Bust::css("/assets/prod/$filename");
});
Route::get('/assets/css/colorschemes/{filename}', function($filename) {
    return Bust::css("/assets/css/colorschemes/$filename");
});
Route::get('/assets/css/prefs/{filename}', function($filename) {
    return Bust::css("/assets/css/prefs/$filename");
});
App::make('cachebuster.StripSessionCookiesFilter')->addPattern('|css/|');

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

Event::listen('auth.token.valid', function($user)
{
  Auth::setUser($user);
});

App::error(function(AuthTokenNotAuthorizedException $exception) {
    return Response::json(array('error' => $exception->getMessage()), $exception->getCode());
});
