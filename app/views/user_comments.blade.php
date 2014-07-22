@extends('layout.user')

@section('title')
    <title>spreadit.io :: {{ $username }}'s comments</title>
@stop

@section('style')
@stop

@section('content')
@foreach ($comments as $comment)
@include('commentpiece', ['comment' => $comment, 'user_page' => true])
@endforeach

{{ $comments->links() }}
@stop

@section('script')
@stop
