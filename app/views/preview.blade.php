<!doctype html>
<html>
    <head>
        <title>preview</title>
        <meta name=viewport content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="/assets/prod/build.min.css">
        {{ UtilityController::colorschemeHtml() }} 
    </head>
    <body>
        {{ $data }}
        @include('commonscripts')
    </body>
</html>
