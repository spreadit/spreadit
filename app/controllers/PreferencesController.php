<?php
class PreferencesController extends BaseController
{
    protected function preferences()
    {
        $section_titles = function($input) {
            if(strlen(Auth::user()->$input) == 0) {
                return "";
            }

            $input_data = Auth::user()->$input;
            $arr = explode(',', $input_data);

            return implode(',', array_unique(F::map(Section::getById($arr), function($m) {
                return $m->title;
            })));
        };

        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));
        
        $frontpage_show_sections   = $section_titles('frontpage_show_sections');
        $frontpage_ignore_sections = $section_titles('frontpage_ignore_sections');

        return View::make('page.user.prefs.preferences', [
            'sections'                  => $sections,
            'section'                   => $section,
            'frontpage_show_sections'   => $frontpage_show_sections,
            'frontpage_ignore_sections' => $frontpage_ignore_sections,
        ]);
    }

    protected function preferencesJson()
    {
        return Response::json("moo");
    }

    protected function savePreferences()
    {
        $section_ids = function($input) {
            $input_data = Input::get($input, '');
            
            if(strlen($input_data) == 0) {
                return "";
            }
            
            $arr = explode(',', $input_data);

            return implode(',', array_unique(F::map(Section::getByTitle($arr), function($m) {
                return $m->id;
            })));
        };
        
        $show_nsfw = Input::get('show_nsfw', 0);
        $show_nsfl = Input::get('show_nsfl', 0);

        $frontpage_show_sections   = $section_ids('frontpage_show_sections');
        $frontpage_ignore_sections = $section_ids('frontpage_ignore_sections');

        
        if($frontpage_show_sections   == "0") $frontpage_show_sections   = "";
        if($frontpage_ignore_sections == "0") $frontpage_ignore_sections = "";

        User::savePreferences(Auth::id(), [
            'show_nsfw'                 => $show_nsfw,
            'show_nsfl'                 => $show_nsfl,
            'frontpage_show_sections'   => $frontpage_show_sections,
            'frontpage_ignore_sections' => $frontpage_ignore_sections,
        ]);

        return $this->savedPreferences();
    }

    protected function savedPreferences()
    {
        $sections = Section::get();
        $section = Section::sectionFromSections(Section::getByTitle([""]));

        return View::make('page.user.prefs.savedpreferences', [
            'sections' => $sections,
            'section'  => $section,
        ]);
    }

    protected function theme_index()
    {
        return View::make('page.user.prefs.theme_index', ['sections' => Section::get()]);
    }

    private function theme_cookie_switch($colorscheme)
    {
        return Redirect::to(Utility::backOrUrl("/theme"))->withCookie(Cookie::forever('theme', $colorscheme));

    }

    protected function theme_dark()
    {
        return $this->cookie_switch('dark');
    }

    protected function theme_light()
    {
        return $this->cookie_switch('light');
    }

    protected function theme_tiles()
    {
        return $this->cookie_switch('tiles');
    }

    protected function homepage()
    {
        return View::make('page.user.prefs.homepage', [
            'sections' => Section::get(),
            'markdown' => Auth::user()->profile_markdown,
            'css'      => Auth::user()->profile_css,
        ]);
    }

    protected function saveHomepage()
    {   
        $profile_markdown = Input::get('data', '');
        $profile_data     = Markdown::defaultTransform(e($profile_markdown));
        $profile_css      = Input::get('css', '');

        User::saveHomepage(Auth::id(), [
            'profile_data'       => $profile_data,
            'profile_markdown'   => $profile_markdown,
            'profile_css'        => $profile_css,
        ]);

        return $this->savedPreferences();
    }
}
