<?php namespace Whackashoe\Bstall\Controllers;

use Whackashoe\Bstall\Bstall;
use Response;
use Input;

class BstallController extends BaseController
{
    protected $bstall; /// Whackashoe\Bstall\Bstall

    public function __construct(Bstall $bstall)
    {
        $this->bstall = $bstall;
    }


    /**
     * Scratch onto the stall
     *
     * @param  string  $name
     * @return Illuminate\Http\Response;
     */
    public function draw($name)
    {
        $this->bstall->load($name);
        
        $pixels = Input::get('pixels');
        foreach($pixels as $p) {
            if(isset($p['x']) 
            && isset($p['y']) 
            && isset($p['c'])) {
                $this->bstall->write($p['x'], $p['y'], $p['c']);
            }
        }
        
        $this->bstall->save($name);

        return Response::json(["success" => true]);
    }
}
