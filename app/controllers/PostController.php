<?php

class PostController extends BaseController
{
    protected $post;
    protected $section;
    protected $vote;
    protected $comment;
    protected $anon;

    public function __construct(Post $post, Section $section, Vote $vote, Comment $comment, Anon $anon)
    {
        $this->post = $post;
        $this->section = $section;
        $this->vote = $vote;
        $this->comment = $comment;
        $this->anon = $anon;
    }

    public function get($section_title, $post_id)
    {
        $section = $this->section->sectionFromSections($this->section->getByTitle(Utility::splitByTitle($section_title)));
        $section->selected = $this->vote->getSelected(Constant::SECTION_TYPE, $section);
        
        $post = $this->post->get($post_id);
        $post->section_title = $section_title;
        $post->selected = $this->vote->getSelected(Constant::POST_TYPE, $post);
        
        $commentTree = new CommentTree($this->comment->getByPostId($post_id, $this->vote));

        return View::make('page.post', [
            'section'                  => $section,
            'sections'                 => $this->section->get(),
            'comments'                 => $commentTree->grab()->sort('new')->render(),
            'post'                     => $post,
            'sort_highlight'           => Utility::getSortMode(),
            'sort_timeframe_highlight' => Utility::getSortTimeframe(),
        ]);
    }

    public function getJson($section_title, $post_id)
    {
		$post = $this->post->get($post_id);
        $post->selected = $this->vote->getSelected(Constant::POST_TYPE, $post);

        $commentTree = new CommentTree($this->comment->getByPostId($post_id, $this->vote));

		return Response::json([
			'post' => $post,
			'comments' => $commentTree->grab()->sort('new')
        ]);
    }

    public function getRedir($post_id)
    {
        $post = $this->post->findOrFail($post_id);
        $section_title = $this->section->findOrFail($post->section_id)->title;
        
        return Redirect::to("/s/$section_title/posts/$post_id");
    }

    public function post($section_title)
    {
        $anon = $this->anon->make(Input::get('captcha'));

        if(!$anon->success) {
            return Redirect::refresh()->withErrors($anon->errorMessage())->withInput();
        }

        $post = $this->post->make(
            Input::get('section',  ''),
            Input::get('data',     ''),
            Input::get('title',    ''),
            Input::get('url',      ''),
            Input::get('nsfw-tag', 0),
            Input::get('nsfl-tag', 0),
            $this->section
        );

        if($post->success) {
            $location = sprintf("/s/%s/posts/%s/%s", $post->data->section_title, $post->data->item_id, $post->data->item_title);
        
            return Redirect::to($location);
        } else {
            return Redirect::back()->withErrors($post->errorMessage())->withInput();
        }
    }

    public function postJson($section_title)
    {
        try {
            Route::callRouteFilter('auth.token', array(), Route::current(), Request::instance());
        } catch(Tappleby\AuthToken\Exceptions\NotAuthorizedException $e) {
            $anon = $this->anon->make(Input::get('captcha'));
            
            if(!$anon->success) {
                return Response::json([
                    'success' => false,
                    'errors'  => [$anon->errorMessage()],
                    'item_id' => NULL
                ]);
            }
        }

        $post = $this->post->make(
            Input::get('section', ''),
            Input::get('data',    ''),
            Input::get('title',   ''),
            Input::get('url',     ''),
            Input::get('nsfw-tag', 0),
            Input::get('nsfl-tag', 0),
            $this->section
        );

        return Response::json([
            'success' => $post->success,
            'errors'  => $post->errors,
            'item_id' => $post->data->item_id
        ]);
    }

    public function update($post_id)
    {
        $post = $this->post->amend($post_id, Input::get('data'));

        if($post->success) {
            return Redirect::to($post->data->prev_path);
        } else {
            return Redirect::to($post->data->prev_path)->withErrors($post->errorMessage())->withInput();
        }
    }

    public function updateJson($post_id)
    {
        $post = $this->post->amend($post_id, Input::get('data'));

        if($post->success) {
            return Response::json([
                'success' => true,
                'errors'  => [],
            ]);
        } else {
            return Response::json([
                'success' => false,
                'errors'  => [$post->errorMessage()],
            ]);
        }
    }

    public function delete($post_id)
    {
        $post = $this->post->remove($post_id);

        if($post->success) {
            return Redirect::to("/s/" . $post->data->section_title);
        } else {
            return Redirect::to($post->data->prev_path)->withErrors($post->errorMessage());
        }
    }
}
