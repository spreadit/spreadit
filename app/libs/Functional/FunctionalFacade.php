<?php namespace Functional;

use Illuminate\Support\Facades\Facade;

class FunctionalFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'functional'; }

}
