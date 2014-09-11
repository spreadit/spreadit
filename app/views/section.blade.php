@extends('layout.default')

@section('title')
    <title>spreadit.io :: discuss {{ $section->title }}</title>
@stop
@section('description')
    <meta name="description" content="discussion and new links regarding {{ $section->title }}">
@stop

@section('content')
<div>
    <div class="posts-container">
        @foreach ($posts as $post)
        @include('postpiece', ['post' => $post])
        @endforeach

        {{ $posts->links() }}
    </div>
    <div class="sidebar">
    </div>
</div>
@stop
