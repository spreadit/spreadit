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
        <form action="{{ $comment->form_action }}" id="comment-form" method="post">
            <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
            <input type="hidden" name="parent_id" value="{{ $comment->parent_id }}">
            <p class="text">
                <textarea name="data" id="data" value="{{ Input::old('data') }}" placeholder="{{ UtilityController::commentsRemainingHtml() }}" maxlength="{{ Comment::MAX_MARKDOWN_LENGTH }}" required></textarea>

            </p>
            @if (Comment::canPost())
            <div class="submit">
                @if (!Auth::check())
                    <p class="captcha">
                        {{  HTML::image(Captcha::img(), 'Captcha image') }}
                        <input type="text" name="captcha" placeholder="Captcha text" size="8" required>
                    </p>
                @endif
                <button type="submit" class="preview" 
                    formmethod="post"
                    formaction="{{ URL::to('/util/preview') }}"
                    formtarget="previewcomment-reply-box{{ $comment->parent_id }}">
                    Preview
                </button>
                <button type="submit">Post</button>
            </div>
            @endif
        </form>
        <div class="preview-box"><iframe name="previewcomment-reply-box{{ $comment->parent_id }}"></iframe></div>
    </body>
</html>
