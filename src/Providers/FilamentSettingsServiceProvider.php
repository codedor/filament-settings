<?php

namespace Codedor\FilamentSettings\Providers;

use Codedor\FilamentSettings\Pages\Settings;
use Codedor\FilamentSettings\Repositories\DatabaseSettingsRepository;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelPackageTools\Package;

class FilamentSettingsServiceProvider extends PluginServiceProvider
{
    protected array $pages = [
        Settings::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-settings')
            ->setBasePath(__DIR__ . '/../')
            ->hasConfigFile()
            ->hasMigration('2021_04_06_000000_create_settings_table')
            ->hasViews('filament-settings')
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        Validator::extend('mustBeFilledIn', fn () => true);
    }

    public function registeringPackage()
    {
//        app()->instance('settings', function () {
//            return $this->app->make(DatabaseSettingsRepository::class);
//        });
    }
}
