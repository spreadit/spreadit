<!doctype html>
<html>
    <head>
        @include('layout.etc.metahead')
    </head>
    <body data-spy="scroll" data-target=".bs-docs-sidebar" class="{{ (Auth::check() ? 'logged-in' : 'logged-out') }}">
        <div id="fullpage-container">
            <div class="navbar navbar-inverse">
                @include ('layout.nav.sections', ['sections' => $sections])
                <div id="header-nav">
                    <div class="align-bottom">
                        @include('layout.nav.user_actions')
                    </div>
                </div>
            </div>

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
        </div>
        @include('layout.etc.commonscripts')
        @yield('script')        
    </body>
</html>
