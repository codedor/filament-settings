<?php

namespace Codedor\FilamentSettings\Providers;

use Codedor\FilamentSettings\Repositories\DatabaseSettingsRepository;
use Codedor\FilamentSettings\Repositories\SettingRepositoryInterface;
use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton(SettingTabRepository::class, function () {
            return new SettingTabRepository();
        });

        app()->bind(SettingRepositoryInterface::class, DatabaseSettingsRepository::class);

        app()->bind('settings', function () {
            return app(SettingRepositoryInterface::class);
        });
    }
}
