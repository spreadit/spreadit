<?php
class CommentController extends BaseController
{

    protected $comment;
    protected $anon;
    protected $vote;

    public function __construct(Comment $comment, Anon $anon, Vote $vote)
    {
        $this->comment = $comment;
        $this->anon    = $anon;
        $this->vote    = $vote;
    }

    /**
     * redirects to the post where the comment exists
     *
     * @param int  $comment_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function getRedir($comment_id)
    {
		$data = $this->comment->getPathDataFromId($comment_id);
		return Redirect::to('/s/'.$data->section_title.'/posts/'.$data->post_id.'#comment_'.$comment_id);
    }


    /**
     * for no-js users, this generates the prior to comment box
     * this saves time by not generating N captchas per page in case 
     * of an anonymous user
     *
     * @param int  $post_id
     * @param int  $comment_id
     * @return Illuminate\View\View
     */
    public function preReply($post_id, $parent_id)
    {
        if(Auth::check()) {
            return $this->curReply($post_id, $parent_id);
        }

        $comment = new stdClass;
        $comment->post_id   = $post_id;
        $comment->parent_id = $parent_id;

        return View::make('comment.before', ['comment' => $comment]);
    }


    /**
     * for no-js users, this generates the comment form / reply box
     *
     * @param int  $post_id
     * @param int  $comment_id
     * @return Illuminate\View\View
     */
    public function curReply($post_id, $parent_id)
    {
        $comment = new stdClass;
        $comment->post_id     = $post_id;
        $comment->parent_id   = $parent_id;
        $comment->form_action = URL::to('/comments/' . $comment->parent_id . '/create');

        return View::make('comment.replybox', ['comment' => $comment]);
    }


    /**
     * for no-js users, this shows a thank you page for commenting
     *
     * @param int  $post_id
     * @param int  $comment_id
     * @return Illuminate\View\View
     */
    public function postReply($post_id, $parent_id)
    {
        return View::make('comment.saved');
    }


    /**
     * for javascript users, this generates the comment form / reply box
     *
     * @param int  $post_id
     * @param int  $comment_id
     * @return Illuminate\View\View
     */
    public function formReply($post_id, $parent_id)
    {
        $comment = new stdClass;
        $comment->post_id     = $post_id;
        $comment->parent_id   = $parent_id;
        $comment->form_action = URL::to('/comments/' . $comment->parent_id . '/create');

        return View::make('comment.replyboxform', ['comment' => $comment]);
    }


    /**
     * updates a comment with new data
     *
     * @param int  $comment_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function update($comment_id)
    {
        $comment = $this->comment->amend($comment_id, Input::get('data'));

        if($comment->success) {
            return Redirect::to("/comments/$comment_id");
        } else {
            return Redirect::to("/comments/$comment_id")->withErrors($comment->errorMessage())->withInput();
        }
    }


    /**
     * see `update`
     *
     * @param int  $comment_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function updateJson($comment_id)
    {
        $comment = $this->comment->amend($comment_id, Input::get('data'));

        if($comment->success) {
            return Response::json([
                'success' => true,
                'errors'  => [],
            ]);
        } else {
            return Response::json([
                'success' => false,
                'errors'  => [$comment->errorMessage()],
            ]);
        }
    }


    /**
     * creates a new comment
     * logs in user as anon if he passes captcha correctly
     * and redirects to comment success page on success or back on error
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function make()
    {
        $anon = $this->anon->make(Input::get('captcha'));

        if($anon->success) {
            $comment = $this->comment->make(Input::get('post_id'), Input::get('data'), Input::get('parent_id'));
            
            if($comment->success) {
                return Redirect::to(sprintf('/comments/post/%d/%d', Input::get('post_id'), Input::get('parent_id')));
            } else {
                return Redirect::back()->withErrors($comment->errorMessage())->withInput();
            }
        } else {
            return Redirect::back()->withErrors($anon->errorMessage())->withInput(); 
        }
    }


    /**
     * see `make`
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function makeJson()
    {
        $anon = $this->anon->make(Input::get('captcha'));

        if($anon->success) {
            $comment = $this->comment->make(Input::get('post_id'), Input::get('data'), Input::get('parent_id'));

            return Response::json([
                'success'    => $comment->success,
                'errors'     => $comment->errors,
                'comment_id' => $comment->data->comment_id
            ]);
        } else {
            return Response::json([
                'success'    => false,
                'errors'     => [$anon->errorMessage()],
                'comment_id' => -1
            ]);
        }
    }


    /**
     * deletes a comment that you have previously created
     * redirects to comments's post page on success or error
     *
     * @param int  $comment_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete($comment_id)
    {
        $comment = $this->comment->remove($comment_id);

        if($comment->success) {
            return Redirect::to("/posts/" . $comment->data->post_id);
        } else {
            return Redirect::to("/posts/" . $comment->data->post_id)->withErrors($comment->errorMessage())->withInput();
        }
    }


    /**
     * renders a single comments html
     *
     * @param int  $comment_id
     * @return Illuminate\View\View
     */
    public function render($comment_id)
    {
        $comment = $this->comment->get($comment_id, $this->vote);
        return View::make('comment.piece', ['comment' => $comment]);
    }
}
