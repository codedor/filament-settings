<?php

use Codedor\FilamentSettings\Models\Setting;

it('fetches value from database', function () {
    $setting = Setting::factory()->create();

    dd($setting);
});
