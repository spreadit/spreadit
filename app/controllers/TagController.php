<?php
class TagController extends BaseController
{
    protected function nsfw($post_id) {
        Tag::action($post_id, Tag::NSFW, Tag::UP);
        return Redirect::back(); 
    } 

    protected function sfw($post_id) {
        Tag::action($post_id, Tag::NSFW, Tag::DOWN);
        return Redirect::back(); 
    } 
    
    protected function nsfl($post_id) {
        Tag::action($post_id, Tag::NSFL, Tag::UP);
        return Redirect::back(); 
    } 
    
    protected function sfl($post_id) {
        Tag::action($post_id, Tag::NSFL, Tag::DOWN);
        return Redirect::back(); 
    } 

    protected function nsfwJson($post_id) {
        return Response::json(Tag::action($post_id, Tag::NSFW, Tag::UP));
    } 

    protected function sfwJson($post_id) {
        return Response::json(Tag::action($post_id, Tag::NSFW, Tag::DOWN));
    } 
    
    protected function nsflJson($post_id) {
        return Response::json(Tag::action($post_id, Tag::NSFL, Tag::UP));
    } 
    
    protected function sflJson($post_id) {
        return Response::json(Tag::action($post_id, Tag::NSFL, Tag::DOWN));
    } 
}
