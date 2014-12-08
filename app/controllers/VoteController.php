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

    public function postView($type_id)
    {
        return View::make('page.vote.table', [
            'votes' => $this->vote->getPostVotes($type_id),
            'sections' => $this->section->get()
        ]);
    }

    public function postJson($type_id)
    {
        return Response::json(iterator_to_array($this->vote->getPostVotes($type_id)));
    }
    
    public function postUp($type_id)
    {
        $this->vote->action($this->vote->POST_TYPE, $type_id, $this->vote->UP);
        return Redirect::back(); 
    }

    public function postDown($type_id)
    {
        $this->vote->action($this->vote->POST_TYPE, $type_id, $this->vote->DOWN);
        return Redirect::back();
    }

    public function postUpJson($type_id)
    {
        return Response::json($this->vote->action($this->vote->POST_TYPE, $type_id, $this->vote->UP));
    }

    public function postDownJson($type_id)
    {
        return Response::json($this->vote->action($this->vote->POST_TYPE, $type_id, $this->vote->DOWN));
    }

    public function commentView($type_id)
    {
        return View::make('page.vote.table', [
            'votes' => $this->vote->getCommentVotes($type_id),
            'sections' => $this->section->get()
        ]);
    }

    public function commentUp($type_id)
    {
        $this->vote->action($this->vote->COMMENT_TYPE, $type_id, $this->vote->UP);
        return Redirect::back(); 
    }

    public function commentDown($type_id)
    {
        $this->vote->action($this->vote->COMMENT_TYPE, $type_id, $this->vote->DOWN);
        return Redirect::back();
    }

    public function commentJson($type_id)
    {
        return Response::json(iterator_to_array($this->vote->getCommentVotes($type_id)));
    }

    public function commentUpJson($type_id)
    {
        return Response::json($this->vote->action($this->vote->COMMENT_TYPE, $type_id, $this->vote->UP));
    }

    public function commentDownJson($type_id)
    {
        return Response::json($this->vote->action($this->vote->COMMENT_TYPE, $type_id, $this->vote->DOWN));
    }

    public function sectionUp($type_id)
    {
        $this->vote->action($this->vote->SECTION_TYPE, $type_id, $this->vote->UP);
        return Redirect::back(); 
    }

    public function sectionDown($type_id)
    {
        $this->vote->action($this->vote->SECTION_TYPE, $type_id, $this->vote->DOWN);
        return Redirect::back();
    }

    public function sectionUpJson($type_id)
    {
        return Response::json($this->vote->action($this->vote->SECTION_TYPE, $type_id, $this->vote->UP));
    }

    public function sectionDownJson($type_id)
    {
        return Response::json($this->vote->action($this->vote->SECTION_TYPE, $type_id, $this->vote->DOWN));
    }
}
