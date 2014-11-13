<?php

class SectionController extends BaseController
{

    protected function add($section_title)
    {
        $sections = Section::get();
        $titles = Section::splitByTitle($section_title);
        $section = Section::sectionFromSections(Section::getByTitle($titles));

        if(strcmp($section->title, "all") == 0) {
            return View::make('newpost_multisection', [
                'sections'   => $sections,
                'section'    => $section,
                'selections' => F::map($sections, function($m) { return $m->title; })
            ]);
        } else if(count($titles) > 1) {
            return View::make('newpost_multisection', [
                'sections'   => $sections,
                'section'    => $section,
                'selections' => $titles
            ]);
        }

		return View::make('newpost', [
			'sections' => $sections,
			'section'  => $section
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
        $sections = Section::getByTitle(Section::splitByTitle($section_title));
        if(empty($sections)) {
            App::abort(404);
        }
        $section = Section::sectionFromSections($sections);
        $my_votes = Vote::getMatchingVotes(Vote::SECTION_TYPE, $sections);

        F::map($sections, function($m) {
            $m->selected = isset($my_votes[$m->id]) ? $my_votes[$m->id] : 0;
        });

        if(is_null($sort_mode)) {
            $sort_mode = Utility::getSortMode();
        }

        if(is_null($timeframe_mode)) {
            $timeframe_mode = Utility::getSortTimeframe();
        }

        $section_ids = F::map($sections, function($m) { return $m->id; });
        $posts = $this->getPosts($sort_mode, $section_ids, $this->getSecondsFromTimeframe($timeframe_mode));

        if($no_view) return $posts;

        return Response::make(View::make('section', [
            'sections'                 => Section::get(),
            'posts'                    => $posts,
            'section'                  => $section,
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

    protected function getSpreadits()
    {
        return View::make('spreadits', [
            'sections' => Section::get(),
            'spreadits' => Section::getAll()
        ]);
    }

    protected function getSpreaditsJson()
    {
        return Response::json(Section::getAll());
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

    protected function getPosts($sort_mode, array $section_ids, $seconds)
    {
        switch($sort_mode) {
            case 'hot':           return Post::getHotList($section_ids, $seconds); break;
            case 'new':           return Post::getNewList($section_ids, $seconds); break;
            case 'top':           return Post::getTopList($section_ids, $seconds); break;
            case 'controversial': return Post::getControversialList($section_ids, $seconds); break;
            default:              return App::abort(404); break;
        }
    }
}
