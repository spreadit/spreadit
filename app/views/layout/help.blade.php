<!doctype html>
<html>
    <head>
        @include('layout.etc.metahead')
    </head>
    <body class="{{ (Auth::check() ? 'logged-in' : 'logged-out') }}">
        @include ('layout.nav.header', [
            'sections' => $sections,
        ])
        @include('layout.nav.sidebar')

        <div class="help-page">
            <div class="row-fluid">
                <div class="span12">
                    <nav class="help-nav">
                        <ul>
                            <li><a href="/help">Table of Contents</a></li>
                            <li><a href="/help/feeds">Feeds</a></li>
                            <li><a href="/help/posting">Posting</a></li>
                            <li><a href="/help/formatting">Formatting</a></li>
                            <li><a href="/help/points">Points</a></li>
                            <li><a href="/help/moderation">Moderation</a></li>
                            <li><a href="/help/anonymity">Anonymity</a></li>
                            <li><a href="/help/help">How to Help</a></li>
                        </ul>
                    </nav>

                    @yield('content')
                </div>
            </div>
        </div>

        @include('layout.nav.footer')
        @include('layout.etc.commonscripts')
        @yield('script')
    </body>
</html>
