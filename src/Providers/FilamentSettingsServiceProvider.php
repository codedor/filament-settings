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
            ->hasMigration('create_package_table');
    }
}
