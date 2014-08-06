<div class="editbox">
    <form id="edit-form" action="/update_post/{{ $post_id }}" method="post">
        <p class="text">
            <textarea name="data" id="data" required maxlength="{{ Post::MAX_MARKDOWN_LENGTH }}">{{ Post::getSourceFromId($post_id) }}</textarea>
        </p>
        <div class="submit">
            <button class="preview">Preview</button>
            <button type="submit">Update</button>
        </div>
    </form>
    <div class="preview-box"></div>
</div>
