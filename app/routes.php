<?php
(new ViewComposers())->init();

Route::pattern('post_id', '[0-9]+');
Route::pattern('post_title', '[a-zA-Z0-9_-]+');
Route::pattern('section_titles', '[a-zA-Z0-9_-\n]+');

Route::group(['domain' => '{username}.spreadit.{tld}'], function($username)
{
    Route::get('/style', 'UserPageController@css');
    Route::get('/',      'UserPageController@index');
});


Route::get('/', 'SectionController@getHtml');
Route::get('/.json', 'SectionController@getJson');
Route::get('/spreadits', 'SectionController@getSpreadits');
Route::get('/spreadits/.json', 'SectionController@getSpreaditsJson');
Route::get('/.rss', 'FeedController@rss');
Route::get('/.atom', 'FeedController@atom');

Route::get('/about',   'PageController@about');
Route::get('/contact', 'PageController@contact');
Route::get('/threats', 'PageController@threats');
Route::get('/login',   'PageController@login');
Route::post('/login',  ['before' => 'throttle:5,1', 'uses' => 'UserController@login']);

Route::any('/logout', ['before' => 'auth', 'uses' => 'UserController@logout']);

Route::post('/register', ['before' => 'throttle:5,10', 'uses' => 'UserController@register']);

Route::get('/notifications', ['before' => 'auth', 'uses' => 'UserController@notifications']);
Route::get('/notifications/.json', ['before' => 'auth.token', 'uses' => 'UserController@notificationsJson']);

Route::get('/search', 'SearchController@search');

if(Utility::enableRoute('preferences*')) {
    Route::group(['prefix' => '/preferences'], function()
    {
        Route::get('/',  ['before' => 'auth', 'uses' => 'PreferencesController@preferences']);
        Route::post('/', ['before' => 'auth|throttle:10,1', 'uses' => 'PreferencesController@savePreferences']);
        Route::get('/.json', ['before' => 'auth.token', 'uses' => 'PreferencesController@preferencesJson']);

        if(Utility::enableRoute('preferences/homepage*')) {
            Route::group(['prefix' => '/homepage'], function()
            {
                Route::get('/',  ['before' => 'auth', 'uses' => 'PreferencesController@homepage']);
                Route::post('/', ['before' => 'auth|throttle:10,1', 'uses' => 'PreferencesController@saveHomepage']);
            });
        }
        if(Utility::enableRoute('preferences/theme*')) {
            Route::group(['prefix' => '/theme'], function()
            {
                Route::get('/',      'PreferencesController@theme_index');
                Route::get('/dark',  'PreferencesController@theme_dark');
                Route::get('/light', 'PreferencesController@theme_light');
                Route::get('/tiles', 'PreferencesController@theme_tiles');
            });
        }
    });
}
if(Utility::enableRoute('s*')) {
    Route::group(['prefix' => '/s'], function()
    {
        Route::get('/all', 'SectionController@getHtml');

        Route::group(['prefix' => '/{section_title}'], function($section_title)
        {
            Route::get('/', 'SectionController@getHtml');
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
            Route::post('/posts/{post_id}/{post_title?}', ['before' => 'throttle:3,1', 'uses' => 'CommentController@post']);

            Route::get('/add', 'SectionController@add');
            Route::post('/add',       ['before' => 'throttle:3,1', 'uses' => 'PostController@create']);
            Route::post('/add/.json', ['before' => 'throttle:3,1', 'uses' => 'PostController@createJson']);
        });
    });
}
if(Utility::enableRoute('util*')) {
    Route::group(['prefix' => '/util'], function()
    {
        Route::get('/imagewrapper',   ['before' => 'throttle:2,1',   'uses' => 'UtilityController@imagewrapper']);
        Route::get('/titlefromurl',   ['before' => 'throttle:6000,1',   'uses' => 'UtilityController@titlefromurl']);
        Route::post('/preview',       ['before' => 'throttle:30,1',  'uses' => 'UtilityController@preview']);
        Route::post('/preview/.json', ['before' => 'throttle:30,1',  'uses' => 'UtilityController@previewNoEnclosingPage']);
        Route::get('/thumbnail',      ['before' => 'throttle:2,1',   'uses' => 'UtilityController@thumbnail']);
        Route::get('/captcha',        ['before' => 'throttle:20,1',  'uses' => 'UtilityController@newCaptcha']);
        Route::get('/redirect_to_add_post', 'UtilityController@redirect_to_add_post');
    });
}
if(Utility::enableRoute('u*')) {
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
}
if(Utility::enableRoute('comments*')) {
    Route::group(['prefix' => '/comments'], function()
    {
        Route::get('/pre/{post_id}/{parent_id}',   'CommentController@preReply');
        Route::get('/cur/{post_id}/{parent_id}',   'CommentController@curReply');
        Route::get('/after/{post_id}/{parent_id}', 'CommentController@afterReply');
        Route::get('/form/{post_id}/{parent_id}',  'CommentController@formReply');
        
        Route::group(['prefix' => '/{comment_id}'], function($comment_id)
        {
            Route::get('/',        'CommentController@getRedir');
            Route::get('/render',  'CommentController@render');
            Route::post('/create',       ['before' => 'throttle:2,1',  'uses' => 'CommentController@create']);
            Route::post('/create/.json', ['before' => 'throttle:2,1',  'uses' => 'CommentController@createJson']);
            Route::post('/update',       ['before' => 'throttle:10,1', 'uses' => 'CommentController@update']);
            Route::post('/update/.json', ['before' => 'throttle:10,1', 'uses' => 'CommentController@updateJson']);
            Route::post('/delete',       ['before' => 'throttle:5,1',  'uses' => 'CommentController@delete']);
        });
    });
}
if(Utility::enableRoute('posts*')) {
    Route::group(['prefix' => '/posts/{post_id}'], function($post_id)
    {
        Route::get('/', 'PostController@getRedir');
                
        Route::group(['before' => 'auth|throttle:5,1'], function() {
            Route::post('/update',       'PostController@update');
            Route::post('/update/.json', 'PostController@updateJson');
            Route::post('/delete',       'PostController@delete');
        });

        if(Utility::enableRoute('posts/tag*')) {
            Route::group(['prefix' => '/tag', 'before' => 'auth|throttle:2,1'], function() {
                Route::post('/nsfw',       'TagController@nsfw');
                Route::post('/nsfw/.json', 'TagController@nsfwJson');
                Route::post('/sfw',        'TagController@sfw');
                Route::post('/sfw/.json',  'TagController@sfwJson');
                Route::post('/nsfl',       'TagController@nsfl');
                Route::post('/nsfl/.json', 'TagController@nsflJson');
                Route::post('/sfl',        'TagController@sfl');
                Route::post('/sfl/.json',  'TagController@sflJson');
            });
        }
    });
}
if(Utility::enableRoute('vote*')) {
    Route::group(['prefix' => 'vote'], function()
    {
        Route::group(['before' => 'auth|throttle:10,1'], function()
        {
            if(Utility::enableRoute('vote/section*')) {
                //Route::get('/section/{id}',           'VoteController@sectionView']); //TODO
                Route::get('/section/{id}/up',          'VoteController@sectionUp');
                Route::get('/section/{id}/down',        'VoteController@sectionDown');
                Route::post('/section/{id}/up/.json',   'VoteController@sectionUpJson');
                Route::post('/section/{id}/down/.json', 'VoteController@sectionDownJson');
            }
            if(Utility::enableRoute('vote/post*')) {
                Route::get('/post/{id}',                'VoteController@postView');
                Route::get('/post/{id}/.json',          'VoteController@postJson');
                Route::get('/post/{id}/up',             'VoteController@postUp');
                Route::get('/post/{id}/down',           'VoteController@postDown');
                Route::post('/post/{id}/up/.json',      'VoteController@postUpJson');
                Route::post('/post/{id}/down/.json',    'VoteController@postDownJson');
            }
            if(Utility::enableRoute('vote/comment*')) {
                Route::get('/comment/{id}',             'VoteController@commentView');
                Route::get('/comment/{id}/.json',       'VoteController@commentJson');
                Route::get('/comment/{id}/up',          'VoteController@commentUp');
                Route::get('/comment/{id}/down',        'VoteController@commentDown');
                Route::post('/comment/{id}/up/.json',   'VoteController@commentUpJson');
                Route::post('/comment/{id}/down/.json', 'VoteController@commentDownJson');
            }
        });

    });
}
if(Utility::enableRoute('api*')) {
    Route::group(['prefix' => '/api'], function()
    {
        Route::get('/', 'SwaggerController@index');
        Route::get('/terms', 'SwaggerController@terms');
        Route::get('/license', 'SwaggerController@license');

        if(Utility::enableRoute('api/routes*')) {
            Route::get('/routes', 'SwaggerController@routes');
            Route::get('/routes/{type}', 'SwaggerController@getRoute');
        }
        if(Utility::enableRoute('api/auth*')) {
            Route::group(['prefix' => '/auth', 'before' => 'throttle:5,1'], function()
            {
                Route::get('/.json',   'Tappleby\AuthToken\AuthTokenController@index');
                Route::post('/.json',  'Tappleby\AuthToken\AuthTokenController@store');
                Route::delete('.json', 'Tappleby\AuthToken\AuthTokenController@destroy');
            });
        }
        if(Utility::enableRoute('api/vote*')) {
            Route::group(['prefix' => 'vote', 'before' => 'auth.token|throttle:10,1'], function()
            {
                if(Utility::enableRoute('api/vote/section/*')) {
                    Route::post('/section/{id}/up/.json',   'VoteController@sectionUpJson');
                    Route::post('/section/{id}/down/.json', 'VoteController@sectionDownJson');
                }
                if(Utility::enableRoute('api/vote/post/*')) {
                    Route::post('/post/{id}/up/.json',      'VoteController@postUpJson');
                    Route::post('/post/{id}/down/.json',    'VoteController@postDownJson');
                }
                if(Utility::enableRoute('api/vote/comment/*')) {
                    Route::post('/comment/{id}/up/.json',   'VoteController@commentUpJson');
                    Route::post('/comment/{id}/down/.json', 'VoteController@commentDownJson');
                }
            });
        }
    });
}
if(Utility::enableRoute('help*')) {
    Route::group(['prefix' => '/help'], function()
    {
        Route::get('/',           'HelpController@index');
        Route::get('/feeds',      'HelpController@feeds');
        Route::get('/posting',    'HelpController@posting');
        Route::get('/formatting', 'HelpController@formatting');
        Route::get('/points',     'HelpController@points');
        Route::get('/moderation', 'HelpController@moderation');
        Route::get('/anonymity',  'HelpController@anonymity');
        Route::get('/help',       'HelpController@help');
    });
}
if(Utility::enableRoute('assets*')) {
    Route::group(['prefix' => '/assets'], function()
    {
        Route::get('/prod/{filename}',       'AssetsController@prod');
        Route::get('/css/{filename}',        'AssetsController@css');
        Route::get('/css/themes/{filename}', 'AssetsController@css_themes');
        Route::get('/css/prefs/{filename}',  'AssetsController@prefs');
    });
}
if(Utility::enableRoute('bstall*')) {
    Route::group(['prefix' => '/bstall'], function()
    {
        Route::post('/draw/{id}',     'Whackashoe\Bstall\Controllers\BstallController@draw');
    });
}
