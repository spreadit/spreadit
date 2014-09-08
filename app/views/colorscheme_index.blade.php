@extends('layout.pages')

@section('title')
    <title>spreadit.io :: a little spec out in the universe</title>
@stop
@section('description')
    <meta name="description" content="spreadit is a link sharing and bullshit generator">
@stop

@section('content')
<div class="row-fluid">
    <div class="span12">
        <h2>Choose a colorscheme</h2>
        <p>There has been quite a few requests for a lighter themed colorscheme. I've made one which isn't finished, but I'd like to show you and see what your thoughts on it are! You can switch it at any time.</p>
        <ul>
            <li><a href="/color/light">Light color scheme</a></li>
            <li><a href="/color/dark">Dark color scheme</a></li>
        </ul>
    </div>
</div>
@stop
