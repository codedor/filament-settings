<?php

namespace Codedor\FilamentSettings\Providers;

use Codedor\FilamentSettings\Drivers\DatabaseDriver;
use Codedor\FilamentSettings\Drivers\DriverInterface;
use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->singleton(SettingTabRepository::class, function () {
            return new SettingTabRepository();
        });

        app()->bind(DriverInterface::class, DatabaseDriver::class);

        app()->singleton('setting', function () {
            return app(DriverInterface::class);
        });
    }
}
