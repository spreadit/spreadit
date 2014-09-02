<div class="navbar-inner dark-navbar" id="sections-navbar">

    <div class="container">
        <ul class="nav">
            <li>
                <a href="/">spreadit.io</a>
            </li>
            @foreach ($sections as $section)
            <li class="{{ Request::segment(2) == $section->title ? 'active' : '' }}">
                <a href="/s/{{ $section->title }}">{{ $section->title }}</a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
