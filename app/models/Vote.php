<?php
class Vote extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'votes';

    protected $guarded = array('id');
}
