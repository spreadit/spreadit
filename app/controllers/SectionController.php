<?php
use \Michelf\MarkdownExtra;
use \Functional as F;

class SectionController extends BaseController
{
    const PAGE_POST_COUNT = 25;
    const SECTION_HRENDER_CACHE_MINS = 15;
    const ALL_SECTIONS_TITLE = "all";

    /**
     * gets numerical id from title
     *
     * @param $section_title string
     *
     * @return int
     */
    public static function getId($section_title)
    {
        if($section_title == self::ALL_SECTIONS_TITLE) return 0;

        return Cache::remember('sections_id_'.$section_title, self::SECTION_HRENDER_CACHE_MINS, function() use($section_title)
        {
            $id = DB::table('sections')->where('title', 'LIKE', $section_title)->pluck('id');

            if(is_null($id)) {
                App::abort(404, "spreadit section not found");
            }

            return $id;
        });
    }

	public static function get()
	{
		return Cache::remember('sections', self::SECTION_HRENDER_CACHE_MINS, function()
		{
			return DB::table('sections')
				->select('id', 'title')
				->orderBy('id', 'asc')
				->get();
		});
    }

    public static function getSidebar($id)
    {
        //todo cache this
            return DB::table('sections')
                ->select('data')
                ->where('id', '=', $id)
                ->first()->data;
    }

    public static function render($section_title, $posts, $sort_highlight="", $sort_timeframe_highlight="")
    {
        $section_id = SectionController::getId($section_title);
        $sidebar = SectionController::getSidebar($section_id);
        $sections = SectionController::get();
        if($sort_highlight == "") $sort_highlight = SectionController::getSortbyCookie();
        if($sort_timeframe_highlight == "") $sort_timeframe_highlight = SectionController::getSortTimeframebyCookie();

        return View::make('section', [
            'sections' => $sections,
            'posts' => $posts,
            'section_title' => $section_title,
            'sidebar' => $sidebar,
            'sort_highlight' => $sort_highlight,
            'sort_timeframe_highlight' => $sort_timeframe_highlight
        ]);
    }

    public static function getSortSecondsFromCookie()
    {
        switch(self::getSortTimeframebyCookie()) {
        case 'day':
                $seconds = SortController::DAY_SECONDS;
                break;
        case 'week':
                $seconds = SortController::WEEK_SECONDS;
                break;
        case 'month':
                $seconds = SortController::MONTH_SECONDS;
                break;
        case 'year':
                $seconds = SortController::YEAR_SECONDS;
                break;
        case 'forever':
                $seconds = time();
                break;
        default:
                Cookie::queue(SortController::TIMEFRAME_COOKIE_NAME, $seconds, 60 * 24 * 30);
                $seconds = SortController::WEEK_SECONDS;
                break;
        }

        return $seconds;
    }

    public static function getSortTimeframebyCookie()
    {
        return Cookie::get(SortController::TIMEFRAME_COOKIE_NAME, SortController::TIMEFRAME_COOKIE_DEFAULT);
    }

    public static function getSortbyCookie()
    {
        return Cookie::get(SortController::SORTBY_COOKIE_NAME, SortController::SORTBY_COOKIE_DEFAULT);
    }

    public static function getPostsFromCookie($section_id, $seconds)
    {
        $posts = [];

        switch(self::getSortbyCookie()){
        case 'hot':
                $posts = PostController::getHotList($section_id);
                break;
        case 'new':
                $posts = PostController::getNewList($section_id);
                break;
        case 'top':
                $posts = PostController::getTopList($section_id, $seconds);
                break;
        case 'controversial':
                $posts = PostController::getControversialList($section_id, $seconds);
                break;
        default:
                Cookie::queue(SortController::SORTBY_COOKIE_NAME, SortController::SORTBY_COOKIE_DEFAULT, 60 * 24 * 30);
                $posts = PostController::getHotList($section_id);
                break;
        }

        return $posts;
    }
}
