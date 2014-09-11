<?php
use \Michelf\MarkdownExtra;
use \Functional as F;

class CommentController extends BaseController
{
    protected function getRedir($comment_id)
    {
		$data = Comment::getPathDataFromId($comment_id);
		return Redirect::to('/s/'.$data->section_title.'/posts/'.$data->post_id.'#comment_'.$comment_id);
    }

    protected function preReply()
    {
        return Response::make('loading...');
    }

    protected function curReply()
    {
        $comment = new stdClass;
        $comment->parent_id = Input::get('parent_id');
        $comment->post_id = Input::get('post_id');
        $comment->form_action = URL::to('/comments/' . $comment->parent_id . '/create');

        return View::make('commentreplybox', ['comment' => $comment]);
    }

    protected function postReply()
    {
        return Response::make('saved');
    }

    protected function update($comment_id)
    {
        $comment = Comment::amend($comment_id, Input::get('data'));

        if($comment->success) {
            return Redirect::to('/comments/'.$comment_id);
        } else {
            return Redirect::to('/comments/'.$comment_id)->withErrors($comment->errorMessage())->withInput();
        }
    }

    protected function make()
    {
        $anon = Anon::make(Input::get('captcha'));

        if($anon->success) {
            $comment = Comment::make(Input::get('post_id'), Input::get('data'), Input::get('parent_id'));
            
            if($comment->success) {
                return Redirect::to('/comments/post');
            } else {
                return Redirect::back()->withErrors($errors)->withInput();
            }
        } else {
            return Redirect::back()->withErrors($anon->errorMessage())->withInput(); 
        }
    }


    protected function delete($comment_id)
    {
        $comment = Comment::remove($comment_id);

        if($comment->success) {
            return Redirect::to("/posts/$post_id");
        } else {
            return Redirect::to("/posts/$post_id")->withErrors($comment->errorMessage())->withInput();
        }
    }
}
