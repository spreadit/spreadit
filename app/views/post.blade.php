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
<div>
    <div class="posts-container">
        <div class="post">
            <div class="post-piece" tabindex="1">
                <div class="post-points">
                    <div class="breaker points-actions">
                        <a href="{{ URL::to("/vote/post/" . $post->id . "/up") }}" {{ UtilityController::bubbleText() }} class="vote {{ UtilityController::bubbleClasses($post) }} {{ UtilityController::upvoteClasses($post) }}" data-id="{{ $post->id }}" data-type="post" data-updown="up">&#x25B2;</a>
                        <a href="{{ URL::to("/vote/post/" . $post->id . "/down") }}" {{ UtilityController::bubbleText() }} class="vote {{ UtilityController::bubbleClasses($post) }} {{ UtilityController::downvoteClasses($post) }}" data-id="{{ $post->id }}" data-type="post" data-updown="down">&#x25BC;</a>
                        <a href="{{ URL::to("/vote/post/".$post->id) }}">
                            <span class="upvotes">{{ $post->upvotes }}</span>-<span class="downvotes">{{ $post->downvotes }}</span> <span class="total-points">{{ $post->upvotes - $post->downvotes }}</span>
                        </a>
                    </div>
                    <div class="breaker points-created_at">
                        {{ Utility::prettyAgo($post->created_at) }}
                    </div>
                    <div class="breaker points-creator">
                        <a class="username {{ UtilityController::anonymousClasses($post) }}" rel="nofollow" href="{{ URL::to("/u/".$post->username) }}">{{ $post->username }}</a>(<span class="upoints">{{ $post->points }}</span>,<span class="uvotes">{{ $post->votes }}</span>)
                    </div>
                </div>
                <div class="post-thumbnail">
                    @if (!empty($post->thumbnail))
                        @if (!empty($post->url))
                            <a rel="nofollow" href="{{ UtilityController::postUrl($post) }}">
                                <img alt="{{{ $post->title }}}" src="/assets/thumbs/{{ $post->thumbnail }}.jpg">
                            </a>
                        @else
                            <img alt="{{{ $post->title }}}" src="/assets/thumbs/{{ $post->thumbnail }}.jpg">
                        @endif
                    @endif
                </div>
                <div class="post-data">
                    <div class="breaker data-title">
                        <h1><a rel="nofollow" href="{{ UtilityController::postUrl($post) }}">{{ $post->title }}</a></h1>
                    </div>
                    <div class="breaker data-data">
                        {{ $post->data }}
                    </div>
                    <menu>
                        @include ('replyframepart', ['post_id' => $post->id, 'parent_id' => 0])

                        <label class="post-action source" for="collapse-postsource{{ $post->id }}">source </label>
                        <input class="collapse" id="collapse-postsource{{ $post->id }}" type="checkbox">
                        <div class="sourcebox">
                            <p class="text">
                                <textarea readonly>{{ $post->markdown }}</textarea>
                            </p>
                        </div>


                        @if ($post->user_id == Auth::id())
                            <label class="post-action edit" for="collapse-postedit{{ $post->id }}">edit </label>
                            <input class="collapse" id="collapse-postedit{{ $post->id }}" type="checkbox">
                            <div class="editbox">
                                <form id="edit-form" action="{{ URL::to("/posts/" . $post->id . "/update") }}" method="post">
                                    <p class="text">
                                        <textarea name="data" id="data" required maxlength="{{ Post::MAX_MARKDOWN_LENGTH }}">{{ $post->markdown }}</textarea>
                                    </p>
                                    <div class="submit">
                                        <button type="submit" formmethod="post" formaction="{{ URL::to('/util/preview') }}" formtarget="previewpost-edit-box{{ $post->id }}" class="preview">Preview</button>
                                        <button type="submit">Update</button>
                                    </div>
                                </form>
                                <div class="preview-box"><iframe name="previewpost-edit-box{{ $post->id }}"></iframe></div>
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
    </div>
</div>
@stop
