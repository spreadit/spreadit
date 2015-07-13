<?php

class SectionController extends BaseController
{

    protected $section;
    protected $post;
    protected $vote;

    public function __construct(Section $section, Post $post, Vote $vote)
    {
        $this->section = $section;
        $this->post = $post;
        $this->vote = $vote;
    }


    /**
     * renders add new post to section page
     *
     * @param string  $section_title
     * @return Illuminate\View\View
     */
    public function add($section_title)
    {
        $sections = $this->section->get();
        $titles = Utility::splitByTitle($section_title);
        $section = $this->section->sectionFromSections($this->section->getByTitle($titles));
        $all_sections = F::reject(
            F::map($this->section->getAll(), function($m) { 
                return $m->title;
            }),
            function($m) use ($section_title) {
                return strcmp($section_title, $m) == 0 || strcmp($m, "all") == 0;
            }
        );

        if(strcmp($section->title, "all") == 0) {
            return View::make('page.newpost.multisection', [
                'sections'   => $sections,
                'section'    => $section,
                'selections' => F::map($sections, function($m) { return $m->title; })
            ]);
        } else if(count($titles) > 1) {
            return View::make('page.newpost.multisection', [
                'sections'   => $sections,
                'section'    => $section,
                'selections' => $titles
            ]);
        }

        if(strcmp($section->title, "") == 0) {
            $section->title = $section_title;
        }

		return View::make('page.newpost.post', [
			'sections'     => $sections,
			'section'      => $section,
            'all_sections' => $all_sections
        ]);
    }


    /**
     * show hot listing for section
     *
     * @param string  $section_title
     * @return Illuminate\Http\Response
     */
    public function hot($section_title)
    {
        return $this->getHtml($section_title, 'hot', null);
    }


    /**
     * see `hot`
     *
     * @param string  $section_title
     * @param string  $timeframe
     * @return Illuminate\Http\JsonResponse
     */
    public function hotJson($section_title)
    {
        return $this->getJson($section_title, 'hot', null);
    }


    /**
     * show new listing for section
     *
     * @param string  $section_title
     * @return Illuminate\Http\Response
     */
    public function new_($section_title)
    {
        return $this->getHtml($section_title, 'new', null);
    }


    /**
     * see `new`
     *
     * @param string  $section_title
     * @return Illuminate\Http\JsonResponse
     */
    public function new_Json($section_title)
    {
        return $this->getJson($section_title, 'new', null);
    }


    /**
     * show top listing for section
     *
     * @param string  $section_title
     * @param string  $timeframe
     * @return Illuminate\Http\Response
     */
    public function top($section_title, $timeframe)
    {
        return $this->getHtml($section_title, 'top', $timeframe);
    }


    /**
     * see `top`
     *
     * @param string  $section_title
     * @param string  $timeframe
     * @return Illuminate\Http\JsonResponse
     */
    public function topJson($section_title, $timeframe)
    {
        return $this->getJson($section_title, 'top', $timeframe);
    }


    /**
     * show controversial listing for section
     *
     * @param string  $section_title
     * @param string  $timeframe
     * @return Illuminate\Http\Response
     */
    public function controversial($section_title, $timeframe)
    {
        return $this->getHtml($section_title, 'controversial', $timeframe);
    }


    /**
     * see `controversial`
     *
     * @param string  $section_title
     * @param string  $timeframe
     * @return Illuminate\Http\JsonResponse
     */
    public function controversialJson($section_title, $timeframe)
    {
        return $this->getJson($section_title, 'controversial', $timeframe);
    }


    /**
     * renders a section page
     *
     * @param string  $section_title
     * @param string  $sort_mode
     * @param string  $timeframe_mode
     * @param bool    $no_view
     * @return mixed
     */
    protected function get($section_title, $sort_mode, $timeframe_mode, $no_view)
    {
        $sections = $this->section->getByTitle(Utility::splitByTitle($section_title));
        if(empty($sections)) {
            App::abort(404);
        }
        $sections = $this->vote->getSelectedList(Constant::SECTION_TYPE, $sections);

        $section = $this->section->sectionFromSections($sections);
        $section->selected = $this->vote->getSelected(Constant::SECTION_TYPE, $section);

        if(is_null($sort_mode)) {
            $sort_mode = Utility::getSortMode();
        }

        if(is_null($timeframe_mode)) {
            $timeframe_mode = Utility::getSortTimeframe();
        }

        $section_ids = F::map($sections, function($m) { return $m->id; });
        $posts = $this->getPosts($sort_mode, $section_ids, $this->getSecondsFromTimeframe($timeframe_mode));

        if($no_view) return $posts;

        return Response::make(View::make('page.section', [
            'sections'                 => $this->section->get(),
            'posts'                    => $posts,
            'section'                  => $section,
            'sort_highlight'           => $sort_mode,
            'sort_timeframe_highlight' => $timeframe_mode
        ]))
        ->withCookie(Cookie::make('posts_sort_mode', $sort_mode))
        ->withCookie(Cookie::make('posts_sort_timeframe', $timeframe_mode));
    }


    /**
     * convenience wrapper to get
     *
     * @param string  $section_title
     * @param string  $sort_mode
     * @param string  $timeframe_mode
     * @return Illuminate\Http\Response
     */
    public function getHtml($section_title="all", $sort_mode="hot", $timeframe_mode=null)
    {
        return $this->get($section_title, $sort_mode, $timeframe_mode, false);
    }


    /**
     * convenience wrapper to get
     *
     * @param string  $section_title
     * @param string  $sort_mode
     * @param string  $timeframe_mode
     * @return Illuminate\Http\JsonResponse
     */
    public function getJson($section_title="all", $sort_mode="hot", $timeframe_mode=null)
    {
        return Response::json(iterator_to_array($this->get($section_title, $sort_mode, $timeframe_mode, true)));
    }


    /**
     * render page listing spreadits
     *
     * @return Illuminate\Http\Response
     */
    public function getSpreadits()
    {
        return View::make('page.spreadits', [
            'sections' => $this->section->get(),
            'spreadits' => $this->section->getAll()
        ]);
    }


    /**
     * see `getSpreadits`
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function getSpreaditsJson()
    {
        return Response::json($this->section->getAll());
    }


    /**
     * utility function to convert timeframe into seconds
     *
     * @param string  $timeframe
     * @return int
     */
    public function getSecondsFromTimeframe($timeframe)
    {
        switch($timeframe) {
            case 'day':     return Constant::SORT_DAY_SECONDS;   break;
            case 'week':    return Constant::SORT_WEEK_SECONDS;  break;
            case 'month':   return Constant::SORT_MONTH_SECONDS; break;
            case 'year':    return Constant::SORT_YEAR_SECONDS;  break;
            case 'forever': return time();                        break;
            default:        return App::abort(404);               break;
        }
    }

    /**
     * get listing of posts by sort_mode and sections
     *
     * @param string  $sort_mode
     * @param [int]   $section_ids
     * @param int     $seconds
     * @return mixed
     */
    public function getPosts($sort_mode, array $section_ids, $seconds)
    {
        switch($sort_mode) {
            case 'hot':           return $this->post->getHotList($section_ids, $this->vote); break;
            case 'new':           return $this->post->getNewList($section_ids, $this->vote); break;
            case 'top':           return $this->post->getTopList($section_ids, $seconds, $this->vote); break;
            case 'controversial': return $this->post->getControversialList($section_ids, $seconds, $this->vote); break;
            default:              return App::abort(404); break;
        }
    }
}
