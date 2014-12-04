<?php

class UserController extends BaseController
{
    protected function logout()
    {
        Auth::logout();
	    return Redirect::to('/');
    }

    protected function register()
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

    protected function validateLogin(array $data)
    {
        $data['username'] = e($data['username']);
        
        $rules = array(
            'username' => 'required|max:24',
            'password' => 'required|max:128',
        );

        return Validator::make($data, $rules);
    }

    protected function login()
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

    protected function notifications()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

		$view = View::make('page.user.notifications', [
			'sections'      => $sections,
			'section'       => $section,
			'notifications' => Notification::get()
		]);

		Notification::markAllAsRead();

		return $view;
    }

    protected function notificationsJson()
    {
		$results = iterator_to_array(Notification::get());
		Notification::markAllAsRead();
		return Response::json($results);
	}

    protected function comments($username)
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('page.user.comments', [
            'sections'  => $sections,
            'section'   => $section,
            'comments'  => User::comments($username),
            'username'  => $username,
            'highlight' => 'comments'
        ]);
    }

    protected function commentsJson($username)
    {
        return Response::json(iterator_to_array(User::comments($username)));
    }

    protected function posts($username)
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('page.user.posts', [
            'sections'  => $sections,
            'section'   => $section,
            'posts'     => User::posts($username),
            'username'  => $username,
            'highlight' => 'posts'
        ]);
    }

    protected function postsJson($username)
    {
        return Response::json(iterator_to_array(User::posts($username)));
    }

    protected function postsVotes($username)
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('page.vote.user_posts', [
            'sections'   => $sections,
            'section'    => $section,
            'votes'      => User::postsVotes($username),
            'username'   => $username,
            'highlight'  => 'pvotes'
        ]);
    }

    protected function postsVotesJson($username)
    {
        return Response::json(iterator_to_array(User::postsVotes($username)));
    }

    protected function commentsVotes($username)
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('page.vote.user_comments', [
            'sections'  => $sections,
            'section'   => $section,
            'votes'     => User::commentsVotes($username),
            'username'  => $username,
            'highlight' => 'cvotes'
        ]);
    }

    protected function commentsVotesJson($username)
    {
        return Response::json(iterator_to_array(User::commentsVotes($username)));
    }

    protected function mainVote($username)
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));
        $stats = User::userStats($username);

        return View::make('page.user.profile', [
            'sections'  => $sections,
            'section'   => $section,
            'username'  => $username,
            'stats'     => $stats,
            'highlight' => ''
        ]);
    }
}
