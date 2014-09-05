@extends('layout.user')

@section('title')
    <title>spreadit.io :: {{ $username }}'s comments</title>
@stop
@section('description')
    <meta name="description" content="let's spy on {{ $username }}'s comments.. for science!">
@stop


@section('content')
@foreach ($comments as $comment)
@include('commentpiece', ['comment' => $comment, 'user_page' => true])
@endforeach

{{ $comments->links() }}
@stop
