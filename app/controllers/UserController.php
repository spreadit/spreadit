<?php

class UserController extends BaseController
{
    //how many pages to show with pagination
    const PAGE_RESULTS = 25;

    public function register()
    {
        $data = Input::only('username', 'password_confirmation', 'password', 'captcha');
        $rules = [
            'username' => 'required|unique:users|max:24',
            'password' => 'required|confirmed|max:128',
            'password_confirmation' => 'required|max:128',
            'captcha' => 'required|captcha'
          ];
        
        $validate = Validator::make($data, $rules);
        if($validate->fails()) {
            $errors = $validate->messages();
            return Redirect::to('/login')->withErrors($errors)->withInput();
        }

        $user = new User;
        $user->username = e(Input::get('username'));
        $user->password = Hash::make(Input::get('password'));
        $user->save();
        $info = Input::only('username', 'password');
        
        if(Auth::attempt($info)) {
            return Redirect::intended('/s/all/hot');
        }
    }

    public function login()
    {
        $data = Input::only('username', 'password');
        $data['username'] = e($data['username']);
        
        $rules = array(
            'username' => 'required|max:24',
            'password' => 'required|max:128',
        );
        $validate = Validator::make($data, $rules);
        if($validate->fails()) {
            $errors = $validate->messages();
            return Redirect::to('/login')->withErrors($errors)->withInput();
        }
        if(Auth::attempt($data, true)) {
            return Redirect::to('/');
        } else {
            return Redirect::to('/login')->with('message', 'wrong user id or password');
        }
    }

    public static function getComments($username)
    {
        $comments = DB::table('comments')
            ->select('comments.id', 'comments.created_at', 'comments.data', 'comments.upvotes', 'comments.downvotes', 'users.username', 'users.points', 'users.id AS users_user_id')
            ->leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('id', 'desc')
            ->simplePaginate(self::PAGE_RESULTS);

        return VoteController::applySelection($comments, VoteController::COMMENT_TYPE);
    }

    public static function commentsView($username)
    {
        return View::make('user_comments', [
            'sections' => SectionController::get(),
            'comments' => self::getComments($username),
            'username' => $username,
            'highlight' => 'comments'
        ]);
    }

    public static function getPosts($username)
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->join('sections', 'posts.section_id', '=', 'sections.id')
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.comment_count', 'posts.user_id', 'users.username', 'users.points', 'sections.title AS section_title')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('id', 'desc')
            ->simplePaginate(self::PAGE_RESULTS);

        return VoteController::applySelection($posts, VoteController::POST_TYPE);
    }

    public static function postsView($username)
    {
        return View::make('user_posts', [
            'sections' => SectionController::get(),
            'posts' => self::getPosts($username),
            'username' => $username,
            'highlight' => 'posts'
        ]);
    }

    public static function getPostsVotes($username)
    {
        return DB::table('votes')
            ->join('users', 'votes.user_id', '=', 'users.id')
            ->join('posts', 'votes.item_id', '=', 'posts.id')
            ->join('sections', 'posts.section_id', '=', 'sections.id')
            ->join('users AS users_r', 'posts.user_id', '=', 'users_r.id')
            ->select('votes.updown', 'votes.created_at', 'posts.id', 'posts.title', 'posts.upvotes', 'posts.downvotes', 'posts.user_id', 'users_r.username', 'users_r.points', 'sections.title AS section_title')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('votes.id', 'desc')
            ->simplePaginate(self::PAGE_RESULTS);
    }

    public static function postsVotesView($username)
    {
        return View::make('user_posts_votes', [
            'sections' => SectionController::get(),
            'votes' => self::getPostsVotes($username),
            'username' => $username,
            'highlight' => 'pvotes'
        ]);
    }

    public static function getCommentsVotes($username)
    {
        return DB::table('votes')
            ->join('users', 'votes.user_id', '=', 'users.id')
            ->join('comments', 'votes.item_id', '=', 'comments.id')
            ->join('posts', 'posts.id', '=', 'comments.post_id')
            ->join('sections', 'posts.section_id', '=', 'sections.id')
            ->join('users AS users_r', 'comments.user_id', '=', 'users_r.id')
            ->select('votes.updown', 'votes.created_at', 'comments.id', 'comments.data', 'comments.upvotes', 'comments.downvotes', 'comments.user_id', 'users_r.username AS username', 'users_r.points', 'sections.title AS section_title')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('votes.id', 'desc')
            ->simplePaginate(self::PAGE_RESULTS);
    }

    public static function commentsVotesView($username)
    {
        return View::make('user_comments_votes', [
            'sections' => SectionController::get(),
            'votes' => self::getCommentsVotes($username),
            'username' => $username,
            'highlight' => 'cvotes'
        ]);
    }

    public static function mainVoteView($username)
    {
        return View::make('user_votes_page', [
            'sections' => SectionController::get(),
            'username' => $username,
            'highlight' => ''
        ]);
    }
}
