<?php
use \Functional as F;

class CommentTree
{
    /* CommentBranch */
    protected $nodes;

    /*
     * create a comment tree
     *
     * @param array $comments
     */
    public function __construct(array $comments)
    {
        $this->nodes = new CommentBranch();
        $lookup_table = array();

        foreach($comments as $i) {
            $i->children = array();

            if($i->parent_id == 0) {
                $this->nodes->children[$i->id] = $i;
                $lookup_table[$i->id] = $i->id;
            } else {
                if(isset($lookup_table[$i->parent_id])) {
                    $path = explode('_', $lookup_table[$i->parent_id]);
                    
                    $tmp = F::reduce_left($path, function($v, $i, $c, $r) {
                        return $r->children[$v];
                    }, $this->nodes);

                    $tmp->children[$i->id] = $i;
                    $lookup_table[$i->id] = $lookup_table[$i->parent_id] . '_' . $i->id;
                } else {
                    throw new LogicException("this shouldn't be happening");
                }
            }
        }

    }

    /*
     * get the nodes
     *
     * @return CommentBranch
     */
    public function grab()
    {
        return $this->nodes;
    }
}
