<?php

namespace Codedor\FilamentSettings\Providers;

use Codedor\FilamentSettings\Drivers\DriverInterface;
use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-settings')
            ->setBasePath(__DIR__ . '/../')
            ->hasConfigFile()
            ->hasMigration('2021_04_06_000000_create_settings_table')
            ->hasViews('filament-settings')
            ->hasTranslations()
            ->runsMigrations();
    }

    public function bootingPackage()
    {
        parent::bootingPackage();

        $this->registerTabs();
    }

    protected function registerTabs(): void
    {
        /** @var \Codedor\FilamentSettings\Repositories\SettingTabRepository $settingsTabRepository */
        $settingsTabRepository = app(SettingTabRepository::class);
        $settingsTabRepository->registerTab(config('filament-settings.tabs', []));
    }

    public function packageRegistered()
    {
        parent::packageRegistered();

        app()->bind(DriverInterface::class, config('filament-settings.driver'));

        app()->singleton(SettingTabRepository::class, function () {
            return new SettingTabRepository();
        });

        app()->singleton('setting', function () {
            return app(DriverInterface::class);
        });
    }
}
