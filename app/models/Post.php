<?php
class Post extends BaseModel
{
    const LINK_POST_TYPE = 0;
    const SELF_POST_TYPE = 1;

    const MAX_TITLE_LENGTH = 128;
    const MAX_URL_LENGTH = 256;

    const MAX_MARKDOWN_LENGTH = 6000;
    const MAX_POSTS_PER_DAY = 15;
    const MAX_POSTS_TIMEOUT_SECONDS = 86400; //one day

    protected $table = 'posts';
    protected $guarded = array('id');

    public static function getSourceFromId($id)
    {
        return Post::findOrFail($id)->markdown;
    }

    public static function getSectionTitleFromId($id)
    {
        return DB::table('sections')
            ->select('sections.title')
            ->join('posts', 'posts.section_id', '=', 'sections.id')
            ->where('posts.id', '=', $id)
            ->pluck('title');
    }

    public static function getList($section_id=0, $seconds=0, $orderby)
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->join('sections', 'sections.id', '=', 'posts.section_id')
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.comment_count', 'posts.user_id', 'posts.markdown', 'posts.thumbnail', 'users.username', 'users.points', 'sections.title AS section_title');

        if($section_id != 0) {
            $posts = $posts->where('posts.section_id', $section_id == 0 ? '>' : '=', $section_id == 0 ? '0' : $section_id);
        }
        if($seconds != 0) {
            $posts = $posts->where('posts.created_at', '>', time() - $seconds);
        }

        $posts = $posts->orderBy(DB::raw($orderby), 'desc')
            ->simplePaginate(Section::PAGE_POST_COUNT);

        return Vote::applySelection($posts, Vote::POST_TYPE);
    }

    public static function getNewList($section_id=0)
    {
        return Vote::applySelection(self::getList($section_id, 0, SortController::ORDERBY_SQL_NEW), Vote::POST_TYPE);
    }

    public static function getTopList($section_id=0, $seconds)
    {
        return Vote::applySelection(self::getList($section_id, $seconds, SortController::ORDERBY_SQL_TOP), Vote::POST_TYPE);
    }

    public static function getHotList($section_id=0)
    {
        return Vote::applySelection(self::getList($section_id, 0, SortController::ORDERBY_SQL_HOT), Vote::POST_TYPE);
    }

    public static function getControversialList($section_id=0, $seconds)
    {
        return Vote::applySelection(self::getList($section_id, $seconds, SortController::ORDERBY_SQL_CONTROVERSIAL), Vote::POST_TYPE);
    }

    public static function get($post_id)
    {
        $post = DB::table('posts')
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.user_id', 'posts.data', 'posts.thumbnail', 'users.username', 'users.points')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.id', '=', $post_id)
            ->orderBy('posts.id', 'desc')
            ->first();

        if(is_null($post)) {
            App::abort(404, "post id not found");
        }

        return $post;
    }
}
