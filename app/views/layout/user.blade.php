<!doctype html>
<html>
    <head>
        @include('layout.etc.metahead')
    </head>
    <body class="{{ (Auth::check() ? 'logged-in' : 'logged-out') }}">
        @include ('layout.nav.header', [
        'sections' => $sections,
        'section' => $section,
        'sort_highlight' => $sort_highlight
        ])
        @include('layout.nav.sidebar')

        <a id="content" name="content"></a>
        @yield('content')

        @include('layout.etc.commonscripts')
        @yield('script')
    </body>
</html>
