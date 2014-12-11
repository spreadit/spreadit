<?php
class SwaggerController extends BaseController
{

    /**
     * renders index page of swagger api demo
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        return View::make('swagger.index');
    }


    /**
     * renders license page
     *
     * @return Illuminate\View\View
     */
    public function license()
    {
        return View::make('swagger.license');
    }


    /**
     * renders terms of service page
     *
     * @return Illuminate\View\View
     */
    public function terms()
    {
        return View::make('swagger.terms');
    }


    /**
     * get listing of swagger routes
     *
     * @return Illuminate\Http\Response
     */
    public function routes()
    {
        return Response::make(View::make('swagger.json.routes'))->header('Content-Type', 'application/json');
    }
    

    /**
     * get swagger route
     *
     * @return mixed
     */
    public function getRoute($type)
    {
        if(in_array($type, ['auth', 'comments', 'posts', 'sections', 'users'])) {
            return Response::make(View::make("swagger.json.routes.$type"))->header('Content-Type', 'application/json');
        } else {
            return App::abort(404);
        }
    }
}
