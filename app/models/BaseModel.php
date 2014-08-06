<?php
use Carbon\Carbon;
use LaravelBook\Ardent\Ardent;

class BaseModel extends Ardent
{
    protected function getDateFormat()
    {
        return 'U';
    }
}
