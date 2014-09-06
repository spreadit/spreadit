<div class="post-piece" tabindex="1">
    <div class="post-points">
        <div class="breaker points-actions">
            <span data-hint="{{ (!Auth::check() || Auth::user()->anonymous) ? 'need to register to vote' : 'costs one point to vote' }}" class="vote hint--right hint--bounce hint--warning  {{ $post->selected == Vote::UP ? 'selected' : '' }} {{ $post->selected == Vote::DOWN ? 'disable-click' : ''}}" data-id="{{ $post->id }}" data-type="post" data-updown="up">&#x25B2;</span>
            <span data-hint="{{ (!Auth::check() || Auth::user()->anonymous) ? 'need to register to vote' : 'costs one point to vote' }}" class="vote hint--right hint--bounce hint--warning  {{ $post->selected == Vote::DOWN ? 'selected' : '' }} {{ $post->selected == Vote::UP ? 'disable-click' : '' }}" data-id="{{ $post->id }}" data-type="post" data-updown="down">&#x25BC;</span>
            <span class="upvotes">{{ $post->upvotes }}</span>-<span class="downvotes">{{ $post->downvotes }}</span> <span class="total-points">{{ $post->upvotes - $post->downvotes }}</span>
        </div>
        <div class="breaker points-created-at">
            {{ Utility::prettyAgo($post->created_at) }}
        </div>
        <div class="breaker points-creator">
            <a class="username" rel="nofollow" href="{{ URL::to("/u/{$post->username}") }}">{{ $post->username }}</a>(<span class="upoints">{{ $post->points }}</span>,<span class="uvotes">{{ $post->votes }}</span>)
        </div>
    </div>
    <div class="post-data">
        <div class="breaker data-title-and-section">
            <a rel="nofollow" href="{{ $post->type == Post::SELF_POST_TYPE ? URL::to("/s/{$post->section_title}/posts/{$post->id}/" . Utility::prettyUrl($post->title)) : URL::to($post->url) }}">{{ $post->title }}</a> | <a class="post-section" href="{{ URL::to('/s/' . $post->section_title) }}">{{ $post->section_title }}</a>
        </div>
        <div class="breaker data-url-and-comments">
            <span class="post-url">{{ parse_url($post->type == Post::SELF_POST_TYPE ? URL::to('/') : $post->url, PHP_URL_HOST) }}</span> :: <a href="{{ URL::to("/s/{$post->section_title}/posts/{$post->id}/" . Utility::prettyUrl($post->title)) }}">view comments({{ $post->comment_count }})</a>
        </div>
        <div class="breaker data-summary">
            <span class="summary">{{{ Utility::prettySubstr($post->markdown, 130) }}}..</span>
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
</div>
