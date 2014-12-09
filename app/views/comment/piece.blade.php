<div class="comment-piece" tabindex="1" data-comment-id="{{ $comment->id }}">
    <a name="comment_{{ $comment->id }}"></a>
    <div class="comment-points">
        <div class="breaker comment-user-points">
            <a class="username {{ $anonymousClasses }}" rel="nofollow" href="{{ URL("/u/{$comment->username}") }}">{{ $comment->username }}</a><span class="upoints">{{ $comment->points }}</span><span class="uvotes">{{ $comment->votes }}</span>
        </div>
        <div class="breaker comment-votes">
            <a href="{{ URL::to("/vote/comment/" . $comment->id . "/up") }}" {{ $bubbleText }} class="vote {{ $bubbleClasses }} {{ $upvoteClasses }}" data-id="{{ $comment->id }}" data-type="comment" data-updown="up" rel="nofollow"><span class="voteiconup"></span></a>
            <a href="{{ URL::to("/vote/comment/" . $comment->id . "/down") }}" {{ $bubbleText }} class="vote {{ $bubbleClasses }} {{ $downvoteClasses }}" data-id="{{ $comment->id }}" data-type="comment" data-updown="down" rel="nofollow"><span class="voteicondown"></span></a>
            <a href="{{ URL("/vote/comment/{$comment->id}") }}">
                <span class="upvotes">{{ $comment->upvotes  }}</span><span class="downvotes">{{ $comment->downvotes }}</span><span class="total-points">{{ ($comment->upvotes - $comment->downvotes) }}</span>
            </a>
        </div>
        <div class="breaker comment-created-at">
             {{ Utility::prettyAgo($comment->created_at) }}
        </div>
    </div>
    <div class="comment-data">
        <div class="comment-content">{{ $comment->data }}</div>
        @if (!isset($user_page))
            @include ('shared.replyframe', ['post_id' => $comment->post_id, 'parent_id' => $comment->id])

            @if ($comment->deleted_at == 0)
                <label class="comment-action source" for="collapse-source{{ $comment->id }}">source </label>
                <input class="collapse" id="collapse-source{{ $comment->id }}" type="checkbox">
                <div class="sourcebox">
                    <p class="text">
                        <textarea readonly>{{ $comment->markdown }}</textarea>
                    </p>
                </div>
                

                @if (Auth::check() && $comment->users_user_id == Auth::user()->id)
                    <label class="comment-action edit" for="collapse-edit{{ $comment->id }}">edit </label>
                    <input class="collapse" id="collapse-edit{{ $comment->id }}" type="checkbox">
                    <div class="editbox">
                        <form id="edit-form" action="/comments/{{ $comment->id }}/update" method="post">
                            <p class="text">
                                <textarea name="data" id="data" required maxlength="{{ Constant::COMMENT_MAX_MARKDOWN_LENGTH }}">{{ $comment->markdown }}</textarea>
                            </p>
                            <div class="submit">
                                <button type="submit" formmethod="post" formaction="{{ URL::to('/util/preview') }}" formtarget="previewcomment-edit-box{{ $comment->id }}" class="preview">Preview</button>
                                <button type="submit">Update</button>
                            </div>
                        </form>
                        <div class="preview-box">
                            <noscript>
                                <iframe name="previewcomment-edit-box{{ $comment->id }}"></iframe>
                            </noscript>
                        </div>
                    </div>

                    <label class="comment-action delete" for="collapse-commentdelete{{ $comment->id }}">delete </label>
                    <input class="collapse" id="collapse-commentdelete{{ $comment->id }}" type="checkbox">
                    <div class="deletebox">
                        <form id="delete-form" action="/comments/{{ $comment->id }}/delete" method="post">
                            <p class="text">Are you positive?</p>

                            <div class="submit">
                                <button type="submit">Yup</button>
                            </div>
                        </form>
                    </div>
                @endif
            @endif
        @endif

        <label class="comment-action" for="collapse-commentpermalink{{ $comment->id }}">permalink </label>
        <input class="collapse" id="collapse-commentpermalink{{ $comment->id }}" type="checkbox">
        <div class="permalinkbox">
            <input type="text" size="20" value="{{ URL::to("/comments/" . $comment->id) }}" readonly>
            <a href="{{ URL::to("/comments/" . $comment->id) }}">link</a>
        </div>
    </div>
</div>
