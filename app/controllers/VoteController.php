<?php
class VoteController extends BaseController
{
    protected function postView($type_id)
    {
        return View::make('page.vote.table', [
            'votes' => Vote::getPostVotes($type_id),
            'sections' => Section::get()
        ]);
    }

    protected function postJson($type_id)
    {
        return Response::json(iterator_to_array(Vote::getPostVotes($type_id)));
    }
    
    protected function postUp($type_id)
    {
        Vote::action(Vote::POST_TYPE, $type_id, Vote::UP);
        return Redirect::back(); 
    }

    protected function postDown($type_id)
    {
        Vote::action(Vote::POST_TYPE, $type_id, Vote::DOWN);
        return Redirect::back();
    }

    protected function postUpJson($type_id)
    {
        return Response::json(Vote::action(Vote::POST_TYPE, $type_id, Vote::UP));
    }

    protected function postDownJson($type_id)
    {
        return Response::json(Vote::action(Vote::POST_TYPE, $type_id, Vote::DOWN));
    }

    protected function commentView($type_id)
    {
        return View::make('page.vote.table', [
            'votes' => Vote::getCommentVotes($type_id),
            'sections' => Section::get()
        ]);
    }

    protected function commentUp($type_id)
    {
        Vote::action(Vote::COMMENT_TYPE, $type_id, Vote::UP);
        return Redirect::back(); 
    }

    protected function commentDown($type_id)
    {
        Vote::action(Vote::COMMENT_TYPE, $type_id, Vote::DOWN);
        return Redirect::back();
    }

    protected function commentJson($type_id)
    {
        return Response::json(iterator_to_array(Vote::getCommentVotes($type_id)));
    }

    protected function commentUpJson($type_id)
    {
        return Response::json(Vote::action(Vote::COMMENT_TYPE, $type_id, Vote::UP));
    }

    protected function commentDownJson($type_id)
    {
        return Response::json(Vote::action(Vote::COMMENT_TYPE, $type_id, Vote::DOWN));
    }

    protected function sectionUp($type_id)
    {
        Vote::action(Vote::SECTION_TYPE, $type_id, Vote::UP);
        return Redirect::back(); 
    }

    protected function sectionDown($type_id)
    {
        Vote::action(Vote::SECTION_TYPE, $type_id, Vote::DOWN);
        return Redirect::back();
    }

    protected function sectionUpJson($type_id)
    {
        return Response::json(Vote::action(Vote::SECTION_TYPE, $type_id, Vote::UP));
    }

    protected function sectionDownJson($type_id)
    {
        return Response::json(Vote::action(Vote::SECTION_TYPE, $type_id, Vote::DOWN));
    }
}
