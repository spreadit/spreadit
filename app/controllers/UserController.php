<?php
use Functional as F;

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

		$view = View::make('notifications', [
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
    
    protected function preferences()
    {
        $section_titles = function($input) {
            if(strlen(Auth::user()->$input) == 0) {
                return "";
            }

            $input_data = Auth::user()->$input;
            $arr = explode(',', $input_data);

            return implode(',', array_unique(F\map(Section::getById($arr), function($m) {
                return $m->title;
            })));
        };

        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));
        
        $frontpage_show_sections   = $section_titles('frontpage_show_sections');
        $frontpage_ignore_sections = $section_titles('frontpage_ignore_sections');

		return View::make('preferences', [
            'sections'                  => $sections,
            'section'                   => $section,
            'frontpage_show_sections'   => $frontpage_show_sections,
            'frontpage_ignore_sections' => $frontpage_ignore_sections,
		]);
    }

    protected function preferencesJson()
    {
        return Response::json("moo");
    }

    protected function savePreferences()
    {
        $section_ids = function($input) {
            $input_data = Input::get($input, '');
            
            if(strlen($input_data) == 0) {
                return "";
            }
            
            $arr = explode(',', $input_data);

            return implode(',', array_unique(F\map(Section::getByTitle($arr), function($m) {
                return $m->id;
            })));
        };
        
        $show_nsfw = Input::get('show_nsfw', 0);
        $show_nsfl = Input::get('show_nsfl', 0);

        $frontpage_show_sections   = $section_ids('frontpage_show_sections');
        $frontpage_ignore_sections = $section_ids('frontpage_ignore_sections');

        
        if($frontpage_show_sections   == "0") $frontpage_show_sections   = "";
        if($frontpage_ignore_sections == "0") $frontpage_ignore_sections = "";

        User::savePreferences(Auth::id(), [
            'show_nsfw'                 => $show_nsfw,
            'show_nsfl'                 => $show_nsfl,
            'frontpage_show_sections'   => $frontpage_show_sections,
            'frontpage_ignore_sections' => $frontpage_ignore_sections,
        ]);

        return $this->savedPreferences();
    }

    protected function savedPreferences()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

		return View::make('savedpreferences', [
			'sections' => $sections,
			'section'  => $section,
		]);
    }

    protected function comments($username)
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('user_comments', [
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

        return View::make('user_posts', [
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

        return View::make('user_posts_votes', [
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

        return View::make('user_comments_votes', [
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

        return View::make('user_votes_page', [
            'sections'  => $sections,
            'section'   => $section,
            'username'  => $username,
            'stats'     => $stats,
            'highlight' => ''
        ]);
    }
}
