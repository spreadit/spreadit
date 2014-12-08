<ul class="nav nav-pills" id="user-navbar">
    @if(Auth::check())
    <li>
        <a role="button" href="/u/{{ Auth::user()->username }}">{{ Auth::user()->username }}(<span class="my-points">{{ Auth::user()->points }}</span>,<span class="my-votes">{{ Auth::user()->votes }}</span></span>)</a>
    </li>

    <li class="{{ $notificationClasses }}">
        <a role="button" href="/notifications">msgs({{ $notificationCount }})</a>
    </li>
    <li class="{{ Request::segment(1) == 'prefs' ? 'active' : '' }}">
        <a role="button" href="/preferences">prefs</a>
    </li>
    <li class="{{ Request::segment(1) == 'logout' ? 'active' : '' }}">
        <a role="button" href="/logout">logout</a>
    </li>
    @else
    <li class="{{ Request::segment(1) == 'login' ? 'active' : '' }}">
        <a role="button" href="/login">login</a>
    </li>
    @endif
</ul>
