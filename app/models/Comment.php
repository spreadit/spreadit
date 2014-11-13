<?php

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
    const MAX_COMMENTS_TIMEOUT_SECONDS = 86400;

    public static function getPathDataFromId($comment_id)
    {
        return Cache::remember(self::CACHE_PATH_DATA_FROM_ID_NAME.$comment_id, self::CACHE_PATH_DATA_FROM_ID_MINS, function() use($comment_id)
        {
            $comment = DB::table('comments')
                ->join('posts', 'comments.post_id', '=', 'posts.id')
                ->join('sections', 'posts.section_id', '=', 'sections.id')
                ->select('posts.id', 'sections.title AS section_title')
                ->where('comments.id', '=', $comment_id)
                ->first();

            $obj = new stdClass();
            $obj->section_title = $comment->section_title;
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

        F::each($comments, function($v) {
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
        return Utility::remainingComments() > 0;
    }

    public static function make($post_id, $content, $parent_id)
    {
        $block = new SuccessBlock();

        if($block->success) {
            if(Auth::user()->points < 1) {
                $block->success = false;
                $block->errors[] = 'You need at least one point to post a comment';
            }
        }

        if($block->success) {
            if(!Comment::canPost()) {
                $block->success = false;
                $block->errors[] = 'can only post ' . Utility::availableComments() . ' per day';
            }
        }

        if($block->success) {
            $data = [
                'data'      => Markdown::defaultTransform(e($content)),
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
                $block->success = false;

                foreach($validate->messages()->all() as $v) {
                    $block->errors[] = $v;
                }
            }
        }

        if($block->success) {
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

        return $block;
    }

    public static function amend($comment_id, $content)
    {
        $block = new SuccessBlock();

        if($block->success) {
            if(Auth::user()->points < 1) {
                $block->success  = false;
                $block->errors[] = 'You need at least one point to edit a comment';
            }
        }

        if($block->success) {
            $comment = Comment::findOrFail($comment_id);

            if($comment->user_id != Auth::user()->id) {
                $block->success  = true;
                $block->errors[] = 'This comment does not have the same user id as you';
            }
        }

        if($block->success) {
            $data['user_id']  = Auth::user()->id;
            $data['data']     = Markdown::defaultTransform(e($content));
            $data['markdown'] = $content;

            $rules = array(
                'user_id'  => 'required|numeric',
                'markdown' => 'required|max:'.self::MAX_MARKDOWN_LENGTH
            );

            $validate = Validator::make($data, $rules);

            if($validate->fails()) {
                $block->success = false;

                foreach($validate->messages()->all() as $v) {            
                    $block->errors[] = $v;
                }
            }
        }

        if($block->success) {
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

        return $block;
    }

    public static function remove($comment_id)
    {
        $block = new SuccessBlock();

        $comment = Comment::findOrFail($comment_id);
        $block->data->post_id = $comment->post_id;

        if($block->success) {
            if(Auth::user()->points < 1) {
                $block->success = false;
                $block->errors[]       = 'You need at least one point to delete a comment';
            }
        }

        if($block->success) {
            if($comment->user_id != Auth::user()->id) {
                $block->success  = false;
                $block->errors[] = 'This comment does not have the same user id as you';
            }
        }

        if($block->success) {
            Cache::forget(self::CACHE_NEWLIST_NAME.$comment->post_id);
            $comment->deleted_at = time();
            $comment->save();
        }

        return $block;
    }

}
