<?php
use \Michelf\MarkdownExtra;
use \Functional as F;

class CommentController extends BaseController
{
    protected function get($comment_id)
    {
		$data = CommentController::getPathDataFromId($comment_id);
		return Redirect::to('/s/'.$data->section_title.'/posts/'.$data->post_id.'#comment_'.$comment_id);
    }

    public static function update($comment_id)
    {
        if(Auth::user()->points < 1) {
            return Redirect::to('/comments/'.$comment_id)->withErrors(['message' => 'You need at least one point to edit a comment']);
        }

        $comment = Comment::findOrFail($comment_id);

        if($comment->user_id != Auth::id()) {
            return Redirect::to('/comments/'.$comment_id)->withErrors(['message' => 'This comment does not have the same user id as you']);
        }

        $data['user_id'] = Auth::id();
        $data['data'] = Input::only('data')['data'];
        $data['markdown'] = $data['data'];
        $data['data'] = MarkdownExtra::defaultTransform(e($data['markdown']));

        $rules = array(
            'user_id' => 'required|numeric',
            'markdown' => 'required|max:'.self::MAX_MARKDOWN_LENGTH
        );

        $validate = Validator::make($data, $rules);
        if($validate->fails()) {
            return Redirect::to('/comments/'.$comment_id)->withErrors($validate->messages())->withInput();
        }

        $history = new History;
        $history->data     = $comment->data;
        $history->markdown = $comment->markdown;
        $history->user_id  = Auth::id();
        $history->type     = HistoryController::COMMENT_TYPE;
        $history->type_id  = $comment->id;
        $history->save();

        Cache::forget(self::CACHE_NEWLIST_NAME.$comment->post_id);

        $comment->markdown = $data['markdown'];
        $comment->data = $data['data'];
        $comment->save();

        return Redirect::to('/comments/'.$comment_id);
    }

    public static function post($section_title, $post_id)
    {
        if(Auth::user()->points < 1) {
            return Redirect::refresh()->withErrors(['message' => 'You need at least one point to post a comment']);
        }

        if(!self::canPost()) {
            return Redirect::refresh()->withErrors(['message' => 'can only post ' . self::MAX_COMMENTS_PER_DAY . ' per day'])->withInput();
        }

        $data = array_merge(
            Input::only('data', 'parent_id'),
            array(
                'user_id' => Auth::id(),
                'post_id' => $post_id
            )
        );
        $data['markdown'] = $data['data'];
        $data['data'] = MarkdownExtra::defaultTransform(e($data['markdown']));

        $rules = array(
            'user_id' => 'required|numeric',
            'parent_id' => 'required|numeric',
            'post_id' => 'required|numeric',
            'markdown' => 'required|max:'.self::MAX_MARKDOWN_LENGTH
        );

        $validate = Validator::make($data, $rules);
        if($validate->fails()) {
            return Redirect::refresh()->withErrors($validate->messages())->withInput();
        }

        $post = Post::findOrFail($data['post_id']);
        
        $notification = new Notification();
        if($data['parent_id'] != self::NO_PARENT) { 
            $parent = Comment::findOrFail($data['parent_id']);
            $notification->type = NotificationController::COMMENT_TYPE;
            $notification->user_id = $parent->user_id;
        } else {
            $notification->type = NotificationController::POST_TYPE;
            $notification->user_id = $post->user_id;
        }

        $comment = new Comment($data);
        $comment->save();
        $post->increment('comment_count');

        $notification->item_id = $comment->id;
        if($notification->user_id != Auth::id()) {
            $notification->save();
        }
                
        Cache::forget(self::CACHE_NEWLIST_NAME.$post_id);
        return Redirect::refresh();
    }
}
