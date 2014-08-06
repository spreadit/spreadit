<?php
use \Michelf\MarkdownExtra;

class UtilityController extends BaseController
{
    protected function imagewrapper()
    {
        return View::make('imagewrapper');
    }

    protected function generate_view($view)
    {
        $allowed_views = ['commentreplybox', 'commentsourcebox', 'commenteditbox', 'postreplybox', 'postsourcebox', 'posteditbox'];
        if(!in_array($view, $allowed_views)) return "not allowed";
        return View::make($view, Input::all());
    }

    protected function titlefromurl()
    {
        $result = Input::has('url') ? Utility::titleFromUrl(Input::get('url')) : "url not given";
        return Response::json(['response' => $result]);
    }

    protected function preview()
    {
       return Response::make(MarkdownExtra::defaultTransform(e(Input::get('data'))));
    }
}
