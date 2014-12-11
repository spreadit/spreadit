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
        $this->post     = $post;
        $this->section  = $section;
        $this->vote     = $vote;
        $this->comment  = $comment;
        $this->anon     = $anon;
    }


    /**
     * renders a post page
     *
     * @param string  $section_title
     * @param int     $post_id
     * @return Illuminate\View\View
     */
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

    /**
     * see `get`
     *
     * @param string  $section_title
     * @param int     $post_id
     * @return Illuminate\Http\JsonResponse
     */
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


    /**
     * redirects you to the real url from just a post id
     *
     * @param int  $post_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function getRedir($post_id)
    {
        $post = $this->post->findOrFail($post_id);
        $section_title = $this->section->findOrFail($post->section_id)->title;
        
        return Redirect::to("/s/$section_title/posts/$post_id");
    }


    /**
     * creates a new post
     * logs you in as anonymous if you are logged out
     * redirects to new post on success or back on error
     *
     * @param string  $section_title
     * @return Illuminate\Http\RedirectResponse
     */
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


    /**
     * see `post`
     * logs you in as anonymous if you are logged out
     * note: if using api and not logged in you will not be able to process captcha
     *
     * @param string  $section_title
     * @return Illuminate\Http\JsonResponse
     */
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


    /**
     * updates a post you have created
     * redirects to prior location on success or error
     *
     * @param string  $post_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function update($post_id)
    {
        $post = $this->post->amend($post_id, Input::get('data'));

        if($post->success) {
            return Redirect::to($post->data->prev_path);
        } else {
            return Redirect::to($post->data->prev_path)->withErrors($post->errorMessage())->withInput();
        }
    }


    /**
     * see `update`
     *
     * @param int  $post_id
     * @return Illuminate\Http\JsonResponse
     */
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


    /**
     * deletes a post you have created
     * redirects to section on success or to prior location on error
     *
     * @param int  $post_id
     * @return Illuminate\Http\RedirectResponse
     */
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
