<?php
class ColorSchemeController extends BaseController
{
    protected function index()
    {
        return View::make('colorscheme_index', ['sections' => Section::get()]);
    }

    private function cookie_switch($colorscheme)
    {
        return Redirect::to(Utility::backOrUrl("/color"))->withCookie(Cookie::forever('colorscheme', $colorscheme));

    }

    protected function dark()
    {
        return $this->cookie_switch('dark');
    }

    protected function light()
    {
        return $this->cookie_switch('light');
    }

    protected function tiles()
    {
        return $this->cookie_switch('tiles');
    }
}
