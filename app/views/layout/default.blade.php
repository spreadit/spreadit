<!doctype html>
<html lang="en">
    <head>
        @include('layout.etc.metahead')
    </head>
    <body class="{{ (Auth::check() ? 'loggedin' : 'loggedout') }} @yield('bodyclasses')">
        @include ('layout.nav.header', [
            'sections' => $sections,
            'section' => $section,
        ])
        @include('layout.nav.sidebar')

        <a id="content" name="content"></a>
        @yield('content')

        @include('layout.nav.footer')
        @include('layout.etc.commonscripts')
        @yield('script')
    </body>
</html>
