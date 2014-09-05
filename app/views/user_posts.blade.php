@extends('layout.user')

@section('title')
    <title>spreadit.io :: {{ $username }}'s posts</title>
@stop
@section('description')
    <meta name="description" content="let's spy on {{ $username }}'s posts.. for science!">
@stop

@section('content')
@foreach ($posts as $post)
@include('postpiece', ['post' => $post])
@endforeach

{{ $posts->links() }}
@stop
