<?php
class HelpController extends BaseController
{
    protected function index()
    {
        return View::make('help_index', ['sections' => Section::get()]);
    }

    protected function feeds()
    {
        return View::make('help_feeds', ['sections' => Section::get()]);
    }

    protected function posting()
    {
        return View::make('help_posting', ['sections' => Section::get()]);
    }

    protected function formatting()
    {
        return View::make('help_formatting', ['sections' => Section::get()]);
    }

    protected function points()
    {
        return View::make('help_points', ['sections' => Section::get()]);
    }

    protected function moderation()
    {
        return View::make('help_moderation', ['sections' => Section::get()]);
    }

    protected function anonymity()
    {
        return View::make('help_anonymity', ['sections' => Section::get()]);
    }

    protected function help()
    {
        return View::make('help_help', ['sections' => Section::get()]);
    }
}
