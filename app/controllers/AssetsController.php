<?php
class AssetsController extends BaseController
{
    public function prod($filename)
    {
        return Bust::css("/assets/prod/$filename");
    }
    
    public function css($filename)
    {
        return Bust::css("/assets/css/$filename");
    }

    public function css_themes($filename)
    {
        return Bust::css("/assets/css/themes/$filename");
    }

    public function css_prefs($filename)
    {
        return Bust::css("/assets/css/prefs/$filename");
    }
}
