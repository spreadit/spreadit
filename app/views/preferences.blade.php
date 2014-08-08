@extends('layout.pages')

@section('title')
    <title>spreadit.io :: preferences</title>
@stop

@section('style')
@stop

@section('content')
<div class="row-fluid">
    <div class="span2">
        <form method="post">
            <label for="color-background">Background</label>
            <input type="color" id="color-background">

            <label for="color-text">Text</label>
            <input type="color" id="color-text">

            <label for="color-link">Link</label>
            <input type="color" id="color-link">

            <label for="color-menu">Menu</label>
            <input type="color" id="color-menu">

            <label for="color-menutext">Menu Text</label>
            <input type="color" id="color-menutext">

            <label for="color-post">Post</label>
            <input type="color" id="color-post">

            <label for="color-oddpost">Post(odd)</label>
            <input type="color" id="color-oddpost">

            <label for="color-comment">Comment</label>
            <input type="color" id="color-comment">

            <label for="color-oddcomment">Comment(odd)</label>
            <input type="color" id="color-oddcomment">

            <label for="color-vote">Vote</label>
            <input type="color" id="color-vote">

            <label for="color-selectedvote">Vote(selected)</label>
            <input type="color" id="color-selectedvote">

            <label for="color-sidebar">Sidebar</label>
            <input type="color" id="color-sidebar">

            <label for="color-textsidebar">Sidebar Text</label>
            <input type="color" id="color-textsidebar">
            
            <button type="submit">Save</button>
        </form>
    </div>
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

@section('script')

<script>
    $("#color-background").change(function() { $("body").css('backgroundColor', $(this).val()); });
    $("#color-text").change(function() { $("body").css('color', $(this).val()); });
    $("#color-link").change(function() { $("body a").css('color', $(this).val()); });
    $("#color-menu").change(function() { $(".navbar.navbar-inverse .navbar-inner.dark-navbar, .navbar.navbar-inverse .navbar-inner.dark-navbar li a").css('backgroundColor', $(this).val()); });
    $("#color-menutext").change(function() { $(".navbar.navbar-inverse .navbar-inner.dark-navbar, .navbar.navbar-inverse .navbar-inner.dark-navbar li a").css('color', $(this).val()); });
    $("#color-post").change(function() { $(".").css('backgroundColor', $(this).val()));
</script>

@stop
