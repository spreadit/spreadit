<?php namespace Whackashoe\Bstall;

use Config;
use View;

class Bstall
{
    protected $id;
    protected $config;

    public function __construct()
    {
        $this->config = Config::get('bstall::config');
    }

    public function html()
    {
        return View::make('bstall::canvas', [
            'id'     => 0,
            'width'  => 300,
            'height' => 300
        ]);
    }

    /* clears a bathroom stall
    */
    public function clear($bgcolor)
    {

    }


    /* write on stall
    */
    public function write()
    {

    }
}