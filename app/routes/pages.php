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

Route::any('/generate_markdown', function()
{
    //$data = Input::get('md');
    $data = "
Welcome to /s/theory
===
This is the place to share any theories of any shape and size. Whether it is pure conjecture, showerthought, conspiracy, or anything else. It will remain a free ground for all speech (like the rest of the site).

Be objective, transparent, and intelligent, and you will win.
";
    return \Michelf\MarkdownExtra::defaultTransform(e($data));
});

Route::get('/titlefromurl', function()
{
	if(!Input::has('url')) {
		return "url not given";
	}

	$url = Input::get('url');

    if(filter_var($url, FILTER_VALIDATE_URL)) {
        $str = file_get_contents($url, NULL, NULL, 0, 16384);
               
        if(strlen($str) > 0) {
            preg_match("/\<title\>(.*)\<\/title\>/", $str, $title);           
            
            if(count($title) > 1) {
                return substr(trim($title[1]), 0, 128);
            } else {
                try {
                    $html = Sunra\PhpSimple\HtmlDomParser::file_get_html($url);
                } catch(ErrorException $e) {
                    return "url not found";
                }
                if(!$html) {
                    return "sorry, we couldn't get a title";
                } 

                foreach($html->find("title") as $e) {
                    return substr(trim($e->plaintext), 0, 128);
                }

                return "no title found";
            }
        }
    } else {
        return "not url";
    }
});
