<?php
use \Michelf\MarkdownExtra;

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
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.comment_count', 'posts.user_id', 'posts.markdown', 'posts.thumbnail', 'users.username', 'users.points', 'sections.title AS section_title')
            ->where('posts.deleted_at', '=', 0);

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
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.user_id', 'posts.data', 'posts.markdown', 'posts.thumbnail', 'users.username', 'users.points')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.id', '=', $post_id)
            ->where('posts.deleted_at', '=', 0)
            ->orderBy('posts.id', 'desc')
            ->first();

        if(is_null($post)) {
            App::abort(404, "post id not found");
        }

        return $post;
    }

    public static function amend($post_id, $content)
    {
        $prev_path = "/s/" . Post::getSectionTitleFromId($post_id) . "/posts/" . $post_id;

        if(Auth::user()->points < 1) {
            return "not enough points";
            return Redirect::to($prev_path)->withErrors(['message' => 'You need at least one point to edit a comment']);
        }

        $post = Post::findOrFail($post_id);


        if($post->user_id != Auth::id()) {
            return Redirect::to($prev_path)->withErrors(['message' => 'This comment does not have the same user id as you']);
        }

        $data['user_id'] = Auth::id();
        $data['data'] = $content;
        $data['markdown'] = $data['data'];
        $data['data'] = MarkdownExtra::defaultTransform(e($data['markdown']));

        $rules = array(
            'user_id' => 'required|numeric',
            'markdown' => 'required|max:'.self::MAX_MARKDOWN_LENGTH
        );

        $validate = Validator::make($data, $rules);
        if($validate->fails()) {
            return Redirect::to($prev_path)->withErrors($validate->messages())->withInput();
        }

        $history = new History;
        $history->data     = $post->data;
        $history->markdown = $post->markdown;
        $history->user_id  = Auth::id();
        $history->type     = HistoryController::POST_TYPE;
        $history->type_id  = $post->id;
        $history->save();


        $post->markdown = $data['markdown'];
        $post->data = $data['data'];
        $post->save();

        return Redirect::to($prev_path);
    }

    public static function remove($post_id)
    {
        $section_title = Post::getSectionTitleFromId($post_id);
        $prev_path = "/s/$section_title/posts/$post_id";

        if(Auth::user()->points < 1) {
            return "not enough points";
            return Redirect::to($prev_path)->withErrors(['message' => 'You need at least one point to delete a post']);
        }

        $post = Post::findOrFail($post_id);


        if($post->user_id != Auth::id()) {
            return Redirect::to($prev_path)->withErrors(['message' => 'This post does not have the same user id as you']);
        }

        $post->deleted_at = time();
        $post->save();

        return Redirect::to("/s/$section_title");
    }

    public static function getPostsInTimeoutRange()
    {
        return DB::table('posts')
            ->select('id')
            ->where('posts.user_id', '=', Auth::id())
            ->where('posts.created_at', '>', time() - self::MAX_POSTS_TIMEOUT_SECONDS)
            ->count();
    }

    public static function canPost()
    {
        return (self::getPostsInTimeoutRange() <= self::MAX_POSTS_PER_DAY);
    }

    public static function make($section_title, $content, $title, $url)
    {
        if(!self::canPost()) {
            return Redirect::back()->withErrors(['message' => 'can only post ' . self::MAX_POSTS_PER_DAY . ' per day'])->withInput();
        }

        $data = [
            'data' => $content,
            'title' => $title,
            'url' => $url,
            'user_id' => Auth::id(),
        ];

        $rules = array(
            'user_id' => 'required|numeric',
            'type' => 'required|numeric|between:0,2',
            'title' => 'required|max:'.self::MAX_TITLE_LENGTH,
        );

        $rule_data = 'max:'.self::MAX_MARKDOWN_LENGTH;
        $rule_url = 'required|url|max:'.self::MAX_URL_LENGTH;
        
        if(empty($data['data']) && empty($data['url'])) {
            $rules['data'] = $rule_data;
            $data['type'] = 1;
        } else if(!empty($data['data']) && !empty($data['url'])) {
            $rules['data'] = $rule_data;
            $rules['url'] = $rule_url;
            $data['type'] = 0;
        } else if(!empty($data['data'])) {
            $rules['data'] = $rule_data;
            $data['type'] = 1;
        } else if(!empty($data['url'])) {
            $rules['url'] = $rule_url;
            $data['type'] = 0;
        }

        $data['title'] = e($data['title']);
        $data['url'] = e($data['url']);
        $data['markdown'] = $data['data'];
        $data['data'] = MarkdownExtra::defaultTransform(e($data['markdown']));
        
        $validate = Validator::make($data, $rules);
        if($validate->fails()) {
            return Redirect::back()->withErrors($validate->messages())->withInput();
        }

        if(isset($rules['url'])) {
            if(!Utility::urlExists($data['url'])) {
                return Redirect::back()
                    ->withErrors(['message' => 'website doesn\'t exist'])
                    ->withInput();

            }

            $data['thumbnail'] = Utility::getThumbnailFromUrl($data['url']);
        }

        if(!Section::exists($section_title)) {
            $ssect = new Section(['title' => $section_title]);

            if(! $ssect->save()) {
                $section_title = str_replace(' ', '_', $section_title);
                return Redirect::back()
                    ->withErrors($ssect->errors())
                    ->withInput();
            }
        }
        $section = Section::getByTitle($section_title);
        $data['section_id'] = $section->id;

        $item = new Post($data);
        $item->save();

        //add a point for adding posts
        Auth::user()->increment('points');

        return Redirect::to("/s/$section_title/posts/$item->id/" . Utility::prettyUrl($data['title']));
    }
}
