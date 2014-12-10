<?php namespace Whackashoe\Bstall\Controllers;

use Whackashoe\Bstall\Bstall;

class AssetsController extends BaseController
{
    protected $bstall;

    public function __construct(Bstall $bstall)
    {
        $this->bstall = $bstall;
    }

    public function html()
    {
        return $this->bstall->html();
    }

    public function js()
    {
        
    }
}
