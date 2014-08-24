<?php

use \Michelf\MarkdownExtra;
use \Functional as F;

class PostController extends BaseController
{
    protected function get($section_title, $post_id)
    {
        $section = Section::getByTitle($section_title);
        $my_votes = Vote::getMatchingVotes(Vote::SECTION_TYPE, [$section]);
        $section->selected = isset($my_votes[$section->id]) ? $my_votes[$section->id] : 0;
        
        $post = Post::get($post_id);
        $my_votes = Vote::getMatchingVotes(Vote::POST_TYPE, [$post]);
        $post->selected = isset($my_votes[$post_id]) ? $my_votes[$post_id] : 0;
        $commentTree = new CommentTree(Comment::get($post_id));
        $sort_highlight = Utility::getSortMode();
        $sort_timeframe_highlight = Utility::getSortTimeframe();

        return View::make('post', [
            'section' => $section,
            'sections' => Section::get(),
            'comments' => $commentTree->grab()->sort('new')->render(),
            'post' => $post,
            'sort_highlight' => $sort_highlight,
            'sort_timeframe_highlight' => $sort_timeframe_highlight,
        ]);
    }

    protected function getJson($section_title, $post_id)
    {
		$post = Post::get($post_id);
		$my_votes = Vote::getMatchingVotes(Vote::POST_TYPE, [$post]);
		$post->selected = isset($my_votes[$post_id]) ? $my_votes[$post_id] : 0;
        $commentTree = new CommentTree(Comment::get($post_id));

		return Response::json([
			'post' => $post,
			'comments' => $commentTree->grab()->sort('new')
        ]);
    }

    protected function getRedir($post_id)
    {
        $post = Post::findOrFail($post_id);
        $section_title = Section::findOrFail($post->section_id)->title;
        
        return Redirect::to("/s/$section_title/posts/$post_id");
    }

    protected function post($section_title)
    {
        return Post::make(Input::get('section'), Input::get('data'), Input::get('title'), Input::get('url'));
    }

    protected function update($post_id)
    {
        return Post::amend($post_id, Input::get('data'));
    }

    protected function delete($post_id)
    {
        return Post::remove($post_id);
    }
}
