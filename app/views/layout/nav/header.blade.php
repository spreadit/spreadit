<?php $section_titles = (isset($section)) ? Utility::splitByTitle($section->title) : []; ?>
<div id="header">
    <a href="#content" id="jumpToContent" tabindex="1">jump to content</a>

    <div id="sr-header-area">
        <div class="width-clip">
            <div class="dropdown srdrop" onclick="open_menu(this)">
                <span class="selected title">my subreddits</span>
            </div>

            <div class="sr-list">
                <ul class="flat-list sr-bar hover">
                    <li class="selected">
                        <a class="choice" href="/">frontpage</a>
                    </li>
                </ul>

                <span class="separator">&nbsp;|&nbsp;</span>

                <ul class="flat-list sr-bar hover">
                    @foreach ($sections as $section)
                        <li>
                            <span class="separator">-</span>
                            <a class="choice" href="/s/{{{ $section->title }}}">{{{ $section->title }}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div id="header-bottom-left">
            <a class="default-header" href="/" id="header-img" title="">spreadit.io</a>&nbsp;

            <ul class="tabmenu">
                <li>
                    <a class="choice" href="/s/{{{ $section->title }}}/new">new</a>
                </li>

                <li>
                    <a class="choice" href="/s/{{{ $section->title }}}/hot">hot</a>
                </li>

                <li>
                    <a class="choice" href="/s/{{{ $section->title }}}/top">top</a>
                </li>

                <li>
                    <a class="choice" href="/s/{{{ $section->title }}}/controversial">controversial</a>
                </li>
            </ul>
        </div>

        <div id="header-bottom-right">
            @if(Auth::check())
                <span class="user"><a href="/u/{{ Auth::user()->username }}">{{ Auth::user()->username }}</a>&nbsp;(<span class="userkarma" title="link karma">{{ Auth::user()->points }}</span>)</span>
                <span class="separator">|</span>
                <a class="{{ $notificationCount == 0 ? 'nonewmail' : 'newmail' }}" href="/notifications" id="mail" title="{{ $notificationCount == 0 ? 'no new mail' : 'new mail' }}">messages({{ $notificationCount }})</a>
                <span class="separator">|</span>

                <ul class="flat-list hover">
                    <li>
                        <a class="pref-lang choice" href="/preferences">preferences</a>
                    </li>
                </ul>

                <span class="separator">|</span>
            @else
                <form action="/logout" class="logout hover" method="get">
                    <a href="javascript:void(0)" onclick="$(this).parent().submit()">logout</a>
                </form>
            @endif
        </div>
    </div>
</div>
