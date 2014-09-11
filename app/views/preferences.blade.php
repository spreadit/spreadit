@extends('layout.pages')

@section('title')
    <title>spreadit.io :: preferences</title>
@stop
@section('description')
    <meta name="description" content="check & change your user preferences here">
@stop

@section('content')
    <div class="span10">
        <form method="post">
            <label for="pref-email">Password Recovery Email</label>
            <input type="text" id="pref-email">

            <label for="pref-bitcoin">Bitcoin Address</label>
            <input type="text" id="pref-bitcoin">

            <label for="pref-profile">Profile</label>
            <textarea id="pref-profile"></textarea>

            <button type="submit">Save</button>
        </form>
    </div>
</div>
@stop
