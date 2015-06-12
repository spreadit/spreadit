<!doctype html>
<html>
    <head>
        @include('layout.etc.metahead')
    </head>
    <body data-spy="scroll" data-target=".bs-docs-sidebar" class="{{ (Auth::check() ? 'logged-in' : 'logged-out') }}">
        <div id="fullpage-container">
            <div class="navbar navbar-inverse">
                @include ('layout.nav.sections', ['sections' => $sections])
                @include('layout.nav.user', [
                    'username' => $username,
                    'highlight' => $highlight
                ])
            </div>
            @yield('content')

            @include('layout.nav.footer')
        </div>
        @include('layout.etc.commonscripts')
        @yield('script')
    </body>
</html>
