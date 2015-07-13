@extends('layout.default')

@section('title')
    <title>spreadit.io :: discuss {{ $section->title }}</title>
@stop
@section('description')
    <meta name="description" content="discussion and new links regarding {{ $section->title }}">
@stop

@section('bodyclasses')
    listing-page
    with-listing-chooser

    @if(Request::is('/'))
        front-page
    @endif

    @if($sort_highlight == 'controversial')
        show-controversial
    @elseif($sort_highlight == 'hot')
        hot-page
    @elseif($sort_highlight == 'new')
        new-page
    @elseif($sort_highlight == 'top')
        top-page
    @endif
@stop

@section('content')
    <div class="content">
        <div class='spacer'>
            @foreach ($posts as $rank => $post)
                @include('post.piece', [
                    'rank'   => $rank,
                    'post' => $post,
                ])
            @endforeach
        </div>

        {{ $posts->links() }}
    </div>
@stop
