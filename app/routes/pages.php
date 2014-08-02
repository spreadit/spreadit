<?php
/***********************************************
 *
 * BASIC PAGES
 *
 **********************************************/
Route::get('/', function()
{
	if(Cookie::get('new_user') == '') {
		return	Redirect::to('/about')->withCookie(Cookie::make('new_user', 'fresh'));
	} else {
		$seconds = SectionController::getSortSecondsFromCookie();
		$section_id = SectionController::getId('all');
		$posts = SectionController::getPostsFromCookie($section_id, $seconds);

		return Response::make(SectionController::render('all', $posts));
	}
});

Route::get('/.rss', function()
{
    return FeedController::generate('all')->render('rss');
});

Route::get('/.atom', function()
{
    return FeedController::generate('all')->render('atom');
});

Route::get('/about', function()
{
	return View::make('about', [ 
		'sections' => SectionController::get()
	]);
});

Route::get('/contact', function()
{
	return View::make('contact', [ 
		'sections' => SectionController::get()
	]);
});

Route::get('/threats', function()
{
	return View::make('threats', [ 
		'sections' => SectionController::get()
	]);
});

Route::get('/login', function()
{
    return View::make('login', ['sections' => SectionController::get()]);
});

Route::any('/generate_view', function()
{
    $allowed_views = ['commentreplybox', 'commentsourcebox', 'commenteditbox', 'postreplybox', 'postsourcebox', 'posteditbox'];
    if(!in_array(Input::get('view'), $allowed_views)) return "not allowed";
    return View::make(Input::get('view'), Input::all());
});

Route::get('/titlefromurl', function()
{
	if(!Input::has('url')) {
		return "url not given";
	}

	return UtilController::titleFromUrl(Input::get('url'));
});
