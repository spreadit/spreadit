<?php
class HelpController extends BaseController
{
    protected function index()
    {
        return View::make('page.help.index', ['sections' => Section::get()]);
    }

    protected function feeds()
    {
        return View::make('page.help.feeds', ['sections' => Section::get()]);
    }

    protected function posting()
    {
        return View::make('page.help.posting', ['sections' => Section::get()]);
    }

    protected function formatting()
    {
        return View::make('page.help.formatting', ['sections' => Section::get()]);
    }

    protected function points()
    {
        return View::make('page.help.points', ['sections' => Section::get()]);
    }

    protected function moderation()
    {
        return View::make('page.help.moderation', ['sections' => Section::get()]);
    }

    protected function anonymity()
    {
        return View::make('page.help.anonymity', ['sections' => Section::get()]);
    }

    protected function help()
    {
        return View::make('page.help.help', ['sections' => Section::get()]);
    }
}
