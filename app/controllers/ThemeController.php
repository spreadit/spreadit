<?php
class ThemeController extends BaseController
{
    protected function index()
    {
        return View::make('page.user.prefs.theme_index', ['sections' => Section::get()]);
    }

    private function cookie_switch($colorscheme)
    {
        return Redirect::to(Utility::backOrUrl("/theme"))->withCookie(Cookie::forever('theme', $colorscheme));

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
