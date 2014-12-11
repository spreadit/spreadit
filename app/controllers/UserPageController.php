<?php                                                                                                                                       
class UserPageController extends BaseController
{

    /**
     * shows the users personal homepage
     *
     * @param string  $username
     * @return Illuminate\View\View
     */
    public function index($username)
    {   
        $user = DB::table('users')
            ->select('profile_data')
            ->where('username', 'LIKE', $username)
            ->first();

        if(is_null($user)) {
             App::abort(404);
        }
 
        return View::make('page.userhomepage', [
            'username' => $username,
            'html'     => $user->profile_data,
        ]);
    }   
 
    /**
     * renders the users personal homepage's css stylesheet
     *
     * @param string  $username
     * @return mixed
     */
    public function css($username) {
        $user = DB::table('users')
            ->select('profile_css')
            ->where('username', 'LIKE', $username)
            ->first();

        if(is_null($user)) {
             App::abort(404);
        }
 
        $response = Response::make($user->profile_css);
        $response->header('Content-Type', 'text/css');
 
        return $response;
    }   
}
