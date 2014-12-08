<?php
class HelpController extends BaseController
{
    protected $section;

    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    public function index()
    {
        return View::make('page.help.index', ['sections' => $this->section->get()]);
    }

    public function feeds()
    {
        return View::make('page.help.feeds', ['sections' => $this->section->get()]);
    }

    public function posting()
    {
        return View::make('page.help.posting', ['sections' => $this->section->get()]);
    }

    public function formatting()
    {
        return View::make('page.help.formatting', ['sections' => $this->section->get()]);
    }

    public function points()
    {
        return View::make('page.help.points', ['sections' => $this->section->get()]);
    }

    public function moderation()
    {
        return View::make('page.help.moderation', ['sections' => $this->section->get()]);
    }

    public function anonymity()
    {
        return View::make('page.help.anonymity', ['sections' => $this->section->get()]);
    }

    public function help()
    {
        return View::make('page.help.help', ['sections' => $this->section->get()]);
    }
}
