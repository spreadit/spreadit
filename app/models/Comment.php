<?php

class Comment extends BaseModel
{
    protected $table = 'comments';

    protected $guarded = array('id');

    public function getPathDataFromId($comment_id)
    {
        return Cache::remember(Constant::COMMENT_CACHE_PATH_DATA_FROM_ID_NAME.$comment_id, Constant::COMMENT_CACHE_PATH_DATA_FROM_ID_MINS, function() use($comment_id)
        {
            $comment = DB::table('comments')
                ->join('posts', 'comments.post_id', '=', 'posts.id')
                ->join('sections', 'posts.section_id', '=', 'sections.id')
                ->select('posts.id', 'sections.title AS section_title')
                ->where('comments.id', '=', $comment_id)
                ->first();

            if(is_null($comment)) {
                App::abort(404);
            }

            $obj = new stdClass();
            $obj->section_title = $comment->section_title;
            $obj->post_id = $comment->id;
            return $obj;
        });
    }

    public function get($comment_id, Vote $vote)
    {
        $comment = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->select('comments.id', 'comments.post_id', 'comments.user_id', 'comments.created_at', 'comments.updated_at', 'comments.deleted_at', 'comments.upvotes', 'comments.downvotes', 'comments.parent_id', 'comments.data', 'comments.markdown', 'users.username', 'users.points', 'users.id AS users_user_id', 'users.votes', 'users.anonymous')
            ->where('comments.id', '=', $comment_id)
            ->orderBy('id', 'asc')
            ->first();

        if(is_null($comment)) {
            App::abort(404);
        }

        if( $comment->deleted_at != 0) {
            $comment->username = "deleted";
            $comment->data = "<p>user deleted this comment</p>";
            $comment->markdown = "user deleted this comment";
        }

        return $vote->applySelection([$comment], $vote->COMMENT_TYPE)[0];
    }

    public function getByPostId($post_id, Vote $vote)
    {
        $comments = Cache::remember(Constant::COMMENT_CACHE_NEWLIST_NAME.$post_id, Constant::COMMENT_CACHE_NEWLIST_MINS, function() use($post_id)
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

        return $vote->applySelection($comments, $vote->COMMENT_TYPE);
    }

    public function getCommentsInTimeoutRange()
    {
        $user_id = (isset(Auth::user()->id)) ? Auth::user()->id : -1;

        return DB::table('comments')
            ->select('id')
            ->where('comments.user_id', '=', $user_id)
            ->where('comments.created_at', '>', time() - Constant::COMMENT_MAX_COMMENTS_TIMEOUT_SECONDS)
            ->count();
    }

    public function canPost()
    {
        return Utility::remainingComments() > 0;
    }

    public function make($post_id, $content, $parent_id)
    {
        $block = new SuccessBlock();
        $block->data->comment_id = -1;

        if($block->success) {
            if(Auth::user()->points < 1) {
                $block->success = false;
                $block->errors[] = 'You need at least one point to post a comment';
            }
        }

        if($block->success) {
            if(!$this->canPost()) {
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
                'markdown'  => 'required|max:'.Constant::COMMENT_MAX_MARKDOWN_LENGTH
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
            if($data['parent_id'] != Constant::COMMENT_NO_PARENT) { 
                $parent = $this->findOrFail($data['parent_id']);
                $notification->type = Constant::NOTIFICATION_COMMENT_TYPE;
                $notification->user_id = $parent->user_id;
            } else {
                $notification->type = Constant::NOTIFICATION_POST_TYPE;
                $notification->user_id = $post->user_id;
            }

            $comment = new Comment($data);
            $comment->save();
            $post->increment('comment_count');

            $notification->item_id = $comment->id;
            $block->data->comment_id = $comment->id;
            if($notification->user_id != Auth::user()->id) {
                $notification->save();
            }

            Cache::forget(Constant::COMMENT_CACHE_NEWLIST_NAME.$post_id);
        }

        return $block;
    }

    public function amend($comment_id, $content)
    {
        $block = new SuccessBlock();

        if($block->success) {
            if(Auth::user()->points < 1) {
                $block->success  = false;
                $block->errors[] = 'You need at least one point to edit a comment';
            }
        }

        if($block->success) {
            $comment = $this->findOrFail($comment_id);

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
                'markdown' => 'required|max:'.Constant::COMMENT_MAX_MARKDOWN_LENGTH
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
            $history->type     = Constant::COMMENT_TYPE;
            $history->type_id  = $comment->id;
            $history->save();

            Cache::forget(Constant::COMMENT_CACHE_NEWLIST_NAME.$comment->post_id);

            $comment->markdown = $data['markdown'];
            $comment->data = $data['data'];
            $comment->save();
        }

        return $block;
    }

    public function remove($comment_id)
    {
        $block = new SuccessBlock();

        $comment = $this->findOrFail($comment_id);
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
            Cache::forget(Constant::COMMENT_CACHE_NEWLIST_NAME.$comment->post_id);
            $comment->deleted_at = time();
            $comment->save();
        }

        return $block;
    }

}
