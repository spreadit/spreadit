<?php
use \Functional as F;
use \Michelf\MarkdownExtra;

class Comment extends BaseModel
{
    protected $table = 'comments';

    protected $guarded = array('id');


    const NO_PARENT = 0;
    const CACHE_PATH_DATA_FROM_ID_MINS = SortController::YEAR_SECONDS;
    const CACHE_PATH_DATA_FROM_ID_NAME = 'comment_path_from_id_';
    const CACHE_NEWLIST_MINS = 1;
    const CACHE_NEWLIST_NAME = 'comment_newlist_id_';

    const MAX_MARKDOWN_LENGTH = 4000;
    const MAX_COMMENTS_PER_DAY = 30;
    const MAX_COMMENTS_TIMEOUT_SECONDS = 86400;

    public static function getPathDataFromId($comment_id)
    {
        return Cache::remember(self::CACHE_PATH_DATA_FROM_ID_NAME.$comment_id, self::CACHE_PATH_DATA_FROM_ID_MINS, function() use($comment_id)
        {
            $comment = DB::table('comments')
                ->join('posts', 'comments.post_id', '=', 'posts.id')
                ->join('sections', 'posts.section_id', '=', 'sections.id')
                ->select('posts.id', 'sections.title')
                ->where('comments.id', '=', $comment_id)
                ->first();

            $obj = new stdClass();
            $obj->section_title = $comment->title;
            $obj->post_id = $comment->id;
            return $obj;
        });
    }

    public static function get($post_id)
    {
        $comments = Cache::remember(self::CACHE_NEWLIST_NAME.$post_id, self::CACHE_NEWLIST_MINS, function() use($post_id)
        {
            return DB::table('comments')
                ->join('users', 'comments.user_id', '=', 'users.id')
                ->select('comments.id', 'comments.post_id', 'comments.user_id', 'comments.created_at', 'comments.updated_at', 'comments.deleted_at', 'comments.upvotes', 'comments.downvotes', 'comments.parent_id', 'comments.data', 'comments.markdown', 'users.username', 'users.points', 'users.id AS users_user_id', 'users.votes', 'users.anonymous')
                ->where('post_id', '=', $post_id)
                ->orderBy('id', 'asc')
                ->get();
        });

        F\each($comments, function($v) {
            if($v->deleted_at != 0) {
                $v->username = "deleted";
                $v->data = "<p>user deleted this comment</p>";
                $v->markdown = "user deleted this comment";
            }
            return $v;
        });

        return Vote::applySelection($comments, Vote::COMMENT_TYPE);
    }

    public static function getCommentsInTimeoutRange()
    {
        $user_id = (isset(Auth::user()->id)) ? Auth::user()->id : -1;

        return DB::table('comments')
            ->select('id')
            ->where('comments.user_id', '=', $user_id)
            ->where('comments.created_at', '>', time() - self::MAX_COMMENTS_TIMEOUT_SECONDS)
            ->count();
    }

    public static function canPost()
    {
        return (self::getCommentsInTimeoutRange() <= self::MAX_COMMENTS_PER_DAY);
    }

    public static function make($post_id, $content, $parent_id)
    {
        $success = true;
        $errors = [];

        if($success) {
            if(Auth::user()->points < 1) {
                $success = false;
                $errors[] = ['You need at least one point to post a comment'];
            }
        }

        if($success) {
            if(!Comment::canPost()) {
                $success = false;
                $errors[] = 'can only post ' . self::MAX_COMMENTS_PER_DAY . ' per day';
            }
        }

        if($success) {
            $data = [
                'data'      => MarkdownExtra::defaultTransform(e($content)),
                'parent_id' => $parent_id,
                'user_id'   => Auth::user()->id,
                'post_id'   => $post_id,
                'markdown'  => $content,
            ];

            $rules = array(
                'user_id'   => 'required|numeric',
                'parent_id' => 'required|numeric',
                'post_id'   => 'required|numeric',
                'markdown'  => 'required|max:'.self::MAX_MARKDOWN_LENGTH
            );

            $validate = Validator::make($data, $rules);
            if($validate->fails()) {
                $success = false;

                foreach($validate->messages()->all() as $v) {
                    $errors[] = $v;
                }
            }
        }

        if($success) {
            $post = Post::findOrFail($data['post_id']);
        
            $notification = new Notification();
            if($data['parent_id'] != self::NO_PARENT) { 
                $parent = Comment::findOrFail($data['parent_id']);
                $notification->type = Notification::COMMENT_TYPE;
                $notification->user_id = $parent->user_id;
            } else {
                $notification->type = Notification::POST_TYPE;
                $notification->user_id = $post->user_id;
            }

            $comment = new Comment($data);
            $comment->save();
            $post->increment('comment_count');

            $notification->item_id = $comment->id;
            if($notification->user_id != Auth::user()->id) {
                $notification->save();
            }

            Cache::forget(self::CACHE_NEWLIST_NAME.$post_id);
        }

        $block = new SuccessBlock();
        $block->success = $success;
        $block->errors = $errors;

        return $block;
    }

    public static function amend($comment_id, $content)
    {
        $success = true;
        $errors = [];

        if($success) {
            if(Auth::user()->points < 1) {
                $success = false;
                $errors[] = 'You need at least one point to edit a comment';
            }
        }

        if($success) {
            $comment = Comment::findOrFail($comment_id);

            if($comment->user_id != Auth::user()->id) {
                $success = true;
                $errors[] = 'This comment does not have the same user id as you';
            }
        }

        if($success) {
            $data['user_id'] = Auth::user()->id;
            $data['data'] = $content;
            $data['markdown'] = $content;
            $data['data'] = MarkdownExtra::defaultTransform(e($content));

            $rules = array(
                'user_id' => 'required|numeric',
                'markdown' => 'required|max:'.self::MAX_MARKDOWN_LENGTH
            );

            $validate = Validator::make($data, $rules);

            if($validate->fails()) {
                $success = false;

                foreach($validate->messages()->all() as $v) {            
                    $errors[] = $v;
                }
            }
        }

        if($success) {
            $history = new History;
            $history->data     = $comment->data;
            $history->markdown = $comment->markdown;
            $history->user_id  = Auth::user()->id;
            $history->type     = HistoryController::COMMENT_TYPE;
            $history->type_id  = $comment->id;
            $history->save();

            Cache::forget(self::CACHE_NEWLIST_NAME.$comment->post_id);

            $comment->markdown = $data['markdown'];
            $comment->data = $data['data'];
            $comment->save();
        }

        $block = new SuccessBlock();
        $block->success = $success;
        $block->errors = $errors;

        return $block;
    }

    public static function remove($comment_id)
    {
        $success = true;
        $errors = [];

        $comment = Comment::findOrFail($comment_id);
        $post_id = $comment->post_id;

        if($success) {
            if(Auth::user()->points < 1) {
                $success = false;
    
                $errors[] = 'You need at least one point to delete a comment';
            }
        }

        if($success) {
            if($comment->user_id != Auth::user()->id) {
                $success = false;
                $errors[] = 'This comment does not have the same user id as you';
            }
        }

        if($success) {
            Cache::forget(self::CACHE_NEWLIST_NAME.$comment->post_id);
            $comment->deleted_at = time();
            $comment->save();
        }

        $block = new SuccessBlock();
        $block->success = $success;
        $block->errors = $errors;
        $block->data->post_id = $post_id;

        return $block;
    }

}
