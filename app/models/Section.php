<?php
class Section extends BaseModel
{
    protected $table = 'sections';

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
}
