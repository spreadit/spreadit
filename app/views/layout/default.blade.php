<!doctype html>
<html>
    <head>
        @include('metahead')
    </head>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        <div id="fullpage-container">
            <div class="navbar navbar-inverse">
                @include ('sections_nav', ['sections' => $sections])
                @if (isset($section_title))
                @include('sorting_nav', [
                    'section_title' => $section_title, 
                    'sort_highlight' => $sort_highlight, 
                    'sort_timeframe_highlight' => $sort_timeframe_highlight
                ])
                @endif
            </div>

            @yield('content')

            @include('footer_nav')
        </div>
        @include('commonscripts')
        @yield('script')        
    </body>
</html>
