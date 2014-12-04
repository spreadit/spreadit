@extends('layout.user')

@section('title')
    <title>spreadit.io :: {{ $username }}</title>
@stop

@section('style')
@stop

@section('content')
{{ $username }} is {{ ($stats->anonymous) ? 'an anonymous' : 'an ominous' }} {{ $stats->achievement }}<sup>(lvl {{ $stats->level }})</sup> spawned in the year {{ date("Y", $stats->created_at) }} on the glorious day of {{ date("F", $stats->created_at) }} the {{ date("jS", $stats->created_at) }}
<br><br>
visit {{ $username }}'s homepage <a href="http://{{ $username }}.spreadit.io">here</a>
@stop
