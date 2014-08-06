<div class="editbox">
    <form id="edit-form" action="/comments/{{ $comment_id }}/update" method="post">
        <p class="text">
            <textarea name="data" id="data" required maxlength="{{ Comment::MAX_MARKDOWN_LENGTH }}">{{ Comment::getSourceFromId($comment_id) }}</textarea>
        </p>
        <div class="submit">
            <button class="preview">Preview</button>
            <button type="submit">Update</button>
        </div>
    </form>
    <div class="preview-box"></div>
</div>
