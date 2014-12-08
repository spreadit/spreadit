@if (Utility::remainingComments() > 0)
<form action="{{ $comment->form_action }}" id="comment-form" method="post">
    <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
    <input type="hidden" name="parent_id" value="{{ $comment->parent_id }}">
    <p class="text">
        <textarea name="data" id="data" value="{{ Input::old('data') }}" placeholder="{{ $commentsRemaining }}" maxlength="{{ Constant::COMMENT_MAX_MARKDOWN_LENGTH }}" required></textarea>
    </p>
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
</form>
<div class="preview-box">
    <noscript>
        <iframe name="previewcomment-reply-box{{ $comment->parent_id }}"></iframe>
    </noscript>
</div>
@else
<p class="text no-comments-remaining">
    {{ $commentsRemaining }}
</p>
@endif