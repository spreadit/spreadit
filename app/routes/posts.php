<?php
/***********************************************
 *
 * VIEW SECTIONS AND POSTS
 *
 **********************************************/
Route::group(['prefix' => '/s'], function()
{
	Route::group(['prefix' => '/{section_title}'], function($section_title)
	{
		Route::get('/', function($section_title)
        {
			$seconds = SectionController::getSortSecondsFromCookie();
			$section_id = SectionController::getId($section_title);
			$posts = SectionController::getPostsFromCookie($section_id, $seconds);

			return Response::make(SectionController::render($section_title, $posts));
		});

		Route::get('/hot', function($section_title)
		{
			return Response::make(SectionController::render($section_title, PostController::getHotList(SectionController::getId($section_title)), 'hot'))->withCookie(Cookie::make('posts_sort_mode', 'hot'));

		});

		Route::get('/new', function($section_title)
		{
			$response = Response::make(SectionController::render($section_title, PostController::getNewList(SectionController::getId($section_title)), 'new'))->withCookie(Cookie::make('posts_sort_mode', 'new'));
			return $response;
		});
		
		Route::group(['prefix' => '/top'], function($section_title)
		{
			Route::get('/', function($section_title)
			{
				$seconds = SectionController::getSortSecondsFromCookie();
				$section_id = SectionController::getId($section_title);
				$posts = PostController::getTopList($section_id, $seconds);

				return Response::make(SectionController::render($section_title, $posts, 'top'));
			});
			Route::get('/day', function($section_title)
			{
				return Response::make(SectionController::render($section_title, PostController::getTopList(SectionController::getId($section_title), SortController::DAY_SECONDS), 'top', 'day'))
					->withCookie(Cookie::make('posts_sort_mode', 'top'))
					->withCookie(Cookie::make('posts_sort_timeframe', 'day'));
			});
			Route::get('/week', function($section_title)
			{
				return Response::make(SectionController::render($section_title, PostController::getTopList(SectionController::getId($section_title), SortController::WEEK_SECONDS), 'top', 'week'))
					->withCookie(Cookie::make('posts_sort_mode', 'top'))
					->withCookie(Cookie::make('posts_sort_timeframe', 'week'));
			});
			Route::get('/month', function($section_title)
			{
				return Response::make(SectionController::render($section_title, PostController::getTopList(SectionController::getId($section_title), SortController::MONTH_SECONDS), 'top', 'month'))
					->withCookie(Cookie::make('posts_sort_mode', 'top'))
					->withCookie(Cookie::make('posts_sort_timeframe', 'month'));
			});
			Route::get('/year', function($section_title)
			{
				return Response::make(SectionController::render($section_title, PostController::getTopList(SectionController::getId($section_title), SortController::YEAR_SECONDS), 'top', 'year'))
					->withCookie(Cookie::make('posts_sort_mode', 'top'))
					->withCookie(Cookie::make('posts_sort_timeframe', 'year'));
			});
			Route::get('/forever', function($section_title)
			{
				return Response::make(SectionController::render($section_title, PostController::getTopList(SectionController::getId($section_title), time()), 'top', 'forever'))
					->withCookie(Cookie::make('posts_sort_mode', 'top'))
					->withCookie(Cookie::make('posts_sort_timeframe', 'forever'));
			});
		});

		Route::group(['prefix' => '/controversial'], function($section_title)
		{
			Route::get('/', function($section_title)
			{
				$seconds = SectionController::getSortSecondsFromCookie();
				$section_id = SectionController::getId($section_title);
				$posts = PostController::getControversialList($section_id, $seconds);

				return Response::make(SectionController::render($section_title, $posts, 'controversial'));
			});
			Route::get('/day', function($section_title)
			{
				return Response::make(SectionController::render($section_title, PostController::getControversialList(SectionController::getId($section_title), SortController::DAY_SECONDS), 'controversial', 'day'))
					->withCookie(Cookie::make('posts_sort_mode', 'controversial'))
					->withCookie(Cookie::make('posts_sort_timeframe', 'day'));
			});
			Route::get('/week', function($section_title)
			{
				return Response::make(SectionController::render($section_title, PostController::getControversialList(SectionController::getId($section_title), SortController::WEEK_SECONDS), 'controversial', 'week'))
					->withCookie(Cookie::make('posts_sort_mode', 'controversial'))
					->withCookie(Cookie::make('posts_sort_timeframe', 'week'));
			});
			Route::get('/month', function($section_title)
			{
				return Response::make(SectionController::render($section_title, PostController::getControversialList(SectionController::getId($section_title), SortController::MONTH_SECONDS), 'controversial', 'month'))
					->withCookie(Cookie::make('posts_sort_mode', 'controversial'))
					->withCookie(Cookie::make('posts_sort_timeframe', 'month'));
			});
			Route::get('/year', function($section_title)
			{
				return Response::make(SectionController::render($section_title, PostController::getControversialList(SectionController::getId($section_title), SortController::YEAR_SECONDS), 'controversial', 'year'))
					->withCookie(Cookie::make('posts_sort_mode', 'controversial'))
					->withCookie(Cookie::make('posts_sort_timeframe', 'year'));
			});
			Route::get('/forever', function($section_title)
			{
				return Response::make(SectionController::render($section_title, PostController::getControversialList(SectionController::getId($section_title), time()), 'controversial', 'forever'))
					->withCookie(Cookie::make('posts_sort_mode', 'controversial'))
					->withCookie(Cookie::make('posts_sort_timeframe', 'forever'));
			});
		});
	});

	Route::get('/{section_title}/posts/{post_id}/{post_title?}', function($section_title, $post_id)
	{
		$sections = SectionController::get();
		$section_id = SectionController::getId($section_title);
		$sidebar = SectionController::getSidebar($section_id);
		$post = PostController::get($post_id);
		$my_votes = VoteController::getMatchingVotes(VoteController::POST_TYPE, [$post]);
		$post->selected = isset($my_votes[$post_id]) ? $my_votes[$post_id] : 0;
        $commentTree = CommentController::sortTreeNew(CommentController::makeTree(CommentController::get($post_id)));
        $comments = CommentController::renderTree($commentTree);
		$sort_highlight = SectionController::getSortbyCookie();
        $sort_timeframe_highlight = SectionController::getSortTimeframebyCookie();

		return View::make('post', [
			'section_title' => $section_title,
			'sections' => $sections,
			'comments' => $comments,
			'post' => $post,
            'sidebar' => $sidebar,
            'sort_highlight' => $sort_highlight,
            'sort_timeframe_highlight' => $sort_timeframe_highlight,
		]);
	});

});

