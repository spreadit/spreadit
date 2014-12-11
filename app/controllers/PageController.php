<?php
class PageController extends BaseController
{
    protected $section;

    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    /**
     * renders about page
     *
     * @return Illuminate\View\View
     */
    public function about()
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

        return View::make('page.about', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }


    /**
     * renders contact page
     *
     * @return Illuminate\View\View
     */
    public function contact()
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();
        
        return View::make('page.contact', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
    
    
    /**
     * renders threats page
     *
     * @return Illuminate\View\View
     */
    public function threats()
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

        return View::make('page.threats', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
    

    /**
     * renders login page
     *
     * @return Illuminate\View\View
     */
    public function login()
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

        return View::make('page.login', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
}
