<!doctype html>                                                                                                                             
<html>
    <head>
        <title>{{ $username }}</title>
        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="https://spreadit.io/assets/js/html5shiv.js"></script>
        <![endif]-->
        <meta name=viewport content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" media="screen" href="/assets/css/homepage.css">
        <link rel="stylesheet" media="screen" href="/style">
        <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
         
        ga('create', 'UA-42844259-2', 'auto');
        ga('set', '&uid', {{ (Auth::check() ? Auth::user()->id : 0) }});
        ga('require', 'linkid', 'linkid.js');
        ga('require', 'displayfeatures');
        ga('send', 'pageview');
        </script>
    </head>
    <body>
        <div id="fullpage-container">
            {{ $html }}
        </div>
        <script>
            var elements = document.getElementsByClassName('lazy-loaded');
            for(var i=0; i<elements.length; i++) {
                elements[i].setAttribute('src', elements[i].getAttribute('data-original'));
            }
        </script>
    </body>
</html>
