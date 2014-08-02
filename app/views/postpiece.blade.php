<div class="post-piece" tabindex="1">
    <div class="post-points">
        <span class="vote {{ $post->selected == VoteController::UP ? 'selected' : '' }} {{ $post->selected == VoteController::DOWN ? 'disable-click' : ''}}" data-id="{{ $post->id }}" data-type="post" data-updown="up">&#x25B2;</span>
        <span class="vote {{ $post->selected == VoteController::DOWN ? 'selected' : '' }} {{ $post->selected == VoteController::UP ? 'disable-click' : '' }}" data-id="{{ $post->id }}" data-type="post" data-updown="down">&#x25BC;</span>
        <span class="upvotes">{{ $post->upvotes }}</span>-<span class="downvotes">{{ $post->downvotes }}</span> <span class="total-points">{{ $post->upvotes - $post->downvotes }}</span>
        <br>
        {{ UtilController::prettyAgo($post->created_at) }}
        <br>
        <a rel="nofollow" href="{{ URL::to("/u/{$post->username}") }}">{{ $post->username }}</a>({{ $post->points }})
    </div>
    <div class="post-data">
        <a rel="nofollow" href="{{ $post->type == PostController::SELF_POST_TYPE ? URL::to("/s/{$post->section_title}/posts/{$post->id}/" . UtilController::prettyUrl($post->title)) : $post->url }}">{{ $post->title }}</a> | {{ $post->section_title }}
        <br>
        <span class="post-url">{{ parse_url($post->type == PostController::SELF_POST_TYPE ? URL::to('/') : $post->url, PHP_URL_HOST) }}</span> :: <a href="{{ URL::to("/s/{$post->section_title}/posts/{$post->id}/" . UtilController::prettyUrl($post->title)) }}">view comments({{ $post->comment_count }})</a>
        <br>
        <span class="summary">{{{ UtilController::prettySubstr($post->markdown, 130) }}}..</span>
    </div>
</div>
