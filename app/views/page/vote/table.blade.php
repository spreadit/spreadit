@extends('layout.pages')

@section('title')
    <title>spreadit.io :: stats</title>
@stop
@section('description')
    <meta name="description" content="spreadit is so transparent with their voting urghrhr">
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
            <td>{{ Utility::prettyAgo($vote->created_at) }}</td>
            <td><a class="username" href="/u/{{ $vote->username }}">{{ $vote->username }}</a><span class="upoints">{{ $vote->points }}</span><span class="uvotes">{{ $vote->votes }}</span></td>
            <td><span class="{{ $vote->updown == Vote::UP ? 'voteiconup' : 'voteicondown' }}"></span></td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $votes->links() }}
@stop
