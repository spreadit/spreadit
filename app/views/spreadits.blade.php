@extends('layout.pages')

@section('title')
    <title>spreadit.io :: all spreadits list</title>
@stop

@section('style')
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

@section('script')
@stop
