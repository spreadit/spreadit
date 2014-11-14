@extends('layout.pages')

@section('title')
    <title>spreadit.io :: select a specific section</title>
@stop
@section('description')
    <meta name="description" content="select a specific section to post to">
@stop

@section('content')
<p>Please select a spreadit below:</p>
<div class="row-fluid">
	<div class="span6">
		<form method="get" action="/util/redirect">
			<div class="spreadit-selector">
				<select name="url" id="url">
					@foreach ($selections as $sel)
					    <option value="/s/{{ $sel }}/add">{{ $sel }}</option>
					@endforeach
				</select>
			</div>
			<button type="submit">go</button>
		</form>
	</div>
</div>
@stop