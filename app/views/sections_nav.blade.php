<div class="navbar-inner dark-navbar" id="sections-navbar">
    <div class="container">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"></a>
        <div class="nav-collapse collapse">
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
</div>
