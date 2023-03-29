<?php

namespace Codedor\FilamentSettings\Providers;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentSettingsServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-settings')
            ->setBasePath(__DIR__ . '/../')
            ->hasConfigFile()
            ->hasMigration('2021_04_06_000000_create_settings_table');
    }
}
