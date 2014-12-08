<?php
class CommentController extends BaseController
{

    protected $comment;
    protected $anon;
    protected $vote;

    public function __construct(Comment $comment, Anon $anon, Vote $vote)
    {
        $this->comment = $comment;
        $this->anon = $anon;
        $this->vote = $vote;
    }

    public function getRedir($comment_id)
    {
		$data = $this->comment->getPathDataFromId($comment_id);
		return Redirect::to('/s/'.$data->section_title.'/posts/'.$data->post_id.'#comment_'.$comment_id);
    }

    public function preReply($post_id, $parent_id)
    {
        if(Auth::check()) {
            return $this->curReply($post_id, $parent_id);
        }

        $comment = new stdClass;
        $comment->post_id = $post_id;
        $comment->parent_id = $parent_id;

        return View::make('comment.before', ['comment' => $comment]);
    }

    public function curReply($post_id, $parent_id)
    {
        $comment = new stdClass;
        $comment->post_id = $post_id;
        $comment->parent_id = $parent_id;
        $comment->form_action = URL::to('/comments/' . $comment->parent_id . '/create');

        return View::make('comment.replybox', ['comment' => $comment]);
    }

    //for javascript
    public function formReply($post_id, $parent_id)
    {
        $comment = new stdClass;
        $comment->post_id = $post_id;
        $comment->parent_id = $parent_id;
        $comment->form_action = URL::to('/comments/' . $comment->parent_id . '/create');

        return View::make('comment.replyboxform', ['comment' => $comment]);
    }

    public function postReply($post_id, $parent_id)
    {
        return View::make('comment.saved');
    }

    public function update($comment_id)
    {
        $comment = $this->comment->amend($comment_id, Input::get('data'));

        if($comment->success) {
            return Redirect::to("/comments/$comment_id");
        } else {
            return Redirect::to("/comments/$comment_id")->withErrors($comment->errorMessage())->withInput();
        }
    }

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


    public function delete($comment_id)
    {
        $comment = $this->comment->remove($comment_id);

        if($comment->success) {
            return Redirect::to("/posts/" . $comment->data->post_id);
        } else {
            return Redirect::to("/posts/" . $comment->data->post_id)->withErrors($comment->errorMessage())->withInput();
        }
    }

    public function render($comment_id)
    {
        $comment = $this->comment->get($comment_id, $this->vote);
        return View::make('comment.piece', ['comment' => $comment]);
    }

    public function newCaptcha()
    {
        $response = Response::make(HTML::image(Captcha::img(), 'Captcha image'));
        $response->header('Content-Type', 'text/html');

        return $response;
    }
}
