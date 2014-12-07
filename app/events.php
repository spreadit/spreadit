<?php

Event::listen('auth.token.valid', function($user)
{
    Auth::setUser($user);
});