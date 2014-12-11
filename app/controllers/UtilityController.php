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

    /**
     * make image fullscreen for thumbnailing
     *
     * @return Illuminate\View\View
     */
    public function imagewrapper()
    {
        return View::make('util.imagewrapper');
    }

    public function redirect_to_add_post()
    {
        return Redirect::to(URL::to("/s/" . Input::get('url', '/') . "/add"));
    }

    /**
     * get the title from a url
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function titlefromurl()
    {
        $result = Input::has('url') ? Utility::titleFromUrl(Input::get('url')) : "url not given";
        return Response::json(['response' => $result]);
    }

    /**
     * compile markdown to html and render inside a page
     *
     * @return Illuminate\View\View
     */
    public function preview()
    {
        return View::make('util.preview', ['data' => Markdown::defaultTransform(e(Input::get('data')))]);
    }

    /**
     * compile markdown to html and render as text
     *
     * @return Illuminate\View\View
     */
    public function previewNoEnclosingPage()
    {
        $markdown = Markdown::defaultTransform(e(Input::get('data')));
        $response = Response::make($markdown);
        $response->header('Content-Type', 'text/html');

        return $response;
    }

    /**
     * create a thumbnail for a post
     *
     * @return Illuminate\Http\Response
     */
    public function thumbnail() {
        if(!Input::has("id") || !Input::has("url")) {
            return Response::make("missing id or url");
        }

        Utility::getThumbnailForPost(Input::get("id"), Input::get("url"));
        return Response::make("");
    }

    /**
     * create new captcha image and display it alone
     *
     * @return Illuminate\Http\Response
     */    
    public function newCaptcha()
    {
        $response = Response::make(HTML::image(Captcha::img(), 'Captcha image'));
        $response->header('Content-Type', 'image/png');

        return $response;
    }
}
