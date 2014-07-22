@extends('layout.user')

@section('title')
    <title>spreadit.io :: {{ $username }}'s posts</title>
@stop

@section('style')
@stop

@section('content')
@foreach ($posts as $post)
@include('postpiece', ['post' => $post])
@endforeach

{{ $posts->links() }}
@stop

@section('script')
@stop
