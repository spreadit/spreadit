<div class="editbox">
    <form id="edit-form" action="/update_post/{{ $post_id }}" method="post">
        <p class="text">
            <textarea name="data" required maxlength="{{ PostController::MAX_MARKDOWN_LENGTH }}">{{ PostController::getSourceFromId($post_id) }}</textarea>
        </p>
        <div class="submit">
            <button type="submit">Update</button>
        </div>
    </form>
</div>
