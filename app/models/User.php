<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface
{
    //how many pages to show with pagination
    const PAGE_RESULTS = 25;

    protected $table = 'users';

    protected $hidden = array('password');

    public $autoHashPasswordAttributes = true;
    public $autoPurgeRedundantAttributes = true;
    public $autoHydrateEntityFromInput = true; 
    
    protected $attributes = array(
        'points'    => 10,
        'upvotes'   => 0,
        'downvotes' => 0,
        'show_nsfw' => 0,
        'show_nsfl' => 0,
    );
    
    public static $passwordAttributes = ['password'];
    
    public static $rules = [
        'username' => 'required|andu|unique:users|max:24',
        'password' => 'required|confirmed|max:128',
        'password_confirmation' => 'required|max:128',
        'captcha' => 'required|captcha'
    ];

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);

        $this->purgeFilters[] = function($key) {
            $purge = array('captcha');
            return ! in_array($key, $purge);
        };
    }

    public static function create_anon($username)
    {
        DB::table('users')->insert([
            'username' => $username,
            'password' => Hash::make(''),
            'anonymous' => 1,
            'points' => 1,
            'upvotes' => 0,
            'downvotes' => 0,
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }

    public static function savePreferences($user_id, $show_nsfw, $show_nsfl) {
        DB::table('users')
            ->where('id', $user_id)
            ->update([
                'show_nsfw' => $show_nsfw,
                'show_nsfl' => $show_nsfl,
            ]);
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
            return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
            return $this->password;
    }

    public function getRememberToken()
    {
            return $this->remember_token;
    }

    public function setRememberToken($value)
    {
            $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
            return 'remember_token';
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
            return $this->email;
    }

    protected function comments($username)
    {
        $comments = DB::table('comments')
            ->select('comments.id', 'comments.created_at', 'comments.data', 'comments.upvotes', 'comments.downvotes', 'users.username', 'users.points', 'users.id AS users_user_id', 'users.votes', 'users.anonymous')
            ->leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('id', 'desc')
            ->simplePaginate(self::PAGE_RESULTS);

        return Vote::applySelection($comments, Vote::COMMENT_TYPE);
    }

    protected function posts($username)
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->join('sections', 'posts.section_id', '=', 'sections.id')
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.comment_count', 'posts.user_id', 'posts.markdown', 'users.username', 'users.points', 'users.votes', 'users.anonymous', 'sections.title AS section_title')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('id', 'desc')
            ->simplePaginate(self::PAGE_RESULTS);

        return Vote::applySelection($posts, Vote::POST_TYPE);
    }

    protected function postsVotes($username)
    {
        return DB::table('votes')
            ->join('users', 'votes.user_id', '=', 'users.id')
            ->join('posts', 'votes.item_id', '=', 'posts.id')
            ->join('sections', 'posts.section_id', '=', 'sections.id')
            ->join('users AS users_r', 'posts.user_id', '=', 'users_r.id')
            ->select('votes.updown', 'votes.created_at', 'posts.id', 'posts.title', 'posts.upvotes', 'posts.downvotes', 'posts.user_id', 'users_r.username', 'users_r.points', 'users_r.votes', 'users_r.anonymous', 'sections.title AS section_title')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('votes.id', 'desc')
            ->simplePaginate(User::PAGE_RESULTS);
    }

    protected function commentsVotes($username)
    {
        return DB::table('votes')
            ->join('users', 'votes.user_id', '=', 'users.id')
            ->join('comments', 'votes.item_id', '=', 'comments.id')
            ->join('posts', 'posts.id', '=', 'comments.post_id')
            ->join('sections', 'posts.section_id', '=', 'sections.id')
            ->join('users AS users_r', 'comments.user_id', '=', 'users_r.id')
            ->select('votes.updown', 'votes.created_at', 'comments.id', 'comments.data', 'comments.upvotes', 'comments.downvotes', 'comments.user_id', 'users_r.username AS username', 'users_r.points', 'users_r.votes', 'users_r.anonymous', 'sections.title AS section_title')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('votes.id', 'desc')
            ->simplePaginate(self::PAGE_RESULTS);
    }
}
