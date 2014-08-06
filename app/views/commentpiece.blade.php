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
                <a class="comment-action reply" data-type="comment" data-id="{{ $comment->id }}">reply </a>
                <a class="comment-action source" data-type="comment" data-id="{{ $comment->id }}">source </a>
                @if ($comment->users_user_id == Auth::id())
                    <a class="comment-action edit" data-type="comment" data-id="{{ $comment->id }}">edit </a>
                @endif
            @endif
            <a class="comment-action" href="{{ URL::to("/comments/".$comment->id) }}">permalink </a>
        @else
            <a href="{{ URL('/login') }}">Register</a> to post replies
        @endif
    </div>
</div>
