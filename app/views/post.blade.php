@extends('layout.default')

@section('title')
    <title>spreadit.io :: {{ $post->title }}</title>
@stop

@section('style')
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
                        <span class="vote {{ $post->selected == Vote::UP ? 'selected' : '' }} {{ $post->selected == Vote::DOWN ? 'disable-click' : ''}}" data-id="{{ $post->id }}" data-type="post" data-updown="up">&#x25B2;</span>
                        <span class="vote {{ $post->selected == Vote::DOWN ? 'selected' : '' }} {{ $post->selected == Vote::UP ? 'disable-click' : '' }}" data-id="{{ $post->id }}" data-type="post" data-updown="down">&#x25BC;</span>
                        <a href="{{ URL::to("/vote/post/".$post->id) }}">
                            <span class="upvotes">{{ $post->upvotes }}</span>-<span class="downvotes">{{ $post->downvotes }}</span> <span class="total-points">{{ $post->upvotes - $post->downvotes }}</span>
                        </a>
                    </div>
                    <div class="breaker points-created_at">
                        {{ Utility::prettyAgo($post->created_at) }}
                    </div>
                    <div class="breaker points-creator">
                        <a rel="nofollow" href="{{ URL::to("/u/".$post->username) }}">{{ $post->username }}</a>({{ $post->points }})
                    </div>
                </div>
                <div class="post-data">
                    <div class="breaker data-title">
                        <h1><a rel="nofollow" href="{{ URL::to($post->url) }}">{{ $post->title }}</a></h1>
                    </div>
                    <div class="breaker data-data">
                        {{ $post->data }}
                    </div>
                    <menu>
                    @if (Auth::check())
                        <label class="post-action reply" for="collapse-postreply{{ $post->id }}">reply </label>
                        <input class="collapse" id="collapse-postreply{{ $post->id }}" type="checkbox">
                        <div class="replybox">
                            <form id="comment-form" method="post" class="flat-form flatpop-left">
                                <input type="hidden" name="parent_id" value="0">
                                <p class="text">
                                    <textarea name="data" id="data" placeholder="You have {{ (Comment::MAX_COMMENTS_PER_DAY - Comment::getCommentsInTimeoutRange()) }} of {{ Comment::MAX_COMMENTS_PER_DAY }} comments remaining ( per {{ Utility::prettyAgo(time() - Comment::MAX_COMMENTS_TIMEOUT_SECONDS) }})" maxlength="{{ Comment::MAX_MARKDOWN_LENGTH }}" required></textarea>
                                </p>
                                @if ((Comment::MAX_COMMENTS_PER_DAY - Comment::getCommentsInTimeoutRange()) > 0)
                                <div class="submit">
                                    <button type="submit" formmethod="post" formaction="{{ URL::to('/util/preview') }}" formtarget="previewpost-reply-box{{ $post->id }}" class="preview">Preview</button>
                                    <button type="submit">Post</button>
                                </div>
                                 @endif
                            </form>
                            <div class="preview-box"><iframe name="previewpost-reply-box{{ $post->id }}"></iframe></div>
                        </div>
                        

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
                    @else
                        <a href="{{ URL::to("/login") }}">Register</a> to post replies
                    @endif
                    </menu>
                </div>
                <div class="post-thumbnail">
                    @if (!empty($post->thumbnail))
                    <img alt="{{{ $post->title }}}" src="/assets/thumbs/{{ $post->thumbnail }}.jpg">
                    @endif
                </div>
            </div>
            {{ $comments }}
        </div>
    </div>
</div>
@stop
@section('script')
@stop
