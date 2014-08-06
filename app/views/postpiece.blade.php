<div class="post-piece" tabindex="1">
    <div class="post-points">
        <span class="vote {{ $post->selected == Vote::UP ? 'selected' : '' }} {{ $post->selected == Vote::DOWN ? 'disable-click' : ''}}" data-id="{{ $post->id }}" data-type="post" data-updown="up">&#x25B2;</span>
        <span class="vote {{ $post->selected == Vote::DOWN ? 'selected' : '' }} {{ $post->selected == Vote::UP ? 'disable-click' : '' }}" data-id="{{ $post->id }}" data-type="post" data-updown="down">&#x25BC;</span>
        <span class="upvotes">{{ $post->upvotes }}</span>-<span class="downvotes">{{ $post->downvotes }}</span> <span class="total-points">{{ $post->upvotes - $post->downvotes }}</span>
        <br>
        {{ Utility::prettyAgo($post->created_at) }}
        <br>
        <a rel="nofollow" href="{{ URL::to("/u/{$post->username}") }}">{{ $post->username }}</a>({{ $post->points }})
    </div>
    <div class="post-data">
        <a rel="nofollow" href="{{ $post->type == Post::SELF_POST_TYPE ? URL::to("/s/{$post->section_title}/posts/{$post->id}/" . Utility::prettyUrl($post->title)) : $post->url }}">{{ $post->title }}</a> | {{ $post->section_title }}
        <br>
        <span class="post-url">{{ parse_url($post->type == Post::SELF_POST_TYPE ? URL::to('/') : $post->url, PHP_URL_HOST) }}</span> :: <a href="{{ URL::to("/s/{$post->section_title}/posts/{$post->id}/" . Utility::prettyUrl($post->title)) }}">view comments({{ $post->comment_count }})</a>
        <br>
        <span class="summary">{{{ Utility::prettySubstr($post->markdown, 130) }}}..</span>
    </div>
    <div class="post-thumbnail">
        @if (!empty($post->thumbnail))
        <img alt="{{{ $post->title }}}" src="/assets/thumbs/{{ $post->thumbnail }}.jpg">
        @endif
    </div>
</div>
