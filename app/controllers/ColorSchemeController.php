<?php
class ColorSchemeController extends BaseController
{
    protected function index()
    {
        return View::make('colorscheme_index', ['sections' => Section::get()]);
    }

    protected function dark()
    {
        return Redirect::to(Utility::backOrUrl("/color"))->withCookie(Cookie::forever('colorscheme', 'dark'));
    }

    protected function light()
    {
        
        return Redirect::to(Utility::backOrUrl("/color"))->withCookie(Cookie::forever('colorscheme', 'light'));
    }
}
