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
        $user = new User;
        $user->username = e(Input::get('username'));
        $user->password = Input::get('password');
        $user->password_confirmation = Input::get('password_confirmation');
        $user->captcha = Input::get('captcha');

        if($user->save()) {
            $info = Input::only('username', 'password');
        
            if(Auth::attempt($info)) {
                return Redirect::intended('/s/all/hot');
            } else {
                return "please try again"; //todo make this not suck
            }
        } else {
            return Redirect::to('/login')->withErrors($user->errors())->withInput();
        }
    }

    protected function login()
    {
        $data = Input::only('username', 'password');
        $data['username'] = e($data['username']);
        
        $rules = array(
            'username' => 'required|max:24',
            'password' => 'required|max:128',
        );
        $validate = Validator::make($data, $rules);
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

		$view = View::make('notifications', [
			'sections' => $sections,
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

    protected function unreadNotifications()
    {
		$json = Notification::getUnread();
		return Response::make($json)->header('Content-Type', 'application/json');
    }


    protected function comments($username)
    {
        return View::make('user_comments', [
            'sections' => Section::get(),
            'comments' => User::comments($username),
            'username' => $username,
            'highlight' => 'comments'
        ]);
    }

    protected function posts($username)
    {
        return View::make('user_posts', [
            'sections' => Section::get(),
            'posts' => User::posts($username),
            'username' => $username,
            'highlight' => 'posts'
        ]);
    }

    protected function postsVotes($username)
    {
        return View::make('user_posts_votes', [
            'sections' => Section::get(),
            'votes' => User::postsVotes($username),
            'username' => $username,
            'highlight' => 'pvotes'
        ]);
    }

    protected function commentsVotes($username)
    {
        return View::make('user_comments_votes', [
            'sections' => Section::get(),
            'votes' => User::commentsVotes($username),
            'username' => $username,
            'highlight' => 'cvotes'
        ]);
    }

    protected function mainVote($username)
    {
        return View::make('user_votes_page', [
            'sections' => Section::get(),
            'username' => $username,
            'highlight' => ''
        ]);
    }
}
