<?php

class CommentBranch
{
    public $children; //array
    public $data;

    public function __construct()
    {
        $this->children = array();
        $this->data = "";
    }

    /**
     * sort our branches (recursively)
     *
     * @param string $method sorting method
     * @param CommentBranch $branch branch to sort
     *
     * @return CommentBranch
     */
    public function sort($method, $branch=null)
    {
        if(is_null($branch)) {
            $branch = $this;
        }

        switch($method) {
            case 'new': usort($branch->children, function($a, $b) { return $a->id < $b->id; }); break;
        }

        F::each($branch->children, function($piece) use ($method) {
            $piece = $this->sort($method, $piece);
        });

        return $branch;
    }

    /*
     * render our branches (recursively)
     *
     * @param CommentBranch $branch
     * @param bool $first we treat first render differently
     *
     * @return string
     */
    public function render($branch=null, $first=false)
    {
        if($branch == null) {
            $branch = $this;
        }

        $rval = $first ? View::make('commentpiece', ['comment' => $branch]) : '';
     
        if(count($branch->children) > 0) {
            $result = F::reduce_left(F::map($branch->children, function($v) {
                $tresult = $this->render($v, true);
                return "<li>{$tresult}</li>";
            }), function($v, $i, $c, $r) {
                return $r . $v;
            });

            $rval .= "<ul class=\"commentbranch\">{$result}</ul>";
        }

        return $rval;
    }
}
