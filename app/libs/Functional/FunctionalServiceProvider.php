<?php namespace Functional;

use Illuminate\Support\ServiceProvider;

class FunctionalServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('functional', function()
        {
            return new FunctionalShim();
        });
    }
}
