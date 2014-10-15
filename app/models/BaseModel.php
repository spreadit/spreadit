<?php
use Carbon\Carbon;
use LaravelBook\Ardent\Ardent;

class BaseModel extends Ardent
{
    public function getDateFormat()
    {
        return 'U';
    }
}
