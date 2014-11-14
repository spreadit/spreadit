@extends('layout.pages')

@section('title')
    <title>spreadit.io :: rate limit hit</title>
@stop

@section('content')
	<img src="/assets/images/429.jpg">
	<br>
	{{ $message }}
@stop
