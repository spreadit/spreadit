<?php

class FeedController extends BaseController
{
    /**
     * generate xss/atom from spreadit
     *
     * @param string $section_title name of section
     *
     * @return Roumen\Feed
     */
    public static function generate($section_title)
    {
        $section_id = SectionController::getId($section_title);
        $posts = PostController::getHotList($section_id);
        $feed = Feed::make();
        $feed->title = $section_title;
        $feed->description = "$section_title of spreadit :: " . SectionController::getSidebar($section_id);
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
}
