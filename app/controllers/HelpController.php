<?php
class HelpController extends BaseController
{
    protected $section;

    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    /**
     * renders index page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        return View::make('page.help.index', ['sections' => $this->section->get()]);
    }

    /**
     * renders feeds page
     *
     * @return Illuminate\View\View
     */
    public function feeds()
    {
        return View::make('page.help.feeds', ['sections' => $this->section->get()]);
    }


    /**
     * renders posting page
     *
     * @return Illuminate\View\View
     */
    public function posting()
    {
        return View::make('page.help.posting', ['sections' => $this->section->get()]);
    }


    /**
     * renders formatting page
     *
     * @return Illuminate\View\View
     */
    public function formatting()
    {
        return View::make('page.help.formatting', ['sections' => $this->section->get()]);
    }


    /**
     * renders points page
     *
     * @return Illuminate\View\View
     */
    public function points()
    {
        return View::make('page.help.points', ['sections' => $this->section->get()]);
    }


    /**
     * renders moderation page
     *
     * @return Illuminate\View\View
     */
    public function moderation()
    {
        return View::make('page.help.moderation', ['sections' => $this->section->get()]);
    }


    /**
     * renders anonymity page
     *
     * @return Illuminate\View\View
     */
    public function anonymity()
    {
        return View::make('page.help.anonymity', ['sections' => $this->section->get()]);
    }


    /**
     * renders helping page
     *
     * @return Illuminate\View\View
     */
    public function help()
    {
        return View::make('page.help.help', ['sections' => $this->section->get()]);
    }
}
