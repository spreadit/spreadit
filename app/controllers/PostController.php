<?php

class PostController extends BaseController
{
    protected function get($section_title, $post_id)
    {
        $section = Section::sectionFromSections(Section::getByTitle(Section::splitByTitle($section_title)));
        $my_votes = Vote::getMatchingVotes(Vote::SECTION_TYPE, [$section]);
        $section->selected = isset($my_votes[$section->id]) ? $my_votes[$section->id] : 0;
        
        $post = Post::get($post_id);
        $post->section_title = $section_title;
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
        $anon = Anon::make(Input::get('captcha'));

        if(!$anon->success) {
            return Redirect::refresh()->withErrors($anon->errorMessage())->withInput();
        }

        $post = Post::make(
            Input::get('section',  ''),
            Input::get('data',     ''),
            Input::get('title',    ''),
            Input::get('url',      ''),
            Input::get('nsfw-tag', 0),
            Input::get('nsfl-tag', 0)
        );

        if($post->success) {
            $location = sprintf("/s/%s/posts/%s/%s", $post->data->section_title, $post->data->item_id, $post->data->item_title);
        
            return Redirect::to($location);
        } else {
            return Redirect::back()->withErrors($post->errorMessage())->withInput();
        }
    }

    protected function postJson($section_title)
    {
        $post = Post::make(
            Input::get('section', ''),
            Input::get('data',    ''),
            Input::get('title',   ''),
            Input::get('url',     ''),
            Input::get('nsfw-tag', 0),
            Input::get('nsfl-tag', 0)
        );

        return Response::json([
            'success' => $post->success,
            'errors'  => $post->errors,
            'item_id' => $post->data->item_id
        ]);
    }

    protected function update($post_id)
    {
        $post = Post::amend($post_id, Input::get('data'));

        if($post->success) {
            return Redirect::to($post->data->prev_path);
        } else {
            return Redirect::to($post->data->prev_path)->withErrors($post->errorMessage())->withInput();
        }
    }

    protected function delete($post_id)
    {
        $post = Post::remove($post_id);

        if($post->success) {
            return Redirect::to("/s/" . $post->data->section_title);
        } else {
            return Redirect::to($post->data->prev_path)->withErrors($post->errorMessage());
        }
    }
}
