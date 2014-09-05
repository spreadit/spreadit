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

    protected function update($comment_id)
    {
        return Comment::amend($comment_id, Input::get('data'));
    }

    protected function post($section_title, $post_id)
    {
        $anon = Anon::make(Input::get('captcha'));
        if($anon == "success") {
            return Comment::make($post_id, Input::get('data'), Input::get('parent_id'));
        } else return $anon;
    }

    protected function delete($comment_id)
    {
        return Comment::remove($comment_id);
    }
}
