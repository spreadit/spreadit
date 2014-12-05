<?php
class CommentController extends BaseController
{
    protected function getRedir($comment_id)
    {
		$data = Comment::getPathDataFromId($comment_id);
		return Redirect::to('/s/'.$data->section_title.'/posts/'.$data->post_id.'#comment_'.$comment_id);
    }

    protected function preReply($post_id, $parent_id)
    {
        if(Auth::check()) {
            return $this->curReply($post_id, $parent_id);
        }

        $comment = new stdClass;
        $comment->post_id = $post_id;
        $comment->parent_id = $parent_id;

        return View::make('comment.before', ['comment' => $comment]);
    }

    protected function curReply($post_id, $parent_id)
    {
        $comment = new stdClass;
        $comment->post_id = $post_id;
        $comment->parent_id = $parent_id;
        $comment->form_action = URL::to('/comments/' . $comment->parent_id . '/create');

        return View::make('comment.replybox', ['comment' => $comment]);
    }

    //for javascript
    protected function formReply($post_id, $parent_id)
    {
        $comment = new stdClass;
        $comment->post_id = $post_id;
        $comment->parent_id = $parent_id;
        $comment->form_action = URL::to('/comments/' . $comment->parent_id . '/create');

        return View::make('comment.replyboxform', ['comment' => $comment]);
    }

    protected function postReply($post_id, $parent_id)
    {
        return View::make('comment.saved');
    }

    protected function update($comment_id)
    {
        $comment = Comment::amend($comment_id, Input::get('data'));

        if($comment->success) {
            return Redirect::to("/comments/$comment_id");
        } else {
            return Redirect::to("/comments/$comment_id")->withErrors($comment->errorMessage())->withInput();
        }
    }

    protected function make()
    {
        $anon = Anon::make(Input::get('captcha'));

        if($anon->success) {
            $comment = Comment::make(Input::get('post_id'), Input::get('data'), Input::get('parent_id'));
            
            if($comment->success) {
                return Redirect::to(sprintf('/comments/post/%d/%d', Input::get('post_id'), Input::get('parent_id')));
            } else {
                return Redirect::back()->withErrors($comment->errorMessage())->withInput();
            }
        } else {
            return Redirect::back()->withErrors($anon->errorMessage())->withInput(); 
        }
    }

    protected function makeJson()
    {
        $anon = Anon::make(Input::get('captcha'));

        if($anon->success) {
            $comment = Comment::make(Input::get('post_id'), Input::get('data'), Input::get('parent_id'));

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


    protected function delete($comment_id)
    {
        $comment = Comment::remove($comment_id);

        if($comment->success) {
            return Redirect::to("/posts/" . $comment->data->post_id);
        } else {
            return Redirect::to("/posts/" . $comment->data->post_id)->withErrors($comment->errorMessage())->withInput();
        }
    }

    protected function render($comment_id)
    {
        $comment = Comment::get($comment_id);
        return View::make('comment.piece', ['comment' => $comment]);
    }

    protected function newCaptcha()
    {
        $response = Response::make(HTML::image(Captcha::img(), 'Captcha image'));
        $response->header('Content-Type', 'text/html');

        return $response;
    }
}
