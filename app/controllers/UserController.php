<?php

class UserController extends BaseController
{

    protected $user;
    protected $section;
    protected $notification;
    protected $vote;

    public function __construct(User $user, Section $section, Notification $notification, Vote $vote)
    {
        $this->user = $user;
        $this->section = $section;
        $this->notification = $notification;
        $this->vote = $vote;
    }


    /**
     * log user out
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
	    return Redirect::to('/');
    }


    /**
     * attempt to register user and redirect to home on success
     * or to login page if registration fails
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function register()
    {
        $user = new User();
        $user->username = e(Input::get('username'));
        $user->password = Input::get('password');
        $user->password_confirmation = Input::get('password_confirmation');
        $user->captcha = Input::get('captcha');

        if($user->save()) {
            $info = Input::only('username', 'password');
        
            if(Auth::attempt($info)) {
                return Redirect::to('/');
            } else {
                return Redirect::to('/login')->withErrors(['message' => 'A general error occurred, please try again.'])->withInput();
            }
        } else {
            return Redirect::to('/login')->withErrors($user->errors())->withInput();
        }
    }


    /**
     * attempt to register user and redirect to home on success
     * or to login page if registration fails
     *
     * @param  array  $data
     * @return Illuminate\Validation\Validator
     */
    public function validateLogin(array $data)
    {
        $data['username'] = e($data['username']);
        
        $rules = array(
            'username' => 'required|max:24',
            'password' => 'required|max:128',
        );

        return Validator::make($data, $rules);
    }


    /**
     * attempt to login user and redirect to home on success
     * or to login page if login fails
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function login()
    {
        $data = Input::only('username', 'password');
        $validate = $this->validateLogin($data);

        if($validate->fails()) {
            return Redirect::to('/login')->withErrors($validate->messages())->withInput();
        }

        if(!Auth::attempt($data, true)) {
            return Redirect::to('/login')->withErrors(['message' => 'wrong user id or password']);
        }

        return Redirect::to('/');
    }


    /**
     * get all notifications for a user
     * and set all of users notifications to 'read' (vs. unread)
     *
     * @return Illuminate\View\View
     */
    public function notifications()
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

		$view = View::make('page.user.notifications', [
			'sections'      => $sections,
			'section'       => $section,
			'notifications' => $this->notification->get()
		]);

		$this->notification->markAllAsRead();

		return $view;
    }


    /**
     * see `notifications`
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function notificationsJson()
    {
		$results = iterator_to_array($this->notification->get());
		$this->notification->markAllAsRead();
		return Response::json($results);
	}


    /**
     * display all of a users comments
     *
     * @param string  $username
     * @return Illuminate\View\View
     */
    public function comments($username)
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

        return View::make('page.user.comments', [
            'sections'  => $sections,
            'section'   => $section,
            'comments'  => $this->user->comments($username, $this->vote),
            'username'  => $username,
            'highlight' => 'comments'
        ]);
    }


    /**
     * see `commentsJson`
     *
     * @param string  $username
     * @return Illuminate\Http\JsonResponse
     */
    public function commentsJson($username)
    {
        return Response::json(iterator_to_array($this->user->comments($username, $this->vote)));
    }


    /**
     * display all of a users posts
     *
     * @param string  $username
     * @return Illuminate\View\View
     */
    public function posts($username)
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

        return View::make('page.user.posts', [
            'sections'  => $sections,
            'section'   => $section,
            'posts'     => $this->user->posts($username, $this->vote),
            'username'  => $username,
            'highlight' => 'posts'
        ]);
    }


    /**
     * see `posts`
     *
     * @param string  $username
     * @return Illuminate\Http\JsonResponse
     */
    public function postsJson($username)
    {
        return Response::json(iterator_to_array($this->user->posts($username, $this->vote)));
    }


    /**
     * display all of a users votes on posts
     *
     * @param string  $username
     * @return Illuminate\View\View
     */
    public function postsVotes($username)
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

        return View::make('page.vote.user_posts', [
            'sections'   => $sections,
            'section'    => $section,
            'votes'      => $this->user->postsVotes($username),
            'username'   => $username,
            'highlight'  => 'pvotes'
        ]);
    }


    /**
     * see `postsVotes`
     *
     * @param string  $username
     * @return Illuminate\Http\JsonResponse
     */
    public function postsVotesJson($username)
    {
        return Response::json(iterator_to_array($this->user->postsVotes($username)));
    }


    /**
     * display all of a users votes on comments
     *
     * @param string  $username
     * @return Illuminate\View\View
     */
    public function commentsVotes($username)
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

        return View::make('page.vote.user_comments', [
            'sections'  => $sections,
            'section'   => $section,
            'votes'     => $this->user->commentsVotes($username),
            'username'  => $username,
            'highlight' => 'cvotes'
        ]);
    }


    /**
     * see `commentsVotes`
     *
     * @param string  $username
     * @return Illuminate\Http\JsonResponse
     */
    public function commentsVotesJson($username)
    {
        return Response::json(iterator_to_array($this->user->commentsVotes($username)));
    }


    /**
     * show users page with links to all activity
     *
     * @param string  $username
     * @return Illuminate\View\View
     */
    public function mainVote($username)
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();
        $stats = $this->user->userStats($username);

        return View::make('page.user.profile', [
            'sections'  => $sections,
            'section'   => $section,
            'username'  => $username,
            'stats'     => $stats,
            'highlight' => ''
        ]);
    }
}
