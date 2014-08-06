<?php

use \Michelf\MarkdownExtra;
use \Functional as F;

class PostController extends BaseController
{
    protected function get($section_title, $post_id)
    {
        $sections = Section::get();
        $section_id = Section::getId($section_title);
        $sidebar = Section::getSidebar($section_id);
        $post = Post::get($post_id);
        $my_votes = Vote::getMatchingVotes(Vote::POST_TYPE, [$post]);
        $post->selected = isset($my_votes[$post_id]) ? $my_votes[$post_id] : 0;
        $commentTree = new CommentTree(Comment::get($post_id));
        $sectionController = new SectionController;
        $sort_highlight = Utility::getSortMode();
        $sort_timeframe_highlight = Utility::getSortTimeframe();

        return View::make('post', [
            'section_title' => $section_title,
            'sections' => $sections,
            'comments' => $commentTree->grab()->sort('new')->render(),
            'post' => $post,
            'sidebar' => $sidebar,
            'sort_highlight' => $sort_highlight,
            'sort_timeframe_highlight' => $sort_timeframe_highlight,
        ]);
    }

    public static function update($post_id)
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
            return Redirect::to("/s/$section_title/add")->withErrors(['message' => 'can only post ' . self::MAX_POSTS_PER_DAY . ' per day'])->withInput();
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
            return Redirect::to("/s/$section_title/add")->withErrors($validate->messages())->withInput();
        }

        if(isset($rules['url'])) {
            if(!Utility::urlExists($data['url'])) {
                return Redirect::to("/s/$section_title/add")
                    ->withErrors(['message' => 'website doesn\'t exist'])
                    ->withInput();

            }

            $data['thumbnail'] = Utility::getThumbnailFromUrl($data['url']);
        }

        $item = new Post($data);
        $item->save();

        //add a point for adding posts
        Auth::user()->increment('points');

        return Redirect::to("/s/$section_title/posts/$item->id/" . Utility::prettyUrl($data['title']));
    }
}

