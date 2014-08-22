<?php

class Section extends BaseModel
{
    const PAGE_POST_COUNT = 25;
    const SECTION_HRENDER_CACHE_MINS = 15;
    const ALL_SECTIONS_TITLE = "all";
    const TOPBAR_SECTIONS = 15;
    const MAX_TITLE_LENGTH = 24;
    const MIN_TITLE_LENGTH = 1;

    protected $table = 'sections';
    
    protected $attributes = array(
        'upvotes' => '0',
        'downvotes' => '0'
    );

    public static $rules = [
        'title' => "required|min:2|max:24"
    ];

    /*
     * get a single section by its title name
     *
     * @param string $section_title title in db
     *
     * @throws 404
     *
     * @return db obj
     */
    public static function getByTitle($section_title)
    {
        $section = DB::table('sections')
            ->select('id', 'title', 'data')
            ->where('title', 'LIKE', $section_title)
            ->first();

        if(is_null($section)) {
            App::abort(404, "spreadit section not found");
        }

        return $section;
    }

    public static function exists($section_title)
    {
        return !is_null(DB::table('sections')->where('title', 'LIKE', $section_title)->first());
    }

    public static function make($section_title)
    {
        $section = new Section();
        $section->title = $section_title;
        return $section->save();
    }

    /*
     * get all sections sorted by top
     *
     * @return list of sections
     */
	public static function get()
	{
        return DB::table('sections')
            ->select('sections.id', 'sections.title')
            ->orderBy(DB::raw('(sections.upvotes - sections.downvotes)'), 'desc')
            ->get();
    }
}
