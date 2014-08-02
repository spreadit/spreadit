<?php
use \Michelf\MarkdownExtra;
use \Functional as F;

class PostController extends BaseController
{
    const LINK_POST_TYPE = 0;
    const SELF_POST_TYPE = 1;

    const MAX_TITLE_LENGTH = 128;
    const MAX_URL_LENGTH = 256;

    const MAX_MARKDOWN_LENGTH = 6000;
    const MAX_POSTS_PER_DAY = 15;
    const MAX_POSTS_TIMEOUT_SECONDS = 86400; //one day


    public static function prettyUrl($url, $max_length=70)
    {
        $url = trim($url);

        if(strlen($url) > $max_length) {
            $url = wordwrap($url, $max_length);
            $url = explode("\n", $url);
            $url = array_shift($url);
        }
        if(strlen($url) > $max_length) {
            $url = substr($url, 0, $max_length);
        }
        
        //from: http://stackoverflow.com/a/7568253
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
        $url = trim($url, "-");
        $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);

        return $url;
    }

    public static function prettyAgo($tm, $rcs = 0)
    {
        $cur_tm = time(); $dif = $cur_tm-$tm;
        $pds = array('second','minute','hour','day','week','month','year','decade');
        $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
        for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

        $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
        if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
        return $x;
    }


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
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.comment_count', 'posts.user_id', 'posts.markdown', 'users.username', 'users.points', 'sections.title AS section_title');

        if($section_id != 0) {
            $posts = $posts->where('posts.section_id', $section_id == 0 ? '>' : '=', $section_id == 0 ? '0' : $section_id);
        }
        if($seconds != 0) {
            $posts = $posts->where('posts.created_at', '>', time() - $seconds);
        }

        $posts = $posts->orderBy(DB::raw($orderby), 'desc')
            ->simplePaginate(SectionController::PAGE_POST_COUNT);

        return VoteController::applySelection($posts, VoteController::POST_TYPE);
    }

    public static function getNewList($section_id=0)
    {
        return VoteController::applySelection(self::getList($section_id, 0, SortController::ORDERBY_SQL_NEW), VoteController::POST_TYPE);
    }

    public static function getTopList($section_id=0, $seconds)
    {
        return VoteController::applySelection(self::getList($section_id, $seconds, SortController::ORDERBY_SQL_TOP), VoteController::POST_TYPE);
    }

    public static function getHotList($section_id=0)
    {
        return VoteController::applySelection(self::getList($section_id, 0, SortController::ORDERBY_SQL_HOT), VoteController::POST_TYPE);
    }

    public static function getControversialList($section_id=0, $seconds)
    {
        return VoteController::applySelection(self::getList($section_id, $seconds, SortController::ORDERBY_SQL_CONTROVERSIAL), VoteController::POST_TYPE);
    }

    public static function get($post_id)
    {
        $post = DB::table('posts')
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.user_id', 'posts.data', 'users.username', 'users.points')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.id', '=', $post_id)
            ->orderBy('posts.id', 'desc')
            ->first();

        if(is_null($post)) {
            App::abort(404, "post id not found");
        }

        return $post;
    }

    public static function update($post_id)
    {
        $prev_path = "/s/" . self::getSectionTitleFromId($post_id) . "/posts/" . $post_id;

        if(Auth::user()->points < 1) {
            return "not enough points";
            return Redirect::to($prev_path)->withErrors(['You need at least one point to edit a comment']);
        }

        $post = Post::findOrFail($post_id);


        if($post->user_id != Auth::id()) {
            return Redirect::to($prev_path)->withErrors(['This comment does not have the same user id as you']);
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
            return Redirect::to($prev_path)->withErrors($validate->messages())->withInput();
        }

        $history = new History([
            'data'     => $post->data,
            'markdown' => $post->markdown,
            'user_id'  => Auth::id(),
            'type'     => HistoryController::POST_TYPE,
            'type_id'  => $post->id
        ]);
        $history->save();


        $post->markdown = $data['markdown'];
        $post->data = $data['data'];
        $post->save();

        return Redirect::to($prev_path);
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

    public static function post($section_title)
    {
        if(!self::canPost()) {
            return Redirect::to("/s/$section_title/add")->withErrors(['error' => 'can only post ' . self::MAX_POSTS_PER_DAY . ' per day'])->withInput();
        }

        $section_id = SectionController::getId($section_title);

        $data = array_merge(
            Input::only('data', 'title', 'url'),
            array(
                'user_id'=>Auth::id(),
                'section_id'=>$section_id
            )
        );

        $rules = array(
            'user_id' => 'required|numeric',
            'type' => 'required|numeric|between:0,2',
            'title' => 'required|max:'.self::MAX_TITLE_LENGTH,
            'section_id' => 'required|numeric',
        );

        $rule_data = 'required|max:'.self::MAX_MARKDOWN_LENGTH;
        $rule_url = 'required|url|max:'.self::MAX_URL_LENGTH;
        
        if(empty($data['data']) && empty($data['url'])) {
            $rules['url'] = $rule_data;
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
            return Redirect::to("/s/$section_title/add")->withErrors($validate->messages())->withInput();
        }

        $item = new Post($data);
        $item->save();

        //add a point for adding posts
        Auth::user()->increment('points');

        return Redirect::to("/s/$section_title/posts/$item->id/" . self::prettyUrl($data['title']));
    }
}

