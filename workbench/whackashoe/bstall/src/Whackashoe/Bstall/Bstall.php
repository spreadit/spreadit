<?php namespace Whackashoe\Bstall;

use Config;
use View;
use Redis;

class Bstall
{
    
    protected $redis;  ///Predis\Client
    protected $canvas; ///array( array ( int ) )

    protected $config;  /// array
    protected $width;   /// int
    protected $height;  /// int
    protected $bgcolor; /// int


    public function __construct()
    {
        $this->redis =  Redis::connection();
        $this->config = Config::get('bstall::config');

        $this->width = 100;
        $this->height = 100;
        $this->bgcolor = 0xFFFFFF;
        
        $this->canvas = array();
    }


    /**
     * Print stall's canvas
     *
     * @param  string  $name
     * @param  int     $width
     * @param  int     $height
     * @param  int     $bgcolor
     * @return Illuminate\View\View
     */
    public function make($name="default", $width=100, $height=100, $bgcolor=0xFFFFFF)
    {
        $bstall = new Bstall;
        $this->width = $width;
        $this->height = $height;
        $this->bgcolor = $bgcolor;
        $this->load($name);
       
        return View::make('bstall::canvas', [
            'name'   => $name,
            'width'  => $this->width,
            'height' => $this->height,
            'canvas' => json_encode($this->canvas, true)
        ]);
    }


    /**
     * Load or initialize a stall
     *
     * @param  string  $name
     * @return void
     */
    public function load($name)
    {
        $stall = $this->redis->get("bstall_{$name}");

        if(is_null($stall)) {
            $this->clean();
            $this->save($name);
        } else {
            $canvas = unserialize($stall);
            $this->canvas = $canvas;
        }
    }


    /**
     * Save stall's canvas
     *
     * @param  string  $name
     * @return void
     */
    public function save($name)
    {
        $this->redis->set("bstall_{$name}", serialize($this->canvas));
    }


    /**
     * Cleans the stall, replacing each pixel with the set background color
     *
     * @param  string  $name
     * @return void
     */
    public function clean()
    {
        $this->canvas = [];

        for($y=0; $y < $this->height; $y++) {
            array_push($this->canvas, []);

            for($x=0; $x < $this->width; $x++) {
                $this->canvas[$y][$x] = $this->bgcolor;
            }
        }
    }


    /**
     * Scribble onto the stall
     *
     * @param  int  $x
     * @param  int  $y
     * @param  int  $color
     * @return void
     */
    public function write($x, $y, $color)
    {
        if(isset($this->canvas[$y][$x])) {
            $this->canvas[$y][$x] = $color;
        }
    }


    public function getCanvas()
    {
        return $this->canvas;
    }

    public function setCanvas($canvas)
    {
        $this->canvas = $canvas;
    }

    public function setBackgroundColor($color)
    {
        $this->bgcolor = $color;
    }
}