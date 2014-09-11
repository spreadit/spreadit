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
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.comment_count', 'posts.user_id', 'posts.markdown', 'posts.thumbnail', 'users.username', 'users.points', 'users.votes', 'users.anonymous', 'sections.title AS section_title')
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
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.user_id', 'posts.data', 'posts.markdown', 'posts.thumbnail', 'users.username', 'users.points', 'users.votes', 'users.anonymous')
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
        $block = new SuccessBlock();

        $block->data->prev_path = sprintf("/s/%s/posts/%d", Post::getSectionTitleFromId($post_id), $post_id);

        if($block->success) {
            if(Auth::user()->points < 1) {
                $block->success  = false;
                $block->errors[] = "not enough points";
            }
        }

        if($block->success) {
            $post = Post::findOrFail($post_id);

            if($post->user_id != Auth::user()->id) {
                $block->success  = false;
                $block->errors[] = 'This post does not have the same user id as you';
            }
        }

        if($block->success) {
            $data['user_id']  = Auth::user()->id;
            $data['data']     = $content;
            $data['markdown'] = $data['data'];
            $data['data']     = MarkdownExtra::defaultTransform(e($data['markdown']));

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
            $history->data     = $post->data;
            $history->markdown = $post->markdown;
            $history->user_id  = Auth::user()->id;
            $history->type     = HistoryController::POST_TYPE;
            $history->type_id  = $post->id;
            $history->save();


            $post->markdown = $data['markdown'];
            $post->data     = $data['data'];
            $post->save();
        }

        return $block;
    }

    public static function remove($post_id)
    {
        $block = new SuccessBlock();

        if($block->success) {
            $block->data->section_title = Post::getSectionTitleFromId($post_id);
            $block->data->prev_path = sprintf("/s/%s/posts/%d", $block->data->section_title, $post_id);

            if(Auth::user()->points < 1) {
                $block->success  = false;
                $block->errors[] = 'You need at least one point to delete a post';
            }
        }

        if($block->success) {
            $post = Post::findOrFail($post_id);

            if($post->user_id != Auth::user()->id) {
                $block->success  = false;
                $block->errors[] = 'This post does not have the same user id as you';
            }
        }

        if($block->success) {
            $post->deleted_at = time();
            $post->save();
        }

        return $block;
    }

    public static function getPostsInTimeoutRange()
    {
        $user_id = (isset(Auth::user()->id)) ? Auth::user()->id : -1;

        return DB::table('posts')
            ->select('id')
            ->where('posts.user_id', '=', $user_id)
            ->where('posts.created_at', '>', time() - self::MAX_POSTS_TIMEOUT_SECONDS)
            ->count();
    }

    public static function canPost()
    {
        return (self::getPostsInTimeoutRange() <= self::MAX_POSTS_PER_DAY);
    }

    public static function generateRules($data)
    {
        $rules = array(
            'user_id' => 'required|numeric',
            'type'    => 'required|numeric|between:0,2',
            'title'   => 'required|max:'.self::MAX_TITLE_LENGTH,
        );

        $rule_data = 'max:'.self::MAX_MARKDOWN_LENGTH;
        $rule_url  = 'required|url|max:'.self::MAX_URL_LENGTH;
        
        if(empty($data['data']) && empty($data['url'])) {
            $rules['data'] = $rule_data;
        } else if(!empty($data['data']) && !empty($data['url'])) {
            $rules['data'] = $rule_data;
            $rules['url']  = $rule_url;
        } else if(!empty($data['data'])) {
            $rules['data'] = $rule_data;
        } else if(!empty($data['url'])) {
            $rules['url']  = $rule_url;
        }

        return $rules;
    }

    public static function prepareData($data)
    {
        if(empty($data['data']) && empty($data['url'])) {
            $data['type'] = 1;
        } else if(!empty($data['data']) && !empty($data['url'])) {
            $data['type'] = 0;
        } else if(!empty($data['data'])) {
            $data['type'] = 1;
        } else if(!empty($data['url'])) {
            $data['type'] = 0;
        }

        $data['title']    = e($data['title']);
        $data['url']      = e($data['url']);
        $data['markdown'] = $data['data'];
        $data['data']     = MarkdownExtra::defaultTransform(e($data['markdown']));
        
        return $data;
    }

    public static function gfycatUrl($url)
    {
        if(Utility::endsWith($url, ".gif")) {
            $successful_conv = true;

            try {
                $gfy_url = Utility::gfycat($url);
            } catch(Exception $e) {
                $successful_conv = false;
            }

            if($successful_conv) {
                return $gfy_url;
            }
        }

        return $url;
    }

    public static function make($section_title, $content, $title, $url)
    {
        $block = new SuccessBlock();
        $block->data->section_title = $section_title;
        $block->data->item_title    = Utility::prettyUrl($title);

        if($block->success) {
            if(!self::canPost()) {
                $block->success  = false;
                $block->errors[] = 'can only post ' . self::MAX_POSTS_PER_DAY . ' per day';
            }
        }

        if($block->success) {
            $data = self::prepareData([
                'data'    => $content,
                'title'   => $title,
                'url'     => $url,
                'user_id' => Auth::user()->id
            ]);

            $rules    = self::generateRules($data);
            $validate = Validator::make($data, self::generateRules($data));

            if($validate->fails()) {
                $block->success = false;

                foreach($validate->messages()->all() as $v) {
                    $block->errors[] = $v;
                }
            }
        }

        if($block->success) {
            if(isset($rules['url'])) {
                if(!Utility::urlExists($data['url'])) {
                    $block->success  = false;
                    $block->errors[] = 'website doesn\'t exist';
                } else {
                    $data['thumbnail'] = Utility::getThumbnailFromUrl($data['url']);
                }
            }
        }

        if($block->success) {
            //check if .gif & gfycat it
            $data['url'] = self::gfycatUrl($data['url']);

            if(!Section::exists($section_title)) {
                $ssect = new Section(['title' => $section_title]);

                if(! $ssect->save()) {
                    $block->success = false;
                    $block->errors[] = 'unable to create new spreadit'; 
                    $block->data->section_title = str_replace(' ', '_', $block->data->section_title);
                }
            }
        }

        if($block->success) {
            $section = Section::getByTitle($section_title);
            $data['section_id'] = $section->id;

            $item = new Post($data);
            $item->save();


            //add a point for adding posts
            if(Auth::user()->anonymous == 0) {
                Auth::user()->increment('points');
            }

            $block->data->item_id = $item->id;
        } else {
            $block->data->item_id = null;
        }


        return $block;
    }
}
