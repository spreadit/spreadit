<div class="row-fluid postpiece">
    <div class="span2">
        <span class="vote {{ $post->selected == VoteController::UP ? 'selected' : '' }} {{ $post->selected == VoteController::DOWN ? 'disable-click' : ''}}" data-id="{{ $post->id }}" data-type="post" data-updown="up">&#x25B2;</span>
        <span class="vote {{ $post->selected == VoteController::DOWN ? 'selected' : '' }} {{ $post->selected == VoteController::UP ? 'disable-click' : '' }}" data-id="{{ $post->id }}" data-type="post" data-updown="down">&#x25BC;</span>
        <span class="upvotes">{{ $post->upvotes }}</span>-<span class="downvotes">{{ $post->downvotes }}</span> <span class="total-points">{{ $post->upvotes - $post->downvotes }}</span>
        <br>
        {{ PostController::prettyAgo($post->created_at) }}
        <br>
        <a href="{{ URL::to("/u/{$post->username}") }}">{{ $post->username }}</a>({{ $post->points }})
    </div>
    <div class="span10">
        <a href="{{ $post->type == PostController::SELF_POST_TYPE ? URL::to("/s/{$post->section_title}/posts/{$post->id}/" . PostController::prettyUrl($post->title)) : $post->url }}">{{ $post->title }}</a> | {{ $post->section_title }}
        <br>
        {{ $post->type == PostController::SELF_POST_TYPE ? URL::to("/s/" . $post->section_title . "/posts/" . $post->id . "/" . PostController::prettyUrl($post->title)) : $post->url }} :: <a href="{{ URL::to("/s/{$post->section_title}/posts/{$post->id}/" . PostController::prettyUrl($post->title)) }}">view comments({{ $post->comment_count }})</a>
    </div>
</div>
