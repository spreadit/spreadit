<?php

// validate alphabetical chars & spaces only
Validator::extend('lowercase', function($attr, $value) {
    return preg_match('/^([a-z])+$/', $value);
});

/**
 * Thanks twoSeats
 * https://gist.github.com/twoSeats/69bb4219a1dae611eff8
 */

// validate alphabetical chars & spaces only
Validator::extend('alpha_space', function($attr, $value) {
    return preg_match('/^([a-zA-Z ])+$/i', $value);
});

// validate alphabetical chars, numbers & spaces only
Validator::extend('alpha_num_space', function($attr, $value) {
    return preg_match('/^([a-zA-Z0-9 ])+$/i', $value);
});

// validate alphabetical chars, dashes & spaces only
Validator::extend('alpha_dash_space', function($attr, $value) {
    return preg_match('/^([a-zA-Z -])+$/i', $value);
});

// validate alpha, num, dash, underscore
Validator::extend('andu', function($attr, $value) {
    return preg_match('/^[a-zA-Z0-9_-]+$/', $value);
});

// validate alpha, num, dash, space
Validator::extend('ands', function($attr, $value) {
    return preg_match('/^[a-zA-Z0-9 -]+$/', $value);
});

// validate alpha, num, dash, space, underscore
Validator::extend('andsu', function($attr, $value) {
    return preg_match('/^[a-zA-Z0-9_ -]+$/', $value);
});

// assert a maximum number of fields allowed
// from a multiple select form control
Validator::extend('array_max_count', function($attr, $value, $params) {
    return count($value) <= $params[0];
});
