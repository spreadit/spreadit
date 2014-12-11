<?php
class AssetsController extends BaseController
{
    /** 
     * Loads the css file at the given URL, replaces all urls within it to 
     * cachebusted CDN urls, and returns the resulting css source code 
     *
     * @param string  $filename
     * @return Illuminate\Http\Response
     */
    public function prod($filename)
    {
        return Bust::css("/assets/prod/$filename");
    }
    

    /**
     * see `prod`
     *
     * @param string  $filename
     * @return Illuminate\Http\Response
     */
    public function css($filename)
    {
        return Bust::css("/assets/css/$filename");
    }


    /**
     * see `prod`
     *
     * @param string  $filename
     * @return Illuminate\Http\Response
     */
    public function css_themes($filename)
    {
        return Bust::css("/assets/css/themes/$filename");
    }


    /**
     * see `prod`
     *
     * @param string  $filename
     * @return Illuminate\Http\Response
     */
    public function css_prefs($filename)
    {
        return Bust::css("/assets/css/prefs/$filename");
    }
}
