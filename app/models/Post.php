<?php

class Post extends BaseModel
{
    protected $table = 'posts';
    protected $guarded = array('id');


    public function getSectionTitleFromId($id)
    {
        return DB::table('sections')
            ->select('sections.title')
            ->join('posts', 'posts.section_id', '=', 'sections.id')
            ->where('posts.id', '=', $id)
            ->pluck('title');
    }

    public function getList(array $section_ids=[0], $seconds=0, $orderby, Vote $vote)
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->join('sections', 'sections.id', '=', 'posts.section_id')
            ->select(
                'posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at',
                'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.comment_count',
                'posts.user_id', 'posts.markdown', 'posts.thumbnail', 'posts.nsfw', 'posts.nsfl', 
                'users.username', 'users.points', 'users.votes', 'users.anonymous', 
                'sections.title AS section_title',
                DB::raw($orderby)
            )
            ->where('posts.deleted_at', '=', 0);

        if(count($section_ids) == 1) {
            $section_id = $section_ids[0];

            if($section_id != 0) {
                $posts = $posts->where(
                    'posts.section_id', 
                    $section_id == 0 ? '>' : '=', 
                    $section_id == 0 ? '0' : $section_id
                );
            } else if(Auth::check()) {
                $ignore_sections = Auth::user()->frontpage_ignore_sections; 
                $show_sections = Auth::user()->frontpage_show_sections;

                if($ignore_sections != "") {
                    $ids = explode(',', $ignore_sections);
                    $posts = $posts->whereNotIn('posts.section_id', $ids);
                }

                if($show_sections != "") {
                    $ids = explode(',', $show_sections);
                    $posts = $posts->whereIn('posts.section_id', $ids);
                }
            }
        } else {
            $posts = $posts->whereIn(
                'posts.section_id', 
                F::filter($section_ids, function($v) { return $v != 0; })
            );
        }


        if($seconds != 0) {
            $posts = $posts->where('posts.created_at', '>', time() - $seconds);
        }

        $posts = $posts->orderBy('sorterino', 'desc')
            ->simplePaginate(Constant::SECTION_PAGE_POST_COUNT);

        return $vote->applySelection($posts, Constant::POST_TYPE);
    }

    public function getNewList(array $section_ids=[0], Vote $vote)
    {
        return $vote->applySelection(
            $this->getList($section_ids, 0, Constant::SORT_ORDERBY_SQL_NEW, $vote), 
            Constant::POST_TYPE
        );
    }

    public function getTopList(array $section_ids=[0], $seconds, Vote $vote)
    {
        return $vote->applySelection(
            $this->getList($section_ids, $seconds, Constant::SORT_ORDERBY_SQL_TOP, $vote), 
            Constant::POST_TYPE
        );
    }

    public function getHotList(array $section_ids=[0], Vote $vote)
    {
        return $vote->applySelection(
            $this->getList($section_ids, 0, Constant::SORT_ORDERBY_SQL_HOT, $vote), 
            Constant::POST_TYPE
        );
    }

    public function getControversialList(array $section_ids=[0], $seconds, Vote $vote)
    {
        return $vote->applySelection(
            $this->getList($section_ids, $seconds, Constant::SORT_ORDERBY_SQL_CONTROVERSIAL, $vote), 
            Constant::POST_TYPE
        );
    }

    public function get($post_id)
    {
        $post = DB::table('posts')
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.user_id', 'posts.data', 'posts.markdown', 'posts.thumbnail', 'posts.nsfw', 'posts.nsfl', 'posts.comment_count',
                'users.username', 'users.points', 'users.votes', 'users.anonymous')
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

    public function amend($post_id, $content)
    {
        $block = new SuccessBlock();

        $block->data->prev_path = sprintf("/s/%s/posts/%d", $this->getSectionTitleFromId($post_id), $post_id);

        if($block->success) {
            if(Auth::user()->points < 1) {
                $block->success  = false;
                $block->errors[] = "not enough points";
            }
        }

        if($block->success) {
            $post = $this->findOrFail($post_id);

            if($post->user_id != Auth::user()->id) {
                $block->success  = false;
                $block->errors[] = 'This post does not have the same user id as you';
            }
        }

        if($block->success) {
            $data['user_id']  = Auth::user()->id;
            $data['data']     = $content;
            $data['markdown'] = $data['data'];
            $data['data']     = Markdown::defaultTransform(e($data['markdown']));

            $rules = array(
                'user_id'  => 'required|numeric',
                'markdown' => 'required|max:'.Constant::POST_MAX_MARKDOWN_LENGTH
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
            $history->type     = Constant::POST_TYPE;
            $history->type_id  = $post->id;
            $history->save();


            $post->markdown = $data['markdown'];
            $post->data     = $data['data'];
            $post->save();
        }

        return $block;
    }

    public function remove($post_id)
    {
        $block = new SuccessBlock();

        if($block->success) {
            $block->data->section_title = $this->getSectionTitleFromId($post_id);
            $block->data->prev_path = sprintf("/s/%s/posts/%d", $block->data->section_title, $post_id);

            if(Auth::user()->points < 1) {
                $block->success  = false;
                $block->errors[] = 'You need at least one point to delete a post';
            }
        }

        if($block->success) {
            $post = $this->findOrFail($post_id);

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

    public function getPostsInTimeoutRange()
    {
        $user_id = (isset(Auth::user()->id)) ? Auth::user()->id : -1;

        return DB::table('posts')
            ->select('id')
            ->where('posts.user_id', '=', $user_id)
            ->where('posts.created_at', '>', time() - Constant::POST_MAX_POSTS_TIMEOUT_SECONDS)
            ->count();
    }

    public function canPost()
    {
        return Utility::remainingPosts();
    }

    public function generateRules($data)
    {
        $rules = array(
            'user_id' => 'required|numeric',
            'type'    => 'required|numeric|between:0,2',
            'title'   => 'required|max:'.Constant::POST_MAX_TITLE_LENGTH,
            'nsfw'    => 'required|numeric|between:0,1',
            'nsfl'    => 'required|numeric|between:0,1',
        );

        $rule_data = 'max:'.Constant::POST_MAX_MARKDOWN_LENGTH;
        $rule_url  = 'required|url|max:'.Constant::POST_MAX_URL_LENGTH;
        
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

    public function prepareData($data)
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
        $data['data']     = Markdown::defaultTransform(e($data['markdown']));
        
        return $data;
    }

    public function gfycatUrl($url)
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

    public function make($section_title, $content, $title, $url, $nsfw, $nsfl, Section $section)
    {
        $section_title = strtolower($section_title);
        $block = new SuccessBlock();
        $block->data->section_title = $section_title;
        $block->data->item_title    = Utility::prettyUrl($title);

        if($block->success) {
            if(Auth::user()->points < 1) {
                $block->success = false;
                $block->errors[] = 'You need at least one point to post';
            }
        }

        if($block->success) {
            if(!$this->canPost()) {
                $block->success  = false;
                $block->errors[] = 'can only post ' . Utility::availablePosts() . ' per day';
            }
        }

        if($block->success) {
            $data = $this->prepareData([
                'data'    => $content,
                'title'   => $title,
                'url'     => $url,
                'user_id' => Auth::user()->id,
                'nsfw'    => $nsfw,
                'nsfl'    => $nsfl,
            ]);

            $rules    = $this->generateRules($data);
            $validate = Validator::make($data, $this->generateRules($data));

            if($validate->fails()) {
                $block->success = false;

                foreach($validate->messages()->all() as $v) {
                    $block->errors[] = $v;
                }
            }
        }

        if($block->success) {
            //check if .gif & gfycat it
            $data['url'] = $this->gfycatUrl($data['url']);

            if(!$section->exists($section_title)) {
                $ssect = new Section(['title' => $section_title]);

                if(! $ssect->save()) {
                    $block->success = false;
                    $block->errors[] = 'unable to create new spreadit'; 
                    $block->data->section_title = str_replace(' ', '_', $block->data->section_title);
                }
            }
        }

        if($block->success) {
            $section = $section->sectionFromSections($section->getByTitle(Utility::splitByTitle($section_title)));
            if($section->id < 1) {
                $block->success  = false;
                $block->errors[] = 'can only post to a real section(you probably tried to post to /s/all)';
            }
        }

        if($block->success) {
            $data['section_id'] = $section->id;

            $item = new Post($data);
            $item->save();

            if(isset($rules['url'])) {
                if(Utility::urlExists($data['url'])) {
                    Utility::thumbnailScript($item->id, $data['url']);
                }
            }

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
