<?php
use \Michelf\MarkdownExtra;
use \Functional as F;

class SectionController extends BaseController
{

    protected function add($section_title)
    {
		$section_id = Section::getId($section_title);
		$sections = Section::get();

		return View::make('newpost', [
			'sections' => $sections,
			'title' => $section_title,
            'formurl' => '/s/'.$section_title.'/add'
        ]);
    }

    protected function hot($section_title)
    {
        return $this->get($section_title, 'hot');
    }

    protected function hotJson($section_title)
    {
        return $this->getJson($section_title, 'hot');
    }

    protected function new_($section_title)
    {
        return $this->get($section_title, 'new');
    }

    protected function new_Json($section_title)
    {
        return $this->getJson($section_title, 'new');
    }

    protected function top($section_title, $timeframe)
    {
        return $this->get($section_title, 'top', $timeframe);
    }

    protected function topJson($section_title, $timeframe)
    {
        return $this->getJson($section_title, 'top', $timeframe);
    }


    protected function controversial($section_title, $timeframe)
    {
        return $this->get($section_title, 'controversial', $timeframe);
    }

    protected function controversialJson($section_title, $timeframe)
    {
        return $this->getJson($section_title, 'controversial', $timeframe);
    }

    protected function get($section_title="all", $sort_mode=null, $timeframe_mode=null, $no_view=false)
    {
        $section_id = Section::getId($section_title);

        if(is_null($sort_mode)) {
            $sort_mode = Utility::getSortMode();
        }

        if(is_null($timeframe_mode)) {
            $timeframe_mode = Utility::getSortTimeframe();
        }

        $posts = $this->getPosts($sort_mode, $section_id, $this->getSecondsFromTimeframe($timeframe_mode));

        if($no_view) return $posts;

        return Response::make(View::make('section', [
            'sections'      => Section::get(),
            'posts'         => $posts,
            'section_title' => $section_title,
            'sidebar'       => Section::getSidebar($section_id),
            'sort_highlight'           => $sort_mode,
            'sort_timeframe_highlight' => $timeframe_mode
        ]))
        ->withCookie(Cookie::make('posts_sort_mode', $sort_mode))
        ->withCookie(Cookie::make('posts_sort_timeframe', $timeframe_mode));
    }

    protected function getJson($section_title="all", $sort_mode=null, $timeframe_mode=null)
    {
        return Response::json(iterator_to_array($this->get($section_title, $sort_mode, $timeframe_mode, true)));
    }

    protected function getSpreaditsJson()
    {
        return Response::json(Section::get());
    }

    protected function getSecondsFromTimeframe($timeframe)
    {
        switch($timeframe) {
            case 'day':     return SortController::DAY_SECONDS;   break;
            case 'week':    return SortController::WEEK_SECONDS;  break;
            case 'month':   return SortController::MONTH_SECONDS; break;
            case 'year':    return SortController::YEAR_SECONDS;  break;
            case 'forever': return time();                        break;
            default:        return App::abort(404);               break;
        }
    }

    protected function getPosts($sort_mode, $section_id, $seconds)
    {
        switch($sort_mode) {
            case 'hot':           return Post::getHotList($section_id, $seconds); break;
            case 'new':           return Post::getNewList($section_id, $seconds); break;
            case 'top':           return Post::getTopList($section_id, $seconds); break;
            case 'controversial': return Post::getControversialList($section_id, $seconds); break;
            default:              return App::abort(404); break;
        }
    }
}
