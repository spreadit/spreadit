<?php
class AssetsController extends BaseController
{
    protected function prod($filename)
    {
        return Bust::css("/assets/prod/$filename");
    }
    
    protected function css($filename)
    {
        return Bust::css("/assets/css/$filename");
    }

    protected function css_themes($filename)
    {
        return Bust::css("/assets/css/themes/$filename");
    }

    protected function css_prefs($filename)
    {
        return Bust::css("/assets/css/prefs/$filename");
    }
}
