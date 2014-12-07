<?php namespace Whackashoe\Routecache;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
 
class RoutecacheServiceProvider extends ServiceProvider {
 
    protected $app;

    /** 
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('whackashoe/routecache');
    }
 
    /** 
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {   
        $this->app->bindShared('command.route:cache', function($app)
        {
            return new RoutecacheCommand($app['router'], $app['files'], $app['config']);
        });

        $this->commands('command.route:cache');


        $this->app->bindShared('command.route:clear', function($app)
        {
            return new RouteclearCommand($app['files'], $app['config']);
        });
  
        $this->commands('command.route:clear');
    }   
 
    /** 
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {   
        return array('command.route:cache', 'command.route:clear');
    }
}
