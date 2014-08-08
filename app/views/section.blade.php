@extends('layout.default')

@section('title')
    <title>spreadit.io :: {{ $section->title }}</title>
@stop

@section('style')
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
        <div class="section-description">
            {{ $section->data }}
        </div>
        <div class="section-image">
            <img src="/assets/section_images/300x250.gif">
        </div>
    </div>
</div>
@stop

@section('script')
@stop
