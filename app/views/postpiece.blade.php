<div class="post-piece {{ UtilityController::nsfClasses($post) }}" tabindex="1">
    <div class="post-points">
        <div class="breaker points-actions">
            <a href="{{ URL::to("/vote/post/" . $post->id . "/up") }}" {{ UtilityController::bubbleText() }} class="vote {{ UtilityController::bubbleClasses() }} {{ UtilityController::upvoteClasses($post) }}" data-id="{{ $post->id }}" data-type="post" data-updown="up" rel="nofollow"><span class="voteiconup"></span></a>
            <a href="{{ URL::to("/vote/post/" . $post->id . "/down") }}" {{ UtilityController::bubbleText() }} class="vote {{ UtilityController::bubbleClasses() }} {{ UtilityController::downvoteClasses($post) }}" data-id="{{ $post->id }}" data-type="post" data-updown="down" rel="nofollow"><span class="voteicondown"></span></a>
            <span class="upvotes">{{ $post->upvotes }}</span>-<span class="downvotes">{{ $post->downvotes }}</span> <span class="total-points">{{ $post->upvotes - $post->downvotes }}</span>
        </div>
        <div class="breaker points-created-at">
            {{ Utility::prettyAgo($post->created_at) }}
        </div>
        <div class="breaker points-creator">
            <a class="username {{ UtilityController::anonymousClasses($post) }}" rel="nofollow" href="{{ URL::to("/u/{$post->username}") }}">{{ $post->username }}</a><span class="upoints">{{ $post->points }}</span><span class="uvotes">{{ $post->votes }}</span>
        </div>
    </div>
    <div class="post-thumbnail">
        @if (!empty($post->thumbnail))
            @if (!empty($post->url))
                <a rel="nofollow" href="{{ URL::to($post->url) }}">
                    <img alt="{{{ $post->title }}}" src="/assets/thumbs/{{ $post->thumbnail }}.jpg">
                </a>
            @else
                <img alt="{{{ $post->title }}}" src="/assets/thumbs/{{ $post->thumbnail }}.jpg">
            @endif
        @endif
    </div>
    <div class="post-data">
        <div class="breaker data-title-and-section">
            <a rel="nofollow" href="{{ UtilityController::postUrl($post) }}">{{ $post->title }}</a> | <a class="post-section" href="{{ UtilityController::sectionUrl($post) }}">{{ $post->section_title }}</a>
        </div>
        <div class="breaker data-url-and-comments">
            <span class="post-url">{{ UtilityController::commentsPrettyUrl($post) }}</span> :: <a href="{{ UtilityController::commentsUrl($post) }}">view comments({{ $post->comment_count }})</a>
        </div>
        <div class="breaker data-summary">
            <span class="summary">{{{ Utility::ellipsis(Utility::prettySubstr($post->markdown, 130)) }}}</span>
        </div>
    </div>
</div>
