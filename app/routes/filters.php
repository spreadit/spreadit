<?php

/***********************************************
 *
 * ROUTE PATTERN FILTERS
 *
 **********************************************/
Route::pattern('post_id', '[0-9]+');
Route::pattern('post_title', '[a-zA-Z0-9_-]+');
Route::pattern('section_title', '[a-zA-Z0-9_-]+');

