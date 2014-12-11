<?php
class TagController extends BaseController
{
    
    protected $tag;
    
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }
    
    /**
     * attempts to tag a post not safe for life
     *
     * @param int  $post_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function nsfw($post_id) {
        $this->tag->action($post_id, Constant::TAG_NSFW, Constant::TAG_UP);
        return Redirect::back(); 
    } 


    /**
     * attempts to tag a post safe for work
     *
     * @param int  $post_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function sfw($post_id) {
        $this->tag->action($post_id, Constant::TAG_NSFW, Constant::TAG_DOWN);
        return Redirect::back(); 
    } 
    

    /**
     * attempts to tag a post not safe for life
     *
     * @param int  $post_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function nsfl($post_id) {
        $this->tag->action($post_id, Constant::TAG_NSFL, Constant::TAG_UP);
        return Redirect::back(); 
    } 
    

    /**
     * attempts to tag a post safe for life
     *
     * @param int  $post_id
     * @return Illuminate\Http\RedirectResponse
     */
    public function sfl($post_id) {
        $this->tag->action($post_id, Constant::TAG_NSFL, Constant::TAG_DOWN);
        return Redirect::back(); 
    } 


    /**
     * see `nsfw`
     *
     * @param int  $post_id
     * @return Illuminate\Http\JsonResponse
     */
    public function nsfwJson($post_id) {
        return Response::json($this->tag->action($post_id, Constant::TAG_NSFW, Constant::TAG_UP));
    } 


    /**
     * see `sfw`
     *
     * @param int  $post_id
     * @return Illuminate\Http\JsonResponse
     */
    public function sfwJson($post_id) {
        return Response::json($this->tag->action($post_id, Constant::TAG_NSFW, Constant::TAG_DOWN));
    } 
    

    /**
     * see `nsfl`
     *
     * @param int  $post_id
     * @return Illuminate\Http\JsonResponse
     */
    public function nsflJson($post_id) {
        return Response::json($this->tag->action($post_id, Constant::TAG_NSFL, Constant::TAG_UP));
    } 
    

    /**
     * see `sfl`
     *
     * @param int  $post_id
     * @return Illuminate\Http\JsonResponse
     */
    public function sflJson($post_id) {
        return Response::json($this->tag->action($post_id, Constant::TAG_NSFL, Constant::TAG_DOWN));
    } 
}
