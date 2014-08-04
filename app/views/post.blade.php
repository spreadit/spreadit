@extends('layout.default')

@section('title')
    <title>spreadit.io :: {{ $post->title }}</title>
@stop

@section('style')
@stop

@section('content')
@if ($errors->any())
<div class="alert alert-warning fade in">
    <div class="close" data-dismiss="alert" aria-hidden="true">&times;</div>
    <h4 class="text-center">{{ $errors->first() }}</h4>
</div>
@endif
<div>
    <div class="posts-container">
        <div class="post">
            <div class="post-piece" tabindex="1">
                <div class="post-points">
                    <span class="vote {{ $post->selected == VoteController::UP ? 'selected' : '' }} {{ $post->selected == VoteController::DOWN ? 'disable-click' : ''}}" data-id="{{ $post->id }}" data-type="post" data-updown="up">&#x25B2;</span>
                    <span class="vote {{ $post->selected == VoteController::DOWN ? 'selected' : '' }} {{ $post->selected == VoteController::UP ? 'disable-click' : '' }}" data-id="{{ $post->id }}" data-type="post" data-updown="down">&#x25BC;</span>
                    <a href="/vote/post/{{ $post->id }}">
                        <span class="upvotes">{{ $post->upvotes }}</span>-<span class="downvotes">{{ $post->downvotes }}</span> <span class="total-points">{{ $post->upvotes - $post->downvotes }}</span>
                    </a>
                    <br>
                    {{ UtilController::prettyAgo($post->created_at) }}
                    <br>
                    <a rel="nofollow" href="/u/{{ $post->username }}">{{ $post->username }}</a>({{ $post->points }})
                </div>
                <div class="post-data">
                    <h1><a rel="nofollow" href="{{ $post->url }}">{{ $post->title }}</a></h1>
                    <br>
                    {{ $post->data }}
                    <menu>
                    @if (Auth::check())
                        <a class="post-action reply" data-type="post" data-id="{{ $post->id }}">reply </a>
                        <a class="post-action source" data-type="post" data-id="{{ $post->id }}">source </a>
                        @if ($post->user_id == Auth::id())
                            <a class="post-action edit" data-type="post" data-id="{{ $post->id }}">edit </a>
                        @endif
                    @else
                        <a href="/login">Register</a> to post replies
                    @endif
                    </menu>
                </div>
            </div>
            {{ $comments }}
        </div>
    </div>
    <div class="sidebar">
        <div class="section-description">
            {{ $sidebar }}
        </div>
        <div class="section-image">
            <img src="/assets/section_images/300x250.gif">
        </div>
    </div>
</div>
@stop
@section('script')
@stop
