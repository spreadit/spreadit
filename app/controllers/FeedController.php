<?php

class FeedController extends BaseController
{
    protected $feed;
    protected $section;
    protected $post;

    public function __construct(Feed $feed, Section $section, Post $post)
    {
        $this->feed = $feed;
        $this->section = $section;
        $this->post = $post;
    }

    /**
     * generate xss/atom from spreadit
     *
     * @param string $section_title name of section
     *
     * @return Roumen\Feed
     */
    protected function generate($section_title)
    {
        $sections = $this->section->getByTitle($this->section->splitByTitle($section_title)); 
        if(empty($sections)) {
            App::abort(404);
        }
        $section = $this->section->sectionFromSections($sections);
        $posts = $this->post->getHotList(F::map($sections, function($m) { return $m->id; }));
        $feed = $this->feed->make();
        $feed->title = $section_title;
        $feed->description = "read hot posts from $section_title"; 
        $feed->link = URL::to("/s/$section_title");
        $feed->lang = 'en';

        $created_at_counter = 0;
        foreach($posts as $post) {
            $feed->add($post->title, $post->username, URL::to($post->url), date(DATE_ATOM, $post->created_at), $post->markdown);
            
            if($post->created_at > $created_at_counter) {
                $created_at_counter = $post->created_at;
            }
        }
        $feed->pubdate = date(DATE_ATOM, $created_at_counter);

        return $feed;
    }

    public function rss($section_title="all")
    {
        return $this->generate($section_title)->render('rss');
    }

    public function atom($section_title="all")
    {
        return $this->generate($section_title)->render('atom');
    }
}
