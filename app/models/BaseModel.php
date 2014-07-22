<?php
use Carbon\Carbon;

class BaseModel extends Eloquent
{
    protected function getDateFormat()
    {
        return 'U';
    }
}
