<div class="replybox">
    <form id="comment-form" method="post" class="flat-form flatpop-left">
        <input type="hidden" name="parent_id" value="{{ $parent_id }}">
        <p class="text">
            <textarea name="data" id="data" placeholder="You have {{ (Comment::MAX_COMMENTS_PER_DAY - Comment::getCommentsInTimeoutRange()) }} of {{ Comment::MAX_COMMENTS_PER_DAY }} comments remaining ( per {{ Utility::prettyAgo(time() - Comment::MAX_COMMENTS_TIMEOUT_SECONDS) }})" maxlength="{{ Comment::MAX_MARKDOWN_LENGTH }}" required></textarea>
        </p>
        @if ((Comment::MAX_COMMENTS_PER_DAY - Comment::getCommentsInTimeoutRange()) > 0)
        <div class="submit">
            <button class="preview">Preview</button>
            <button type="submit">Post</button>
        </div>
        @endif
    </form>
    <div class="preview-box"></div>
</div>
