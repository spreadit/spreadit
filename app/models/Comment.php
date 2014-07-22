<?php
class Comment extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';

    protected $guarded = array('id');
}
