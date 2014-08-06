<?php
use \Michelf\MarkdownExtra;
use \Functional as F;

class SectionController extends BaseController
{

    protected function add($section_title)
    {
		$section_id = SectionController::getId($section_title);
		$sections = SectionController::get();

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

    protected function new_($section_title)
    {
        return $this->get($section_title, 'new');
    }

    protected function top($section_title, $timeframe)
    {
        return $this->get($section_title, 'top', $timeframe);
    }

    protected function controversial($section_title, $timeframe)
    {
        return $this->get($section_title, 'controversial', $timeframe);
    }

    protected function get($section_title="all", $sort_mode=null, $timeframe_mode=null)
    {
        $section_id = Section::getId($section_title);

        if(is_null($sort_mode)) {
            $sort_mode = Utility::getSortMode();
        }

        if(is_null($timeframe_mode)) {
            $timeframe_mode = Utility::getSortTimeframe();
        }

        $response = Response::make(View::make('section', [
            'sections'      => Section::get(),
            'posts'         => $this->getPosts($sort_mode, $section_id, $this->getSecondsFromTimeframe($timeframe_mode)),
            'section_title' => $section_title,
            'sidebar'       => Section::getSidebar($section_id),
            'sort_highlight'           => $sort_mode,
            'sort_timeframe_highlight' => $timeframe_mode
        ]))
        ->withCookie(Cookie::make('posts_sort_mode', $sort_mode))
        ->withCookie(Cookie::make('posts_sort_timeframe', $timeframe_mode));

        return $response;
    }

    protected function getSecondsFromTimeframe($timeframe)
    {
        switch($timeframe) {
            case 'day':   return SortController::DAY_SECONDS;   break;
            case 'week':  return SortController::WEEK_SECONDS;  break;
            case 'month': return SortController::MONTH_SECONDS; break;
            case 'year':  return SortController::YEAR_SECONDS;  break;
            case 'all':   return SortController::ALL_SECONDS;   break;
            default:      return App::abort(404);               break;
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
