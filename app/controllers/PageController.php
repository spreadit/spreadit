<?php
class PageController extends BaseController
{
    protected function about()
    {
        return View::make('about', ['sections' => Section::get()]);
    }

    protected function contact()
    {
        return View::make('contact', ['sections' => Section::get()]);
    }
    
    protected function threats()
    {
        return View::make('threats', ['sections' => Section::get()]);
    }
    
    protected function login()
    {
        return View::make('login', ['sections' => Section::get()]);
    }
}
