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

    public function logout()
    {
        Auth::logout();
	    return Redirect::to('/');
    }

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
                return Redirect::intended('/s/all/hot');
            } else {
                return Redirect::to('/login')->withErrors(['message' => 'A general error occurred, please try again.'])->withInput();
            }
        } else {
            return Redirect::to('/login')->withErrors($user->errors())->withInput();
        }
    }

    public function validateLogin(array $data)
    {
        $data['username'] = e($data['username']);
        
        $rules = array(
            'username' => 'required|max:24',
            'password' => 'required|max:128',
        );

        return Validator::make($data, $rules);
    }

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

    public function notificationsJson()
    {
		$results = iterator_to_array($this->notification->get());
		$this->notification->markAllAsRead();
		return Response::json($results);
	}

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

    public function commentsJson($username)
    {
        return Response::json(iterator_to_array($this->user->comments($username, $this->vote)));
    }

    public function posts($username)
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

        return View::make('page.user.posts', [
            'sections'  => $sections,
            'section'   => $section,
            'posts'     => $this->user->posts($username, $this->votes),
            'username'  => $username,
            'highlight' => 'posts'
        ]);
    }

    public function postsJson($username)
    {
        return Response::json(iterator_to_array($this->user->posts($username, $this->vote)));
    }

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

    public function postsVotesJson($username)
    {
        return Response::json(iterator_to_array($this->user->postsVotes($username)));
    }

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

    public function commentsVotesJson($username)
    {
        return Response::json(iterator_to_array($this->user->commentsVotes($username)));
    }

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
