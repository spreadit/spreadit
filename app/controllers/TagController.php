<?php
class TagController extends BaseController
{
    
    protected $tag;
    
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }
    
    public function nsfw($post_id) {
        $this->tag->action($post_id, $this->tag->NSFW, $this->tag->UP);
        return Redirect::back(); 
    } 

    public function sfw($post_id) {
        $this->tag->action($post_id, $this->tag->NSFW, $this->tag->DOWN);
        return Redirect::back(); 
    } 
    
    public function nsfl($post_id) {
        $this->tag->action($post_id, $this->tag->NSFL, $this->tag->UP);
        return Redirect::back(); 
    } 
    
    public function sfl($post_id) {
        $this->tag->action($post_id, $this->tag->NSFL, $this->tag->DOWN);
        return Redirect::back(); 
    } 

    public function nsfwJson($post_id) {
        return Response::json($this->tag->action($post_id, $this->tag->NSFW, $this->tag->UP));
    } 

    public function sfwJson($post_id) {
        return Response::json($this->tag->action($post_id, $this->tag->NSFW, $this->tag->DOWN));
    } 
    
    public function nsflJson($post_id) {
        return Response::json($this->tag->action($post_id, $this->tag->NSFL, $this->tag->UP));
    } 
    
    public function sflJson($post_id) {
        return Response::json($this->tag->action($post_id, $this->tag->NSFL, $this->tag->DOWN));
    } 
}
