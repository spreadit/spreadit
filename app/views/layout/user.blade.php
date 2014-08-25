<!doctype html>
<html>
    <head>
        @include('metahead')
    </head>
    <body data-spy="scroll" data-target=".bs-docs-sidebar" class="{{ (Auth::check() ? 'logged-in' : 'logged-out') }}">
        <div id="fullpage-container">
            <div class="navbar navbar-inverse">
                @include ('sections_nav', ['sections' => $sections])
                @include('user_nav', [
                    'username' => $username,
                    'highlight' => $highlight
                ])
            </div>
            @yield('content')

            @include('footer_nav')
        </div>
        @include('commonscripts')
        @yield('script')        
    </body>
</html>
