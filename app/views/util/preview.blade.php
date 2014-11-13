<!doctype html>
<html>
    <head>
        <title>preview</title>
        <meta name=viewport content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="{{ Bust::url("/assets/prod/build.min.css") }}">
        {{ UtilityController::customCss() }} 
    </head>
    <body>
        {{ $data }}
        @include('layout.etc.commonscripts')
    </body>
</html>
