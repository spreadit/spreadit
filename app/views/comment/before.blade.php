<!doctype html>
<html>
    <head>
        <title>comment reply box</title>
        <meta name=viewport content="width=device-width, initial-scale=1">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="{{ Bust::url("/assets/prod/build.min.css") }}">
        {{ $customCss }} 
    </head>
    <body class="comment-reply-box-page">
        <a href="/comments/cur/{{ $comment->post_id }}/{{ $comment->parent_id }}">click here to add comment anonymously</a>
    </body>
</html>
