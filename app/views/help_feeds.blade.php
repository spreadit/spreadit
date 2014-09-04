@extends('layout.help')

@section('title')
    <title>spreadit.io :: help/feeds</title>
@stop

@section('content')
<h2>RSS &amp; Atom Feeds</h2>
<p>
    feeds are accessed by appending /.rss or /.atom to a spreadit url<br>
    examples:
    <ul>
        <li><a href="https://spreadit.io/.rss">https://spreadit.io/.rss</a></li>
        <li><a href="https://spreadit.io/.atom">https://spreadit.io/.atom</a></li>
        <li><a href="https://spreadit.io/s/news/.rss">https://spreadit.io/s/news/.rss</a></li>
        <li><a href="https://spreadit.io/s/news/.atom">https://spreadit.io/s/news/.atom</a></li>
    </ul>
    currently only "hot" sorting is applied to spreadits feeds<br>
</p>
@stop
