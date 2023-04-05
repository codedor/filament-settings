<?php

namespace Codedor\FilamentSettings\Providers;

use Codedor\FilamentSettings\Repositories\DatabaseSettingsRepository;
use Codedor\FilamentSettings\SettingTabRepository;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton(SettingTabRepository::class, function () {
            return new SettingTabRepository;
        });

        app()->bind('settings', function () {
            return new DatabaseSettingsRepository();
        });
    }
}
