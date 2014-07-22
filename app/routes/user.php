<?php

/***********************************************
 *
 * USER FUNCTIONS
 *
 **********************************************/
Route::group(['before' => 'csrf'], function()
{
	Route::post('/login', 'UserController@login');
	Route::post('/register', 'UserController@register');
});

Route::group(['prefix' => '/u/{username}'], function($username)
{
    Route::get('/', 'UserController@mainVoteView');
    Route::get('/comments', 'UserController@commentsView');
    Route::get('/posts', 'UserController@postsView');
    Route::get('/votes/comments', 'UserController@commentsVotesView');
    Route::get('/votes/posts', 'UserController@postsVotesView');
});
