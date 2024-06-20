<?php

use Codedor\FilamentSettings\Facades\Setting;

if (! function_exists('setting')) {
    function setting($key, $default = null, $useCache = true)
    {
        return Setting::get($key, $default, $useCache);
    }
}
