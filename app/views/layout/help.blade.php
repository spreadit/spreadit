<!doctype html>
<html>
    <head>
        @include('metahead')
    </head>
    <body data-spy="scroll" data-target=".bs-docs-sidebar" class="{{ (Auth::check() ? 'logged-in' : 'logged-out') }}">
        <div id="fullpage-container">
            <div class="navbar navbar-inverse">
                @include ('sections_nav', ['sections' => $sections])
                <div id="header-nav">
                    <div class="align-bottom">
                        @include('user_actions_nav')
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
                                <li><a href="/help/help">How to Help</a></li>
                            </ul>
                        </nav>

                        @yield('content')
                    </div>
                </div>
            </div>

            @include('footer_nav')
        </div>
        @include('commonscripts')
        @yield('script')        
    </body>
</html>
