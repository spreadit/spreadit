<div id="header-nav">
    <div class="align-bottom">
        <h2>{{ $section_title }}</h2>
        <ul class="nav nav-pills">
            <li {{ $sort_highlight == 'new' ? 'class="active"' : ''}}><a role="button" href="/s/{{ $section_title }}/new">New</a></li>
            <li {{ $sort_highlight == 'hot' ? 'class="active"' : ''}}><a role="button" href="/s/{{ $section_title }}/hot">Hot</a></li>
            <li class="{{ $sort_highlight == 'top' ? 'active' : ''}} dropdown">
                <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#">Top <b class="caret"></b></a>
                <ul id="menu2" class="dropdown-menu" role="menu" aria-labelledby="drop5">
                    <li {{ $sort_highlight == 'top' && $sort_timeframe_highlight == 'day' ? 'class="active"' : '' }} role="presentation"><a role="menuitem" tabindex="-1" href="/s/{{ $section_title }}/top/day">Day</a></li>
                    <li {{ $sort_highlight == 'top' && $sort_timeframe_highlight == 'week' ? 'class="active"' : '' }} role="presentation"><a role="menuitem" tabindex="-1" href="/s/{{ $section_title }}/top/week">Week</a></li>
                    <li {{ $sort_highlight == 'top' && $sort_timeframe_highlight == 'month' ? 'class="active"' : '' }} role="presentation"><a role="menuitem" tabindex="-1" href="/s/{{ $section_title }}/top/month">Month</a></li>
                    <li {{ $sort_highlight == 'top' && $sort_timeframe_highlight == 'year' ? 'class="active"' : '' }} role="presentation"><a role="menuitem" tabindex="-1" href="/s/{{ $section_title }}/top/year">Year</a></li>
                    <li {{ $sort_highlight == 'top' && $sort_timeframe_highlight == 'forever' ? 'class="active"' : '' }} role="presentation"><a role="menuitem" tabindex="-1" href="/s/{{ $section_title }}/top/forever">Forever</a></li>
                </ul>
            </li>
            <li class="{{ $sort_highlight == 'controversial' ? 'active' : '' }} dropdown">
                <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#">Controversial <b class="caret"></b></a>
                <ul id="menu2" class="dropdown-menu" role="menu" aria-labelledby="drop5">
                    <li {{ $sort_highlight == 'controversial' && $sort_timeframe_highlight == 'day' ? 'class="active"' : '' }} role="presentation"><a role="menuitem" tabindex="-1" href="/s/{{ $section_title }}/controversial/day">Day</a></li>
                    <li {{ $sort_highlight == 'controversial' && $sort_timeframe_highlight == 'week' ? 'class="active"' : '' }} role="presentation"><a role="menuitem" tabindex="-1" href="/s/{{ $section_title }}/controversial/week">Week</a></li>
                    <li {{ $sort_highlight == 'controversial' && $sort_timeframe_highlight == 'month' ? 'class="active"' : '' }} role="presentation"><a role="menuitem" tabindex="-1" href="/s/{{ $section_title }}/controversial/month">Month</a></li>
                    <li {{ $sort_highlight == 'controversial' && $sort_timeframe_highlight == 'year' ? 'class="active"' : '' }} role="presentation"><a role="menuitem" tabindex="-1" href="/s/{{ $section_title }}/controversial/year">Year</a></li>
                    <li {{ $sort_highlight == 'controversial' && $sort_timeframe_highlight == 'forever' ? 'class="active"' : '' }} role="presentation"><a role="menuitem" tabindex="-1" href="/s/{{ $section_title }}/controversial/forever">Forever</a></li>
                </ul>
            </li>
            @if(Auth::check() && isset($section_title))
            <li {{ $sort_highlight == 'add' ? 'class="active"' : '' }}><a role="button" href="/s/{{ $section_title }}/add">add post</a></li>
            @endif
        </ul>
        @include ('user_actions_nav')
        </ul>
    </div>
</div>
