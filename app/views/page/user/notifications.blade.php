@extends('layout.pages')

@section('title')
    <title>spreadit.io :: notifications</title>
@stop
@section('description')
    <meta name="description" content="check all of your notifications in one place">
@stop

@section('content')
@foreach ($notifications as $notification)
<div class="row-fluid notification">
    <div class="span12">
    @if ($notification->type == Constant::NOTIFICATION_COMMENT_TYPE)
        <div class="comment {{ $notification->read ? 'notification-read' : 'notification-unread' }}">
            <header>
                From <a class="username" href="/u/{{ $notification->username }}">{{ $notification->username }}</a><span class="upoints">{{ $notification->points }}</span><span class="uvotes">{{ $notification->votes }}</span><span class="timeago">{{ Utility::prettyAgo($notification->created_at) }} ago</span>
            </header>
            <div class="content">
                {{ $notification->data }}
            </div>
            <footer>
                <a href="/comments/{{ $notification->item_id }}">reply/view</a>
            </footer>
        </div>
    @elseif ($notification->type == Constant::NOTIFICATION_POST_TYPE)
        <div class="comment {{ $notification->read ? 'notification-read' : 'notification-unread' }}">
            <header>
                From <a class="username" href="/u/{{ $notification->username }}">{{ $notification->username }}</a><span class="upoints">{{ $notification->points }}</span><span class="uvotes">{{ $notification->votes }}</span> <span class="timeago">{{ Utility::prettyAgo($notification->created_at) }} ago</span>
            </header>
            <div class="content">
                {{ $notification->data }}
            </div>
            <footer>
                <a href="/comments/{{ $notification->item_id }}">reply/view</a>
            </footer>
        </div>
    @elseif ($notification->type == Constant::NOTIFICATION_ANNOUNCEMENT_TYPE)
        <div class="announcement {{ $notification->read ? 'notification-read' : 'notification-unread' }}">
            <header>
                An announcement from spreadit
            </header>
            <div class="content">
                {{ $notification->data }}
            </div>
        </div>
    @else 
    {{-- nothing shall go here --}}
    @endif
    </div>
</div>
@endforeach

{{ $notifications->links() }}
@stop
