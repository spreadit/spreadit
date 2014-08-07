<?php
class SwaggerController extends BaseController
{
    protected function index()
    {
        return View::make('swagger/index');
    }

    protected function license()
    {
        return View::make('swagger/license');
    }

    protected function routes()
    {
        return Response::make(View::make('swagger/json/routes'))->header('Content-Type', 'application/json');
    }
    
    protected function getRoute($type)
    {
        return Response::make(View::make("swagger/json/$type"))->header('Content-Type', 'application/json');
    }


}
