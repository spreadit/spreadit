<!doctype html>
<html>
    <head>
        <title>comment reply box</title>
        <meta name=viewport content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="{{ Bust::url("/assets/prod/build.min.css") }}">
        {{ UtilityController::customCss() }} 
    </head>
    <body class="comment-reply-box-page">
        @if ($errors->any())
            <div class="alert alert-warning fade in">
                <div class="close" data-dismiss="alert" aria-hidden="true">&times;</div>
                <h4 class="text-center">{{ $errors->first() }}</h4>
            </div>
        @endif
        @include ('comment.replyboxform', ['comment' => $comment])
    </body>
</html>
