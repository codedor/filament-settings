<?php

namespace Codedor\FilamentSettings\Providers;

use Codedor\FilamentSettings\Drivers\DriverInterface;
use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerTabs();
    }

    protected function registerTabs(): void
    {
        /** @var \Codedor\FilamentSettings\Repositories\SettingTabRepository $settingsTabRepository */
        $settingsTabRepository = app(SettingTabRepository::class);
        $settingsTabRepository->registerTab(config('filament-settings.tabs', []));
    }

    public function register()
    {
        app()->bind(DriverInterface::class, config('filament-settings.driver'));

        app()->singleton(SettingTabRepository::class, function () {
            return new SettingTabRepository();
        });

        app()->singleton('setting', function () {
            return app(DriverInterface::class);
        });
    }
}
