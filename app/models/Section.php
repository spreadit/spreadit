<?php

class Section extends BaseModel
{
    public $PAGE_POST_COUNT = 25;
    public $SECTION_HRENDER_CACHE_MINS = 15;
    public $ALL_SECTIONS_TITLE = "all";
    public $TOPBAR_SECTIONS = 15;
    public $MAX_TITLE_LENGTH = 24;
    public $MIN_TITLE_LENGTH = 1;
    public $PAGINATION_AMOUNT = 30;


    protected $table = 'sections';

    protected $guarded = array();
    protected $fillable = array('title');
    
    protected $attributes = array(
        'upvotes' => '0',
        'downvotes' => '0'
    );

    public static $rules = [
        'title' => "required|lowercase|andu|min:2|max:24"
    ];

    public function sectionFromEmptySection()
    {
        return $this->sectionFromSections($this->getByTitle([""]));
    }

    public function sectionFromSections(array $sections)
    {
        if(count($sections) == 0) {
            $section = new stdClass;
            $section->id = -1;
            $section->multi_spreadit = false;
            $section->title = "";
        } else if(count($sections) == 1) {
            $section = $sections[0];
        } else if(count($sections) > 1) {
            $section = new stdClass;
            $section->id = -1;
            $section->multi_spreadit = true;
            $section->title = implode('+', F::map($sections, function($m) { return $m->title; }));
        }

        return $section;
    }

    /*
     * get sections by their titles
     *
     * @param array string $section_titles title in db
     *
     * @return db obj
     */
    public function getByTitle(array $section_titles)
    {
        $sections = DB::table('sections')
            ->select('id', 'title', 'data')
            ->whereIn('title', $section_titles)
            ->get();

        if(is_null($sections)) {
            $sections = [];
            array_push($sections, new stdClass);
            $sections[0]->id = -1;
            $sections[0]->title = "not found";
            $sections[0]->data = "none";
        }

        return $sections;
    }

    /*
     * get sections by their ids
     *
     * @param array int $ids id in db
     *
     * @return db obj
     */
    public function getById(array $ids)
    {
        $sections = DB::table('sections')
            ->select('id', 'title', 'data')
            ->whereIn('id', $ids)
            ->get();

        if(is_null($sections)) {
            $sections = [];
            array_push($sections, new stdClass);
            $sections[0]->id = -1;
            $sections[0]->title = "not found";
            $sections[0]->data = "none";
        }

        return $sections;
    }

    public function exists($section_title)
    {
        return !is_null(DB::table('sections')->where('title', 'LIKE', $section_title)->first());
    }
    
    /*
     * get top sections sorted by top rank
     *
     * @return list of sections
     */
	public function get()
	{
        return DB::table('sections')
            ->select('sections.id', 'sections.title')
            ->orderBy(DB::raw('(sections.upvotes - sections.downvotes)'), 'desc')
            ->where('sections.id', '!=', '0')
            ->take($this->PAGINATION_AMOUNT)
            ->get();
    }

    /*
     * get all sections sorted by top rank
     *
     * @return list of sections
     */
	public function getAll()
	{
        return DB::table('sections')
            ->select('sections.id', 'sections.title')
            ->orderBy(DB::raw('(sections.upvotes - sections.downvotes)'), 'desc')
            ->get();
    }
}

