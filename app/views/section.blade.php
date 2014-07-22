@extends('layout.default')

@section('title')
    <title>spreadit.io :: {{ $section_title }}</title>
@stop

@section('style')
@stop

@section('content')
<div class="row-fluid">
    <div class="span8">
        @foreach ($posts as $post)
        @include('postpiece', ['post' => $post])
        @endforeach

        {{ $posts->links() }}
    </div>

    <div class="span4 hidden-phone">
        <div class="sidebar">
            <div class="section-description">
                {{ $sidebar }}
            </div>
            <div class="section-image">
                <img src="/assets/section_images/300x250.gif">
            </div>
        </div>
    </div>
</div>

@stop

@section('script')
@stop
