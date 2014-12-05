<?php
class UtilityController extends BaseController
{
    protected function imagewrapper()
    {
        return View::make('util.imagewrapper');
    }

    protected function redirect_to_add_post()
    {
        return Redirect::to(URL::to("/s/" . Input::get('url', '/') . "/add"));
    }

    protected function titlefromurl()
    {
        $result = Input::has('url') ? Utility::titleFromUrl(Input::get('url')) : "url not given";
        return Response::json(['response' => $result]);
    }

    protected function preview()
    {
        return View::make('util.preview', ['data' => Markdown::defaultTransform(e(Input::get('data')))]);
    }

    protected function previewNoEnclosingPage()
    {
        $markdown = Markdown::defaultTransform(e(Input::get('data')));
        $response = Response::make($markdown);
        $response->header('Content-Type', 'text/html');

        return $response;
    }

    public static function thumbnail() {
        if(!Input::has("id") || !Input::has("url")) {
            return "missing id or url";
        }

        Utility::getThumbnailForPost(Input::get("id"), Input::get("url"));
        return "";
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

    public static function nsfClasses($post) {
        $result = "";
        if($post->nsfw > 0) $result .= "nsfw ";
        if($post->nsfl > 0) $result .= "nsfl ";
        return $result;
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

    public static function customCss()
    {
        $result = "";

        $bsrc = "";        
        if(strcmp(Cookie::get('theme'), "light") == 0) {
            $bsrc = "/assets/css/themes/light.css";
        } else if(strcmp(Cookie::get('theme'), "tiles") == 0) {
            $bsrc .= "/assets/css/themes/tiles.css"; 
        }

        if(!empty($bsrc)) {
            $result .= "<link rel=\"stylesheet\" media=\"screen\" href=\"".Bust::url($bsrc)."\">"; 
        }

        if(Auth::check()) {
            if(Auth::user()->show_nsfw) {
                $result .= "<link rel=\"stylesheet\" media=\"screen\" href=\"".Bust::url("/assets/css/prefs/show_nsfw.css")."\">"; 
            }
            if(Auth::user()->show_nsfl) {
                $result .= "<link rel=\"stylesheet\" media=\"screen\" href=\"".Bust::url("/assets/css/prefs/show_nsfl.css")."\">"; 
            }
        }


        return $result;
    }

    public static function oldSectionHtml($section)
    {
        return (!empty(Input::old('section')))
            ? Input::old('section')
            : $section->title;
    }

    public static function postsRemainingHtml() {
        return sprintf("You have %s of %s posts remaining per %s", 
            Utility::remainingPosts(),
            Utility::availablePosts(),
            Utility::prettyAgo(time() - Post::MAX_POSTS_TIMEOUT_SECONDS)
        );
    }
    
    public static function commentsRemainingHtml() {
        return sprintf("You have %s of %s comments remaining per %s", 
            Utility::remainingComments(),
            Utility::availableComments(),
            Utility::prettyAgo(time() - Comment::MAX_COMMENTS_TIMEOUT_SECONDS)
        );
    }
}
