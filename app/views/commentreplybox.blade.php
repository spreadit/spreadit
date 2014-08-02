<div class="replybox">
   <form id="comment-form" method="post" class="flat-form flatpop-left">
       <input type="hidden" name="parent_id" value="{{ $parent_id }}">
       <p class="text">
           <textarea name="data" placeholder="You have {{ (CommentController::MAX_COMMENTS_PER_DAY - CommentController::getCommentsInTimeoutRange()) }} of {{ CommentController::MAX_COMMENTS_PER_DAY }} comments remaining ( per {{ UtilController::prettyAgo(time() - CommentController::MAX_COMMENTS_TIMEOUT_SECONDS) }})" maxlength="{{ CommentController::MAX_MARKDOWN_LENGTH }}" required></textarea>
       </p>
        @if ((CommentController::MAX_COMMENTS_PER_DAY - CommentController::getCommentsInTimeoutRange()) > 0)
       <div class="submit">
           <button type="submit">Post</button>
       </div>
        @endif
   </form>
</div>
