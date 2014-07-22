@extends('layout.pages')

@section('title')
    <title>spreadit.io :: stats</title>
@stop

@section('style')
@stop

@section('content')
<table>
    <thead>
        <tr>
            <td style="width: 20%">timestamp</td>
            <td style="width: 20%">when</td>
            <td style="width: 20%">username</td>
            <td style="width: 20%">vote</td>
        </tr>
    </thead>
    <tbody>
    @foreach ($votes as $vote)
        <tr>
            <td>{{ $vote->created_at }}</td>
            <td>{{ PostController::prettyAgo($vote->created_at) }}</td>
            <td><a href="/u/{{ $vote->username }}">{{ $vote->username }}</a></td>
            <td>{{ $vote->updown == VoteController::UP ? '&#x25B2;' : '&#x25BC;' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $votes->links() }}
@stop

@section('script')
@stop
