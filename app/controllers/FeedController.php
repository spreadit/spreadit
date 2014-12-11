<?php

class FeedController extends BaseController
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
     * generate xss/atom from spreadit
     *
     * @param string $section_title
     * @return Roumen\Feed
     */
    protected function generate($section_title)
    {
        $sections = $this->section->getByTitle(Utility::splitByTitle($section_title)); 
        if(empty($sections)) {
            App::abort(404);
        }
        $section = $this->section->sectionFromSections($sections);
        $posts = $this->post->getHotList(F::map($sections, function($m) { return $m->id; }), $this->vote);
        $feed = Feed::make();
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


    /**
     * renders a sections rss feed
     *
     * @param string  $section_title
     * @return Roumen\Feed
     */
    public function rss($section_title="all")
    {
        return $this->generate($section_title)->render('rss');
    }


    /**
     * renders a sections atom feed
     *
     * @param string  $section_title
     * @return Roumen\Feed
     */
    public function atom($section_title="all")
    {
        return $this->generate($section_title)->render('atom');
    }
}
