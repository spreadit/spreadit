@extends('layout.default')

@section('title')
    <title>spreadit.io :: {{ $post->title }}</title>
@stop
@section('description')
    <meta name="description" content="spreadit discussion regarding {{ $post->title }}">
@stop

@section('bodyclasses')
single-page comments-page
@stop

@section('style')
<style>
    body >.content .link .rank,
    .rank-spacer {
        width: 1.1ex
    }
    body >.content .link .midcol,
    .midcol-spacer {
        width: 3.1ex
    }
    .adsense-wrap {
        background-color: #eff7ff;
        font-size: 18px;
        padding-left: 4.2ex;
        padding-right: 5px;
    }
</style>
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-warning fade in">
            <div class="close" data-dismiss="alert" aria-hidden="true">&times;</div>
            <h4 class="text-center">{{ $errors->first() }}</h4>
        </div>
    @endif
    <div class="content" role="main">
        <div id="siteTable" class="sitetable linklisting">
            <div class=" thing id-t3_3brjsf odd&#32; link " onclick="click_thing(this)" data-fullname="t3_3brjsf" data-cid="">
                <p class="parent"></p><span class="rank"></span>
                <div class="midcol unvoted">
                    <div class="arrow up login-required" onclick="$(this).vote(r.config.vote_hash, null, event)" role="button" aria-label="upvote" tabindex="0"></div>
                    <div class="score dislikes">{{ $post->downvotes }}</div>
                    <div class="score unvoted">{{ $post->upvotes - $post->downvotes }}</div>
                    <div class="score likes">{{ $post->upvotes }}</div>
                    <div class="arrow down login-required" onclick="$(this).vote(r.config.vote_hash, null, event)" role="button" aria-label="downvote" tabindex="0"></div>
                </div>
                <a class="thumbnail default may-blank " href="{{{ $post->url }}}" rel="nofollow"></a>
                <div class="entry unvoted">
                    <p class="title"><a class="title may-blank " href="{{{ $post->url }}}" tabindex="1" rel="nofollow">{{{ $post->title }}}</a>&#32;<span class="domain">({{{ $selfpost ? 'self.' . $post->section_title : $post->url   }}})</span>
                    </p>
                    <p class="tagline">submitted&#32;
                        <time title="Wed Jul 1 16:00:16 2015 UTC" datetime="2015-07-01T09:00:16-07:00" class="live-timestamp">2 days ago</time>&#32;by&#32;<a href="/u/{{{ $post->username }}}" class="author may-blank id-t2_b4o62">{{{ $post->username }}}</a><span class="userattrs"></span>
                    </p>
                    <ul class="flat-list buttons">
                        <li class="first">
                            <a href="https://www.reddit.com/r/STCKY/comments/3brjsf/rh2kgaming_is_now_using_rstcky/" class="comments may-blank">
                                {{ $post->comment_count }} comment
                            </a>
                        </li>
                    </ul>
                    <div class="expando" style='display: none'><span class="error">loading...</span>
                    </div>
                </div>
                <div class="child"></div>
                <div class="clearleft"></div>
            </div>
            <div class="clearleft"></div>
        </div>
        <div class='commentarea'>
            <div class="panestack-title"><span class="title">all {{ $post->comment_count }} comments</span>
            </div>
            <div class="menuarea">
                <div class="spacer"><span class="dropdown-title lightdrop">sorted by:&#32;</span>
                    <div class="dropdown lightdrop" onclick="open_menu(this)"><span class="selected">best</span>
                    </div>
                    <div class="drop-choices lightdrop"><a href="https://www.reddit.com/r/STCKY/comments/3brjsf/rh2kgaming_is_now_using_rstcky/?sort=top" class="choice">top</a><a href="https://www.reddit.com/r/STCKY/comments/3brjsf/rh2kgaming_is_now_using_rstcky/?sort=new" class="choice">new</a><a href="https://www.reddit.com/r/STCKY/comments/3brjsf/rh2kgaming_is_now_using_rstcky/?sort=hot" class="hidden choice">hot</a><a href="https://www.reddit.com/r/STCKY/comments/3brjsf/rh2kgaming_is_now_using_rstcky/?sort=controversial" class="choice">controversial</a><a href="https://www.reddit.com/r/STCKY/comments/3brjsf/rh2kgaming_is_now_using_rstcky/?sort=old" class="choice">old</a><a href="https://www.reddit.com/r/STCKY/comments/3brjsf/rh2kgaming_is_now_using_rstcky/?sort=random" class="hidden choice">random</a><a href="https://www.reddit.com/r/STCKY/comments/3brjsf/rh2kgaming_is_now_using_rstcky/?sort=qa" class="choice">q&amp;a</a>
                    </div>
                </div>
                <div class="spacer"></div>
            </div>
            <div class="sitetable nestedlisting">
                {{ $comments }}
            </div>
        </div>
    </div>
    </div>
<!--    <div class="posts-container">
        <div class="post">
            <div class="post-piece {{ $selfpost }}" tabindex="1" data-comment-id="0" data-post-id="{{ $post->id }}">
                <div class="post-points">
                    <div class="breaker points-actions">
                        <a href="{{ URL::to("/vote/post/" . $post->id . "/up") }}" {{ $bubbleText }} class="vote {{ $bubbleClasses }} {{ $upvoteClasses }}" data-id="{{ $post->id }}" data-type="post" data-updown="up" rel="nofollow"><span class="voteiconup"></span></a>
                        <a href="{{ URL::to("/vote/post/" . $post->id . "/down") }}" {{ $bubbleText }} class="vote {{ $bubbleClasses }} {{ $downvoteClasses }}" data-id="{{ $post->id }}" data-type="post" data-updown="down" rel="nofollow"><span class="voteicondown"></span></a>
                        <a href="{{ URL::to("/vote/post/".$post->id) }}">
                            <span class="upvotes">{{ $post->upvotes }}</span><span class="downvotes">{{ $post->downvotes }}</span><span class="total-points">{{ $post->upvotes - $post->downvotes }}</span>
                        </a>
                    </div>
                    <div class="breaker points-created_at">
                        {{ Utility::prettyAgo($post->created_at) }}
                    </div>
                    <div class="breaker points-creator">
                        <a class="username {{ $anonymousClasses }}" rel="nofollow" href="{{ URL::to("/u/".$post->username) }}">{{ $post->username }}</a><span class="upoints">{{ $post->points }}</span><span class="uvotes">{{ $post->votes }}</span>
                    </div>
                </div>
                <div class="post-thumbnail">
                    @if (!empty($post->thumbnail))
                        @if ($selfpost)
                        <a rel="nofollow" href="{{ URL::to($post->url) }}">
                        @else
                        <a href="{{ URL::to($post->url) }}">
                        @endif
                            <span class="thumb-small"><img alt="{{{ $post->title }}}" src="/assets/thumbs/small/{{ $post->thumbnail }}.jpg"></span>
                            <span class="thumb-large"><img alt="{{{ $post->title }}}" src="/assets/thumbs/large/{{ $post->thumbnail }}.jpg"></span>
                        </a>
                    @endif
                </div>
                <div class="post-data">
                    <div class="breaker data-title">
                        <h1><a rel="nofollow" class="post-title" href="{{ $postUrl }}">{{ $post->title }}</a></h1>
                    </div>
                    <div class="breaker data-data">
                        <div class="post-content">{{ $post->data }}</div>
                    </div>
                    <menu>
                        @include ('shared.replyframe', ['post_id' => $post->id, 'parent_id' => 0])

                        <label class="post-action source" for="collapse-postsource{{ $post->id }}">source </label>
                        <input class="collapse" id="collapse-postsource{{ $post->id }}" type="checkbox">
                        <div class="sourcebox">
                            <p class="text">
                                <textarea readonly>{{ $post->markdown }}</textarea>
                            </p>
                        </div>

                        <label class="post-action tag" for="collapse-posttag{{ $post->id }}">tag </label>
                        <input class="collapse" id="collapse-posttag{{ $post->id }}" type="checkbox">
                        <div class="tagbox">
                            <p class="text">
                                <form method="post" class="tag" action="{{ URL::to("/posts/" . $post->id . "/tag/nsfw") }}"><button>nsfw</button></form> 
                                @if ($post->nsfw > 0)
                                    <form method="post" class="tag" action="{{ URL::to("/posts/" . $post->id . "/tag/sfw") }}"><button>sfw</button></form> 
                                @endif
                                <form method="post" class="tag" action="{{ URL::to("/posts/" . $post->id . "/tag/nsfl") }}"><button>nsfl</button></form> 
                                @if ($post->nsfl > 0)
                                    <form method="post" class="tag" action="{{ URL::to("/posts/" . $post->id . "/tag/sfl") }}"><button>sfl</button></form> 
                                @endif
                            </p>
                        </div>


                        @if ($post->user_id == Auth::id())
                            <label class="post-action edit" for="collapse-postedit{{ $post->id }}">edit </label>
                            <input class="collapse collapse-edit" id="collapse-postedit{{ $post->id }}" type="checkbox">
                            <div class="editbox">
                                <form id="edit-form" action="{{ URL::to("/posts/" . $post->id . "/update") }}" method="post">
                                    <p class="text">
                                        <textarea name="data" id="data" required maxlength="{{ Constant::POST_MAX_MARKDOWN_LENGTH }}">{{ $post->markdown }}</textarea>
                                    </p>
                                    <div class="submit">
                                        <button type="submit" formmethod="post" formaction="{{ URL::to('/util/preview') }}" formtarget="previewpost-edit-box{{ $post->id }}" class="preview">Preview</button>
                                        <button type="submit">Update</button>
                                    </div>
                                </form>
                                <div class="preview-box">
                                    <noscript>
                                        <iframe name="previewpost-edit-box{{ $post->id }}"></iframe>
                                    </noscript>
                                </div>
                            </div>

                            <label class="post-action delete" for="collapse-postdelete{{ $post->id }}">delete </label>
                            <input class="collapse" id="collapse-postdelete{{ $post->id }}" type="checkbox">
                            <div class="deletebox">
                                <form id="delete-form" action="{{ URL::to("/posts/" . $post->id . "/delete") }}" method="post">
                                    <p class="text">Are you positive?</p>
                                    <div class="submit">
                                        <button type="submit">Yup</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </menu>
                </div>
            </div>
            {{ $comments }}
        </div>
    </div>
    <div class="sidebar">
    @include ('shared.sidebar')
    </div>
    @include ('shared.modal.info')

    -->
@stop
