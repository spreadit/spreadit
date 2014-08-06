<?php
class VoteController extends BaseController
{
    protected function postView($type_id)
    {
        return View::make('vote_table', [
            'votes' => Vote::getPostVotes($type_id),
            'sections' => Section::get()
        ]);
    }
    
    protected function postUp($type_id)
    {
        return Response::json(Vote::action(Vote::POST_TYPE, $type_id, Vote::UP));
    }

    protected function postDown($type_id)
    {
        return Response::json(Vote::action(Vote::POST_TYPE, $type_id, Vote::DOWN));
    }

    protected function commentView($type_id)
    {
        return View::make('vote_table', [
            'votes' => Vote::getCommentVotes($type_id),
            'sections' => Section::get()
        ]);
    }

    protected function commentUp($type_id)
    {
        return Response::json(Vote::action(Vote::COMMENT_TYPE, $type_id, Vote::UP));
    }

    protected function commentDown($type_id)
    {
        return Response::json(Vote::action(Vote::COMMENT_TYPE, $type_id, Vote::DOWN));
    }

    protected function sectionUp($type_id)
    {
        return Response::json(Vote::action(Vote::SECTION_TYPE, $type_id, Vote::UP));
    }

    protected function sectionDown($type_id)
    {
        return Response::json(Vote::action(Vote::SECTION_TYPE, $type_id, Vote::DOWN));
    }
}
