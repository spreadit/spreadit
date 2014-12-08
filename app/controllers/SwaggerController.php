<?php
class SwaggerController extends BaseController
{
    public function index()
    {
        return View::make('swagger.index');
    }

    public function license()
    {
        return View::make('swagger.license');
    }

    public function terms()
    {
        return View::make('swagger.terms');
    }

    public function routes()
    {
        return Response::make(View::make('swagger.json.routes'))->header('Content-Type', 'application/json');
    }
    
    public function getRoute($type)
    {
        return Response::make(View::make("swagger.json.$type"))->header('Content-Type', 'application/json');
    }


}
