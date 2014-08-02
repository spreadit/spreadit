<ul class="nav nav-pills" id="user-navbar">
    @if(Auth::check())
    <li>
        <a role="button" href="/u/{{ Auth::user()->username }}">{{ Auth::user()->username }}(<span class="my-points">{{ Auth::user()->points }}</span>)</a>
    </li>
    </li>
    <li class="{{ NotificationController::hasUnread() ? 'unread-notifications' : '' }} {{ Request::segment(1) == 'notifications' ? 'active' : '' }}">
        <a role="button" href="/notifications">notifications</a>
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
