<?php

namespace Codedor\FilamentSettings\Tests;

use Codedor\FilamentSettings\Providers\FilamentSettingsServiceProvider;
use Codedor\FilamentSettings\Providers\SettingsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_filament-settings_table.php.stub';
        $migration->up();
        */
    }

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn(string $modelName) => 'Codedor\\FilamentSettings\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentSettingsServiceProvider::class,
            SettingsServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }
}
