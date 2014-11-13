@extends('layout.pages')

@section('title')
    <title>spreadit.io :: all spreadits list</title>
@stop
@section('description')
    <meta name="description" content="check out all of the spreadits we've accumulated">
@stop

@section('content')
<div>
    <div class="posts-container">
        @foreach ($spreadits as $spreadit)
        <a href="{{ URL::to("/s/" . $spreadit->title) }}">{{ $spreadit->title }}</a>
        @endforeach
    </div>
</div>
@stop
