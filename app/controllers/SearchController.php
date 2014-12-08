<?php
class SearchController extends BaseController
{
    public function search()
    {
        $query = Input::get("query");
        return Redirect::to("http://google.com/cse?cx=016150367247265234793:owmd7mbouww&ie=UTF-8&q=$query&sa=Search&nojs=1");
    }
}
