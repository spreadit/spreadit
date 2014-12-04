@extends('layout.pages')

@section('title')
    <title>spreadit.io :: homepage has been saved</title>
@stop
@section('description')
    <meta name="description" content="your user homepage has been saved">
@stop

@section('content')
    <div class="span10">
        Your homepage has been saved. <a href="http://{{ Auth::user()->username }}.spreadit.io" target="_blank">View your homepage</a>
        <br><br>or click <a href="/preferences/homepage">here</a> to go back to editing your homepage.
    </div>
</div>
@stop

