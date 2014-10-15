@extends('layout.pages')

@section('title')
    <title>spreadit.io :: select a specific section</title>
@stop
@section('description')
    <meta name="description" content="select a specific section to post to">
@stop

@section('content')
<p>Please select a spreadit below:</p>
<ul>
@foreach ($selections as $sel)
    <li><a href="/s/{{ $sel }}/add">{{ $sel }}</a></li>
@endforeach
</ul>
@stop

@section('script')
@stop
