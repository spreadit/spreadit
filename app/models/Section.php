<?php
class Section extends BaseModel
{
    protected $table = 'sections';

    const PAGE_POST_COUNT = 25;
    const SECTION_HRENDER_CACHE_MINS = 15;
    const ALL_SECTIONS_TITLE = "all";

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
