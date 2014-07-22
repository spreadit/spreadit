<?php
class Notification extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    protected $guarded = array('id');
}
