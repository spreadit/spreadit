@extends('layout.default')

@section('title')
    <title>spreadit.io :: {{ $post->title }}</title>
@stop
@section('description')
    <meta name="description" content="spreadit discussion regarding {{ $post->title }}">
@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-warning fade in">
            <div class="close" data-dismiss="alert" aria-hidden="true">&times;</div>
            <h4 class="text-center">{{ $errors->first() }}</h4>
        </div>
    @endif
    <div class="posts-container">
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
@stop
