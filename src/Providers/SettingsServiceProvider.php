<?php

namespace Codedor\FilamentSettings\Providers;

use Codedor\FilamentSettings\SettingTabRepository;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton(SettingTabRepository::class, function () {
            return new SettingTabRepository;
        });

        dd('sdf');
    }
}
