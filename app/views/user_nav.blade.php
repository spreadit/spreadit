<div id="header-nav">
    <div class="align-bottom">
        <h2>{{ $username }}</h2>
        <ul class="nav nav-pills">
            <li class="{{ $highlight == 'posts' ? 'active' : '' }}"><a role="button" href="/u/{{ $username }}/posts">posts</a></li>
            <li class="{{ $highlight == 'comments' ? 'active' : '' }}"><a role="button" href="/u/{{ $username }}/comments">comments</a></li>
            <li class="{{ $highlight == 'pvotes' ? 'active' : '' }}"><a role="button" href="/u/{{ $username }}/votes/posts">post votes</a></li>
            <li class="{{ $highlight == 'cvotes' ? 'active' : '' }}"><a role="button" href="/u/{{ $username }}/votes/comments">comment votes</a></li>
        </ul>
        @include('user_actions_nav')
    </div>
</div>
