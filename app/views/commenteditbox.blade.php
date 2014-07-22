<div class="editbox">
    <form id="edit-form" action="/comments/{{ $comment_id }}/update" method="post">
        <p class="text">
            <textarea name="data" required maxlength="{{ CommentController::MAX_MARKDOWN_LENGTH }}">{{ CommentController::getSourceFromId($comment_id) }}</textarea>
        </p>
        <div class="submit">
            <button type="submit">Update</button>
        </div>
    </form>
</div>
