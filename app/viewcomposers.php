<?php

class ViewComposers {
    protected function upvoteClasses($view, $item_var)
    {
        $classes = "";

        if($view->$item_var->selected == Constant::VOTE_UP) {
            $classes .= 'selected';
        }
       
        if($view->$item_var->selected == Constant::VOTE_DOWN) {
            $classes .= ' disable-click';
        }

        return $classes;
    }

    protected function downvoteClasses($view, $item_var)
    {
        $classes = "";

        if($view->$item_var->selected == Constant::VOTE_DOWN) {
            $classes .= 'selected';
        }
       
        if($view->$item_var->selected == Constant::VOTE_UP) {
            $classes .= ' disable-click';
        }

        return $classes;
    }

    protected function anonymousClasses($view, $item_var) {
        return $view->$item_var->anonymous ? 'user-anonymous' : '';
    }

    protected function postUrl($view)
    {
        $postUrl = "";

        if($view->post->type == Constant::POST_SELF_POST_TYPE) {
            $postUrl = URL::to(sprintf("/s/%s/posts/%s/%s", 
                $view->post->section_title, 
                $view->post->id, 
                Utility::prettyUrl($view->post->title)
            ));
        } else {
            $postUrl = URL::to($view->post->url);
        }

        return $postUrl;
    }

    protected function selfpost($view)
    {
        return empty($view->post->url) ? 'self' : 'link';
    }

    public function init()
    {
        View::composer(['comment.piece', 'post.piece', 'layout.nav.sorting', 'page.post'], function($view)
        {
            $text = "";
            $classes = "";
            
            if (!Auth::check()
              || Auth::user()->anonymous
              || (Auth::check() && Auth::user()->votes < 6)) {

                $classes = "";

                if(!Auth::check() || Auth::user()->anonymous) {
                    $text = 'data-hint="need to register to vote" ';
                } else {
                    $text = 'data-hint="costs one point to vote" ';
                }
            }

            $view->with('bubbleText', $text);
            $view->with('bubbleClasses', $classes);
        });


        View::composer(['layout.nav.sorting'], function($view)
        {
            $view->with('upvoteClasses',   $this->upvoteClasses($view, 'section'));
            $view->with('downvoteClasses', $this->downvoteClasses($view, 'section'));
        });


        View::composer(['post.piece', 'page.post'], function($view)
        {
            $view->with('upvoteClasses',   $this->upvoteClasses($view, 'post'));
            $view->with('downvoteClasses', $this->downvoteClasses($view, 'post'));
            $view->with('anonymousClasses', $this->anonymousClasses($view, 'post'));
            $view->with('selfpost', $this->selfpost($view));
            $view->with('postUrl', $this->postUrl($view));
        });


        View::composer(['comment.piece'], function($view)
        {
            $view->with('upvoteClasses',   $this->upvoteClasses($view, 'comment'));
            $view->with('downvoteClasses', $this->downvoteClasses($view, 'comment'));
            $view->with('anonymousClasses', $this->anonymousClasses($view, 'comment'));
        });


        View::composer(['post.piece'], function($view) {
            $post = new Post;
            $view->with('postUrl', $this->postUrl($view));

            $commentsPrettyUrl = parse_url($view->post->type == $post->SELF_POST_TYPE ? URL::to('/') : $view->post->url, PHP_URL_HOST);
            $view->with('commentsPrettyUrl', $commentsPrettyUrl);

            $commentsUrl = URL::to(sprintf("/s/%s/posts/%s/%s", 
                $view->post->section_title, 
                $view->post->id,
                Utility::prettyUrl($view->post->title
            )));
            $view->with('commentsUrl', $commentsUrl);

            $nsfwClasses = "";
            if($view->post->nsfw > 0) $nsfwClasses .= "nsfw ";
            if($view->post->nsfl > 0) $nsfwClasses .= "nsfl ";
            $view->with('nsfwClasses', $nsfwClasses);

            $view->with('sectionUrl', URL::to('/s/' . $view->post->section_title));

        });


        View::composer(['page.newpost.post'], function($view) {
            $oldSection = (!empty(Input::old('section')))
                    ? Input::old('section')
                    : $view->section->title;

            $view->with('oldSection', $oldSection);

            $postsRemaining = sprintf("You have %s of %s posts remaining per %s", 
                Utility::remainingPosts(),
                Utility::availablePosts(),
                Utility::prettyAgo(time() - Constant::POST_MAX_POSTS_TIMEOUT_SECONDS)
            );

            $view->with('postsRemaining', $postsRemaining);
        });


        View::composer(['comment.replyboxform'], function($view) {
            $commentsRemaining = sprintf("You have %s of %s comments remaining per %s", 
                Utility::remainingComments(),
                Utility::availableComments(),
                Utility::prettyAgo(time() - Constant::COMMENT_MAX_COMMENTS_TIMEOUT_SECONDS)
            );

            $view->with('commentsRemaining', $commentsRemaining);
        });


        View::composer(['comment.before',
            'comment.saved',
            'comment.replybox',
            'util.preview',
            'layout.etc.metahead'], function($view)
        {
            $links = "";

            $theme_src = "";        
            if(strcmp(Cookie::get('theme'), "light") == 0) {
                $theme_src = "/assets/css/themes/light.css";
            } else if(strcmp(Cookie::get('theme'), "tiles") == 0) {
                $theme_src .= "/assets/css/themes/tiles.css"; 
            }

            if(!empty($theme_src)) {
                $links .= sprintf('<link rel="stylesheet" media="screen" href="%s">', Bust::url($theme_src)); 
            }

            if(Auth::check()) {
                if(Auth::user()->show_nsfw) {
                    $links .= sprintf('<link rel="stylesheet" media="screen" href="%s">', Bust::url("/assets/css/prefs/show_nsfw.css"));
                }
                if(Auth::user()->show_nsfl) {
                    $links .= sprintf('<link rel="stylesheet" media="screen" href="%s">', Bust::url("/assets/css/prefs/show_nsfl.css"));
                }
            }


            $view->with('customCss', $links);
        });

        View::composer(['layout.nav.user_actions'], function($view) {
            if(Auth::check()) {
                $notification = new Notification();
                $notification_count = $notification->getUnreadCount();
                $classes = "";
                if($notification_count > 0) {
                    $classes .= "unread-notifications ";
                }

                if(Request::segment(1) == 'notifications') {
                    $classes .= 'active ';
                }

                $view->with('notificationClasses', $classes);
                $view->with('notificationCount', $notification_count);
            }
        });
    }
}

$viewcomposers = new ViewComposers();
$viewcomposers->init();