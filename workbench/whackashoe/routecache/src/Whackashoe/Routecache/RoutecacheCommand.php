<?php namespace Whackashoe\Routecache;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteCollection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Symfony\Component\Console\Input\InputOption;


class RoutecacheCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'route:cache';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a route cache file for faster route registration.';

    /** 
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The configuration settings.
     *
     * @var array
     */
    protected $config;

	/**
	 * Create a new command instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @param  \Illuminate\Config\Repository $config
	 * @return void
	 */
	public function __construct(Router $router, Filesystem $files, Repository $config)
	{
		parent::__construct();
        
        $this->router = $router;
        $this->files = $files;
        $this->config = $config->get('routecache::config');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->call('route:clear');
        $routes = $this->router->getRoutes();
		
		if (count($routes) == 0) {
			return $this->error("Your application doesn't have any routes.");
		}

		foreach ($routes as $k => &$route) {
            if(in_array($route->getUri(), $this->config['ignoreUris'])) {
                unset($k);
            } else {
                $route->prepareForSerialization();
                try {
                    serialize($route);
                } catch(\Exception $e) {
                    throw new \LogicException("
                        Unable to prepare route [{$route->getUri()}] for serialization.
                        \nPlace it in the ignoreUris config or make it not a closure.
                    ");
                }
            }
		}

		$this->files->put(
			$this->config['compiledPath'],
			$this->buildRouteCacheFile($routes)
		);
		
		$this->info('Routes cached successfully!');
	}


	/**
	 * Built the route cache file.
	 *
	 * @param  \Illuminate\Routing\RouteCollection  $routes
	 * @return string
	 */
	protected function buildRouteCacheFile(RouteCollection $routes)
	{
		$stub = $this->files->get(__DIR__.'/stubs/routes.stub');
		return str_replace('{{routes}}', base64_encode(serialize($routes)), $stub);
	}
}
