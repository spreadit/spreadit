<?php namespace Whackashoe\Bstall\Controllers;

use Illuminate\Foundation\Application;

class BaseController extends \Illuminate\Routing\Controller
    {
        /**
         * The application instance.
         *
         * @var \Illuminate\Foundation\Application
         */
        protected $app;

        public function __construct(Application $app)
        {
            $this->app = $app;
        }
    }
