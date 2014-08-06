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

    public static function getSourceFromId($id)
    {
        return Comment::findOrFail($id)->markdown;
    }

    public static function get($post_id)
    {
        $comments = Cache::remember(self::CACHE_NEWLIST_NAME.$post_id, self::CACHE_NEWLIST_MINS, function() use($post_id)
        {
            return DB::table('comments')
                ->join('users', 'comments.user_id', '=', 'users.id')
                ->select('comments.id', 'comments.user_id', 'comments.created_at', 'comments.updated_at', 'comments.upvotes', 'comments.downvotes', 'comments.parent_id', 'comments.data', 'users.username', 'users.points', 'users.id AS users_user_id')
                ->where('post_id', '=', $post_id)
                ->orderBy('id', 'asc')
                ->get();
        });

        return Vote::applySelection($comments, Vote::COMMENT_TYPE);
    }

    public static function getCommentsInTimeoutRange()
    {
        return DB::table('comments')
            ->select('id')
            ->where('comments.user_id', '=', Auth::id())
            ->where('comments.created_at', '>', time() - self::MAX_COMMENTS_TIMEOUT_SECONDS)
            ->count();
    }

    public static function canPost()
    {
        return (self::getCommentsInTimeoutRange() <= self::MAX_COMMENTS_PER_DAY);
    }
}
