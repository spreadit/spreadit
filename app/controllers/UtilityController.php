<?php
class UtilityController extends BaseController
{
    protected $comment;
    protected $post;
    protected $vote;

    public function __construct(Comment $comment, Post $post, Vote $vote)
    {
        $this->comment = $comment;
        $this->post = $post;
        $this->vote = $vote;
    }

    public function imagewrapper()
    {
        return View::make('util.imagewrapper');
    }

    public function redirect_to_add_post()
    {
        return Redirect::to(URL::to("/s/" . Input::get('url', '/') . "/add"));
    }

    public function titlefromurl()
    {
        $result = Input::has('url') ? Utility::titleFromUrl(Input::get('url')) : "url not given";
        return Response::json(['response' => $result]);
    }

    public function preview()
    {
        return View::make('util.preview', ['data' => Markdown::defaultTransform(e(Input::get('data')))]);
    }

    public function previewNoEnclosingPage()
    {
        $markdown = Markdown::defaultTransform(e(Input::get('data')));
        $response = Response::make($markdown);
        $response->header('Content-Type', 'text/html');

        return $response;
    }

    public function thumbnail() {
        if(!Input::has("id") || !Input::has("url")) {
            return "missing id or url";
        }

        Utility::getThumbnailForPost(Input::get("id"), Input::get("url"));
        return "";
    }

    
    public function newCaptcha()
    {
        $response = Response::make(HTML::image(Captcha::img(), 'Captcha image'));
        $response->header('Content-Type', 'text/html');

        return $response;
    }
}
