<?php namespace Whackashoe\Routecache;
use Illuminate\Foundation\Application;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository;


class RouteclearCommand extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'route:clear';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the route cache file';

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
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @param  \Illuminate\Config\Repository $config
     * @return void
     */
    public function __construct(Filesystem $files, Repository $config)
    {
        parent::__construct();
        
        $this->files = $files;
        $this->config = $config->get('routecache::config');     
   }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        //$this->files->delete($this->laravel->getCachedRoutesPath()); //TODO
        $this->info('Route cache cleared!');
    }
}
