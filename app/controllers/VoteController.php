<?php
class VoteController extends BaseController
{
    
    protected $vote;
    protected $section;

    public function __construct(Vote $vote, Section $section)
    {
        $this->vote = $vote;
        $this->section = $section;
    }


    /**
     * render posts vote table page
     *
     * @param int  $post_id
     * @return Illuminate\View\View
     */
    public function postView($post_id)
    {
        return View::make('page.vote.table', [
            'votes'    => $this->vote->getPostVotes($post_id),
            'sections' => $this->section->get()
        ]);
    }


    /**
     * render posts vote table page
     *
     * @param int  $post_id
     * @return Illuminate\Http\JsonResponse
     */
    public function postJson($post_id)
    {
        return Response::json(iterator_to_array($this->vote->getPostVotes($post_id)));
    }


    /**
     * upvote post
     *
     * @param int  $post_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function postUp($post_id)
    {
        $this->vote->action(Constant::POST_TYPE, $post_id, Constant::VOTE_UP);
        return Redirect::back(); 
    }


    /**
     * downvote post
     *
     * @param int  $post_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function postDown($post_id)
    {
        $this->vote->action(Constant::POST_TYPE, $post_id, Constant::VOTE_DOWN);
        return Redirect::back();
    }


    /**
     * see `postUp`
     *
     * @param int  $post_id
     * @return Illuminate\Http\JsonResponse
     */
    public function postUpJson($post_id)
    {
        return Response::json($this->vote->action(Constant::POST_TYPE, $post_id, Constant::VOTE_UP));
    }


    /**
     * see `postDown`
     *
     * @param int  $post_id
     * @return Illuminate\Http\JsonResponse
     */
    public function postDownJson($post_id)
    {
        return Response::json($this->vote->action(Constant::POST_TYPE, $post_id, Constant::VOTE_DOWN));
    }

    public function commentView($comment_id)
    {
        return View::make('page.vote.table', [
            'votes' => $this->vote->getCommentVotes($comment_id),
            'sections' => $this->section->get()
        ]);
    }


    /**
     * render comments vote table page
     *
     * @param int  $comment_id
     * @return Illuminate\Http\JsonResponse
     */
    public function commentJson($comment_id)
    {
        return Response::json(iterator_to_array($this->vote->getCommentVotes($comment_id)));
    }

    /**
     * upvote comment
     *
     * @param int  $comment_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function commentUp($comment_id)
    {
        $this->vote->action(Constant::COMMENT_TYPE, $comment_id, Constant::VOTE_UP);
        return Redirect::back(); 
    }


    /**
     * downvote comment
     *
     * @param int  $comment_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function commentDown($comment_id)
    {
        $this->vote->action(Constant::COMMENT_TYPE, $comment_id, Constant::VOTE_DOWN);
        return Redirect::back();
    }


    /**
     * see `commentUp`
     *
     * @param int  $comment_id
     * @return Illuminate\Http\JsonResponse
     */
    public function commentUpJson($comment_id)
    {
        return Response::json($this->vote->action(Constant::COMMENT_TYPE, $comment_id, Constant::VOTE_UP));
    }


    /**
     * see `commentDown`
     *
     * @param int  $comment_id
     * @return Illuminate\Http\JsonResponse
     */
    public function commentDownJson($comment_id)
    {
        return Response::json($this->vote->action(Constant::COMMENT_TYPE, $comment_id, Constant::VOTE_DOWN));
    }


    /**
     * upvote section
     *
     * @param int  $section_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function sectionUp($section_id)
    {
        $this->vote->action(Constant::SECTION_TYPE, $section_id, Constant::VOTE_UP);
        return Redirect::back(); 
    }


    /**
     * downvote section
     *
     * @param int  $section_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function sectionDown($section_id)
    {
        $this->vote->action(Constant::SECTION_TYPE, $section_id, Constant::VOTE_DOWN);
        return Redirect::back();
    }


    /**
     * see `sectionUp`
     *
     * @param int  $section_id
     * @return Illuminate\Http\JsonResponse
     */
    public function sectionUpJson($section_id)
    {
        return Response::json($this->vote->action(Constant::SECTION_TYPE, $section_id, Constant::VOTE_UP));
    }


    /**
     * see `sectionDown`
     *
     * @param int  $section_id
     * @return Illuminate\Http\JsonResponse
     */
    public function sectionDownJson($section_id)
    {
        return Response::json($this->vote->action(Constant::SECTION_TYPE, $section_id, Constant::VOTE_DOWN));
    }
}
