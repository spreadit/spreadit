<?php
$uuid = rand(0, 999999999);
?>
<label class="comment-action reply" for="collapse-reply{{ $uuid }}">reply </label>
<noscript>
    <input class="collapse" id="collapse-reply{{ $uuid }}" type="checkbox">
    <div class="replybox">
        <iframe name="reply-box{{ $uuid }}" src="{{ URL::to(sprintf("/comments/pre/%d/%d", $post_id, $parent_id)) }}"></iframe>
    </div>
</noscript>