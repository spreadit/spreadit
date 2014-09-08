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

        return View::make('preview', ['data' => MarkdownExtra::defaultTransform(e(Input::get('data')))]);
    }

    public static function showBubble()
    {
        return (!Auth::check()
            || Auth::user()->anonymous
            || (Auth::check() && Auth::user()->votes < 6));
    }

    public static function bubbleText()
    {
        if(self::showBubble()) {
            return (!Auth::check() || Auth::user()->anonymous) 
                ? 'data-hint="need to register to vote"'
                : 'data-hint="costs one point to vote"';
        } else {
            return "";
        }
    }

    public static function bubbleClasses()
    {
        return self::showBubble() ? "hint--right hint--bounce hint--warning" : '';
    }

    public static function upvoteClasses($item)
    {
        return  ($item->selected == Vote::UP   ? 'selected' : '')
              . ($item->selected == Vote::DOWN ? ' disable-click' : '');
    }

    public static function downvoteClasses($item)
    {
        return ($item->selected == Vote::DOWN ? 'selected' : '') 
             . ($item->selected == Vote::UP   ? ' disable-click' : '');
    }

    public static function anonymousClasses($item)
    {
        return $item->anonymous ? 'user-anonymous' : '';
    }

    public static function commentsPrettyUrl($item)
    {
        return parse_url($item->type == Post::SELF_POST_TYPE ? URL::to('/') : $item->url, PHP_URL_HOST);
    }
    
    public static function commentsUrl($item)
    {
        return URL::to("/s/{$item->section_title}/posts/{$item->id}/" . Utility::prettyUrl($item->title));
    }

    public static function postUrl($item)
    {
        return ($item->type == Post::SELF_POST_TYPE)
            ? URL::to("/s/{$item->section_title}/posts/{$item->id}/" . Utility::prettyUrl($item->title))
            : URL::to($item->url);
    }

    public static function sectionUrl($item)
    {
        return URL::to('/s/' . $item->section_title);
    }

    public static function colorschemeHtml()
    {
        if(strcmp(Cookie::get('colorscheme'), "light") == 0) {
            return '<link rel="stylesheet" href="/assets/css/colorschemes/light.css">';
        }
        
        return '';
    }
}
