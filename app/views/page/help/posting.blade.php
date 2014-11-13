@extends('layout.help')

@section('title')
    <title>spreadit.io :: help/posting</title>
@stop
@section('description')
    <meta name="description" content="how the hell do I post on this thing?">
@stop

@section('content')
<h2>Creating Posts</h2>
<p>
posts must contain a title, spreadit, and a url or content or both!<br>
if you post to a spreadit which doesn't exist yet a new one will be automatically created<br>
post titles may not be edited once created, though- if you notice a mispelling you can delete the post because mispelling the title is pathetic<br>
post content may be edited once posted, all edits are saved<br>
other people may reply to your post with comments, and you will be notified of these<br>
you may use <a href="/help/formatting">markdown &amp; latex</a> formatting inside your post<br>
</p>
@stop
