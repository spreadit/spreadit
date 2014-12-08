<?php
class PageController extends BaseController
{
    protected $section;

    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    public function about()
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

        return View::make('page.about', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    public function contact()
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();
        
        return View::make('page.contact', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
    
    public function threats()
    {
        $sections = $this->section->get();
        $section = $this->section->sectionFromEmptySection();

        return View::make('page.threats', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
    
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
