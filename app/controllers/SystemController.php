<?php

class SystemController extends BaseController
{
    public static function isDevelopment()
    {
        return ($_SERVER['SERVER_NAME'] == 'spreadit.dev');
    }
}
