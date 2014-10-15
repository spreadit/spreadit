<?php
class PageController extends BaseController
{
    protected function about()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('about', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }

    protected function contact()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));
        
        return View::make('contact', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
    
    protected function threats()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('threats', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
    
    protected function login()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('login', [
            'sections' => $sections,
            'section' => $section,
        ]);
    }
}
