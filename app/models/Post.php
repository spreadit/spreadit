<?php
class Post extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    protected $guarded = array('id');
}
