<?php
class PageController extends BaseController
{
    protected function about()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('page.about', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    protected function contact()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));
        
        return View::make('page.contact', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
    
    protected function threats()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('page.threats', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
    
    protected function login()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('page.login', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
}
