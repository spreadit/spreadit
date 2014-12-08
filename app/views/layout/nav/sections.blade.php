<?php $section_titles = (isset($section)) ? Utility::splitByTitle($section->title) : []; ?>
<div class="navbar-inner dark-navbar" id="sections-navbar">

    <div class="container">
        <ul class="nav">
            <li>
                <a href="/">spreadit.io</a>
            </li>
            @foreach ($sections as $section)
            <li class="{{ in_array($section->title, $section_titles) ? 'active' : '' }}">
                <a href="/s/{{ $section->title }}">{{ $section->title }}</a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
