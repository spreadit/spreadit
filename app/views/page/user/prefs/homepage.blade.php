@extends('layout.pages')

@section('title')
    <title>spreadit.io :: homepage setup</title>
@stop
@section('description')
    <meta name="description" content="modify your spreadit page">
@stop

@section('content')
    <div class="span10">
        <form method="post">
            markdown: {{ Form::textarea('data', $markdown) }}
            <br>
            css: {{ Form::textarea('css', $css) }}
            <br>
            <button type="submit">Save</button>
        </form>
    </div>
</div>
@stop
