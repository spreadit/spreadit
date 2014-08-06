<?php

use \Michelf\MarkdownExtra;
use \Functional as F;

class PostController extends BaseController
{
    protected function get($section_title, $post_id)
    {
        $sections = Section::get();
        $section_id = Section::getId($section_title);
        $sidebar = Section::getSidebar($section_id);
        $post = Post::get($post_id);
        $my_votes = Vote::getMatchingVotes(Vote::POST_TYPE, [$post]);
        $post->selected = isset($my_votes[$post_id]) ? $my_votes[$post_id] : 0;
        $commentTree = new CommentTree(Comment::get($post_id));
        $sectionController = new SectionController;
        $sort_highlight = Utility::getSortMode();
        $sort_timeframe_highlight = Utility::getSortTimeframe();

        return View::make('post', [
            'section_title' => $section_title,
            'sections' => $sections,
            'comments' => $commentTree->grab()->sort('new')->render(),
            'post' => $post,
            'sidebar' => $sidebar,
            'sort_highlight' => $sort_highlight,
            'sort_timeframe_highlight' => $sort_timeframe_highlight,
        ]);
    }

    protected function post($section_title)
    {
        return Post::make($section_title, Input::get('data'), Input::get('title'), Input::get('url'));
    }

    protected function update($post_id)
    {
        return Post::amend($post_id, Input::get('data'));
    }
}
