<div class="comment-piece" tabindex="1">
    <div class="comment-points">
        <a rel="nofollow" href="{{ URL("/u/{$comment->username}") }}">{{ $comment->username }}</a> ({{ $comment->points }})
        <br>
        <span class="vote {{ $comment->selected == Vote::UP ? 'selected' : '' }} {{ $comment->selected == Vote::DOWN ? 'disable-click' : '' }}" data-id="{{ $comment->id }}" data-type="comment" data-updown="up">&#x25B2;</span>
        <span class="vote {{ $comment->selected == Vote::DOWN ? 'selected' : '' }} {{ $comment->selected == Vote::UP ? 'disable-click' : '' }}" data-id="{{ $comment->id }}" data-type="comment" data-updown="down">&#x25BC;</span>

        <a href="{{ URL("/vote/comment/{$comment->id}") }}">
            <span class="upvotes">{{ $comment->upvotes  }}</span>-<span class="downvotes">{{ $comment->downvotes }}</span> <span class="total-points">{{ ($comment->upvotes - $comment->downvotes) }}</span>
        </a>
        <br>
         {{ Utility::prettyAgo($comment->created_at) }}
    </div>
    <div class="comment-data">
        {{ $comment->data }}
        @if (Auth::check())
            @if (!isset($user_page))
                <label class="comment-action reply" for="collapse-reply{{ $comment->id }}">reply </label>
                <input class="collapse" id="collapse-reply{{ $comment->id }}" type="checkbox">
                <div class="replybox">
                    <form id="comment-form" method="post" class="flat-form flatpop-left">
                        <input type="hidden" name="parent_id" value="{{ $comment->parent_id }}">
                        <p class="text">
                            <textarea name="data" id="data" placeholder="You have {{ (Comment::MAX_COMMENTS_PER_DAY - Comment::getCommentsInTimeoutRange()) }} of {{ Comment::MAX_COMMENTS_PER_DAY }} comments remaining ( per {{ Utility::prettyAgo(time() - Comment::MAX_COMMENTS_TIMEOUT_SECONDS) }})" maxlength="{{ Comment::MAX_MARKDOWN_LENGTH }}" required></textarea>
                        </p>
                        @if ((Comment::MAX_COMMENTS_PER_DAY - Comment::getCommentsInTimeoutRange()) > 0)
                        <div class="submit">
                            <button type="submit" formmethod="post" formaction="{{ URL::to('/util/preview') }}" formtarget="previewcomment-reply-box{{ $comment->id }}" class="preview">Preview</button>
                            <button type="submit">Post</button>
                        </div>
                        @endif
                    </form>
                    <div class="preview-box"><iframe name="previewcomment-reply-box{{ $comment->id }}"></iframe></div>
                </div>

                <label class="comment-action source" for="collapse-source{{ $comment->id }}">source </label>
                <input class="collapse" id="collapse-source{{ $comment->id }}" type="checkbox">
                <div class="sourcebox">
                    <p class="text">
                        <textarea readonly>{{ $comment->markdown }}</textarea>
                    </p>
                </div>
                

                @if ($comment->users_user_id == Auth::id())
                    <label class="comment-action edit" for="collapse-edit{{ $comment->id }}">edit </label>
                    <input class="collapse" id="collapse-edit{{ $comment->id }}" type="checkbox">
                    <div class="editbox">
                        <form id="edit-form" action="/comments/{{ $comment->id }}/update" method="post">
                            <p class="text">
                                <textarea name="data" id="data" required maxlength="{{ Comment::MAX_MARKDOWN_LENGTH }}">{{ $comment->markdown }}</textarea>
                            </p>
                            <div class="submit">
                                <button type="submit" formmethod="post" formaction="{{ URL::to('/util/preview') }}" formtarget="previewcomment-reply-box{{ $comment->id }}" class="preview">Preview</button>
                                <button type="submit">Update</button>
                            </div>
                        </form>
                        <div class="preview-box"><iframe name="previewcomment-edit-box{{ $comment->id }}"></iframe></div>
                    </div>
                @endif
            @endif

            <label class="comment-action" for="collapse-permalink">permalink </label>
            <input class="collapse" id="collapse-permalink" type="checkbox">
            <div class="permalinkbox">
                <input type="text" size="20" value="{{ URL::to("/comments/" . $comment->id) }}" readonly>
            </div>
        @else
            <a href="{{ URL('/login') }}">Register</a> to post replies
        @endif
    </div>
</div>
