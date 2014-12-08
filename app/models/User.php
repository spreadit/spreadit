<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface
{

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

    public function create_anon($username)
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

    public function savePreferences($user_id, array $data)
    {
        DB::table('users')
            ->where('id', $user_id)
            ->update([
                'show_nsfw'                 => $data['show_nsfw'],
                'show_nsfl'                 => $data['show_nsfl'],
                'frontpage_show_sections'   => $data['frontpage_show_sections'],
                'frontpage_ignore_sections' => $data['frontpage_ignore_sections'],
            ]);
    }

    public function saveHomepage($user_id, array $data)
    {
        DB::table('users')
            ->where('id', $user_id)
            ->update([
                'profile_data'     => $data['profile_data'],
                'profile_markdown' => $data['profile_markdown'],
                'profile_css'      => $data['profile_css'],
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

    public function comments($username, Vote $vote)
    {
        $comments = DB::table('comments')
            ->select('comments.id', 'comments.created_at', 'comments.data', 'comments.upvotes', 'comments.downvotes', 'users.username', 'users.points', 'users.id AS users_user_id', 'users.votes', 'users.anonymous')
            ->leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('id', 'desc')
            ->simplePaginate($this->PAGE_RESULTS);

        return $vote->applySelection($comments, $vote->COMMENT_TYPE);
    }

    public function posts($username, Vote $vote)
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->join('sections', 'posts.section_id', '=', 'sections.id')
            ->select('posts.id', 'posts.type', 'posts.title', 'posts.created_at', 'posts.updated_at', 'posts.upvotes', 'posts.downvotes', 'posts.type', 'posts.url', 'posts.comment_count', 'posts.user_id', 'posts.markdown', 'posts.nsfw', 'posts.nsfl', 'users.username', 'users.points', 'users.votes', 'users.anonymous', 'sections.title AS section_title')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('id', 'desc')
            ->simplePaginate($this->PAGE_RESULTS);

        return $vote->applySelection($posts, $vote->POST_TYPE);
    }

    public function postsVotes($username)
    {
        return DB::table('votes')
            ->join('users', 'votes.user_id', '=', 'users.id')
            ->join('posts', 'votes.item_id', '=', 'posts.id')
            ->join('sections', 'posts.section_id', '=', 'sections.id')
            ->join('users AS users_r', 'posts.user_id', '=', 'users_r.id')
            ->select('votes.updown', 'votes.created_at', 'posts.id', 'posts.title', 'posts.upvotes', 'posts.downvotes', 'posts.user_id', 'users_r.username', 'users_r.points', 'users_r.votes', 'users_r.anonymous', 'sections.title AS section_title')
            ->where('users.username', 'LIKE', $username)
            ->orderBy('votes.id', 'desc')
            ->simplePaginate($this->PAGE_RESULTS);
    }

    public function commentsVotes($username)
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
            ->simplePaginate($this->PAGE_RESULTS);
    }

    public function userStats($username) {
        $user = DB::table('users')
            ->select('users.points', 'users.votes', 'users.anonymous', 'users.created_at')
            ->where('users.username', 'LIKE', $username)
            ->first();
        if($user == null) { 
            App::abort(404);
        }

        $achievements = [
            'misunderstood',
            'lovelle',
            'paladin',
            'jagmere',
            'middle aged cat',
            'goatherder',
            'cornwhisperer',
            'velocotoaster',
            'rhinocerpuss',
            'beedlegoose',
            'finlander',
            'moosegasser',
            'wetland pony',
            'gorgonzola',
            'cheesehead beaver',
            'leviathan',
            'miniature god',
            'brit',
            'orangutan',
            'mousecycle',
            'jaden',
            'penis witch',
            'skateboard cop',
            'redacted speech impediment',
            'mild cornographer',
            'testicular based lifeform',
        ];

        $level = Utility::availablePosts($user);
        $user->level = $level;
        $user->achievement = $achievements[($level < count($achievements) ? $level : count($achievements) - 1)];
        return $user;
    }
}
