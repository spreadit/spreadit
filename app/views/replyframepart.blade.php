<?php
$uuid = rand(0, 999999999);
?>
<form class="reply-button-form" action="/" method="post">
    <input type="hidden" name="post_id" value="{{ $post_id }}">
    <input type="hidden" name="parent_id" value="{{ $parent_id }}">
    <button type="submit" class="preview"
        formmethod="get"
        formaction="{{ URL::to('/comments/cur') }}"
        formtarget="reply-box{{ $uuid }}">
        <label class="comment-action reply" for="collapse-reply{{ $uuid }}">reply </label>
    </button>
    <button type="submit" style="display:none">hidden</button>
</form>
<input class="collapse" id="collapse-reply{{ $uuid }}" type="checkbox">
<div class="replybox">
    <iframe name="reply-box{{ $uuid }}"></iframe>   
</div>
