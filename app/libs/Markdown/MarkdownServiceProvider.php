<?php namespace Markdown;

use Illuminate\Support\ServiceProvider;

class MarkdownServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('markdown', function()
        {
            return new \Markdown\MarkdownExtra();
        });
    }
}
