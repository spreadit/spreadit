<?php
class History extends BaseModel
{
    protected $table = 'history';
    protected $guarded = array('id');

    public static $rules = [
        'data' => 'required|max:65535',
        'markdown' => 'required|max:65535',
        'user_id' => 'required|numeric',
        'type' => 'required|numeric',
        'type_id' => 'required|numeric'
    ];
    
    const COMMENT_TYPE = 0;
    const POST_TYPE = 1;
    const SECTION_TYPE = 2;
}
