<!--<div class="post-piece {{ $nsfwClasses }} {{ $selfpost }}" tabindex="1" data-post-id="{{ $post->id }}">
    <div class="post-points">
        <div class="breaker points-actions">
            <a href="{{ URL::to("/vote/post/" . $post->id . "/up") }}" {{ $bubbleText }} class="vote {{ $bubbleClasses }} {{ $upvoteClasses }}" data-id="{{ $post->id }}" data-type="post" data-updown="up" rel="nofollow"><span class="voteiconup"></span></a>
            <a href="{{ URL::to("/vote/post/" . $post->id . "/down") }}" {{ $bubbleText }} class="vote {{ $bubbleClasses }} {{ $downvoteClasses }}" data-id="{{ $post->id }}" data-type="post" data-updown="down" rel="nofollow"><span class="voteicondown"></span></a>
            <span class="upvotes">{{ $post->upvotes }}</span><span class="downvotes">{{ $post->downvotes }}</span><span class="total-points">{{ $post->upvotes - $post->downvotes }}</span>
        </div>
        <div class="breaker points-created-at">
            {{ Utility::prettyAgo($post->created_at) }}
        </div>
        <div class="breaker points-creator">
            <a class="username {{ $anonymousClasses }}" rel="nofollow" href="{{ URL::to("/u/{$post->username}") }}">{{ $post->username }}</a><span class="upoints">{{ $post->points }}</span><span class="uvotes">{{ $post->votes }}</span>
        </div>
    </div>

    @if ($selfpost)
    <a rel="nofollow" href="{{ URL::to($post->url) }}">
    @else
    <a href="{{ URL::to($post->url) }}">
    @endif
        <div class="post-thumbnail">
            <span class="thumb-small">
                <div class="thumb-img" title="{{{ $post->title }}}" {{ (empty($post->thumbnail)) ?: 'style="background-image:url(/assets/thumbs/small/'.$post->thumbnail.'.jpg)"' }}></div>
            </span>
            <span class="thumb-large">
                <div class="thumb-img" title="{{{ $post->title }}}" {{ (empty($post->thumbnail)) ?: 'style="background-image:url(/assets/thumbs/large/'.$post->thumbnail.'.jpg)"' }}></div>
            </span>
        </div>
    </a>
    <div class="post-data">
        <div class="breaker data-title-and-section">
            <a class="post-title" rel="nofollow" href="{{ $postUrl }}">{{ $post->title }}</a>
            <span class="post-title-section-separator"></span> 
            <a class="post-section" href="{{ $sectionUrl }}">{{ $post->section_title }}</a>
        </div>
        <div class="breaker data-url-and-comments">
            <span class="post-url">{{ $commentsPrettyUrl }}</span>
            <span class="post-url-comments-link-separator"></span>
            <a class="post-comments-link" href="{{ $commentsUrl }}"><span class="post-comments-count">{{ $post->comment_count }}</span></a>
        </div>
        <div class="breaker data-summary">
            @if ($selfpost)
                <a href="{{ $commentsUrl }}">
            @endif
            <span class="summary">{{{ Utility::ellipsis(Utility::prettySubstr($post->markdown, 130)) }}}</span>
            @if ($selfpost)
                </a>
            @endif
        </div>
    </div>
</div>-->


<div class="linkflair linkflair-request even gilded link self">
    <p class="parent"></p><span class="rank">{{ $rank }}</span>

    <div class="midcol likes">
        <div class="arrow upmod" onclick="$(this).vote(r.config.vote_hash, null, event)" tabindex="0"></div>

        <div class="score dislikes">{{ $post->downvotes }}</div>
        <div class="score unvoted">{{ $post->upvotes - $post->downvotes }}</div>
        <div class="score likes">{{ $post->upvotes }}</div>

        <div class="arrow down login-required" onclick="$(this).vote(r.config.vote_hash, null, event)" tabindex="0"></div>
    </div>

    <a class="thumbnail self may-blank loggedin" href="{{{ $postUrl }}}"></a>

    <div class="entry likes">
        <p class="title">
            <a class="title may-blank loggedin" href= "{{{ $selfpost ? $commentsUrl : $postUrl }}}" tabindex="1">{{ $post->title }}</a>
            &#32;
            @if($selfpost)
                <span class="domain">(self.{{{ $post->section_title }}})</span>
            @else
                <span class="domain">({{{ $postUrl }}})</span>
            @endif
        </p>

        <div class="expando-button collapsed selftext"></div>

        <p class="tagline">submitted&#32;
            <time class="live-timestamp" datetime="2015-07-03T17:36:00+00:00"title="Fri Jul 3 17:36:00 2015 UTC">5 hours ago</time>
            &#32;
            <time class="edited-timestamp" datetime="2015-07-03T21:44:14+00:00" title="last edited 1 hour ago">*</time>
            &#32;by&#32;
            <a class="author may-blank id-t2_hnlos" href="{{ URL::to(sprintf('/u/%s', $post->username)) }}">{{{ $post->username }}}</a>
            <span class="userattrs"></span>&#32;to&#32;
            <a class="subreddit hover may-blank"href="{{ $sectionUrl }}">/s/{{ $post->section_title }}</a>
        </p>

        <ul class="flat-list buttons">
            <li class="first">
                <a class="comments may-blank" href="{{ $commentsUrl }}">{{ $post->comment_count }} comments</a>
            </li>
        </ul>

        <div class="expando" style='display: none'>
            <span class="error">loading...</span>
        </div>
    </div>

    <div class="child"></div>

    <div class="clearleft"></div>
</div>

<div class="clearleft"></div>