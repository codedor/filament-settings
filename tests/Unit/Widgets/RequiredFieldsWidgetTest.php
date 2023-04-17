<?php

use Codedor\FilamentSettings\Drivers\DriverInterface;
use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Codedor\FilamentSettings\Tests\TestFiles\Settings\TestSettings;
use Codedor\FilamentSettings\Widgets\RequiredFieldsWidget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('Shows all settings that needs check', function () {
    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    $repo->registerTab([
        TestSettings::class,
    ]);

    Livewire::test(RequiredFieldsWidget::class)
        ->assertSeeTextInOrder([
            __('filament-settings::widget.required fields title'),
            'site.name - ' . __('filament-settings::widget.setting needs check'),
        ]);
});

it('Shows all settings that are oke', function () {
    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    $repo->registerTab([
        TestSettings::class,
    ]);

    /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $settingsRepo */
    $settingsRepo = app(DriverInterface::class);
    $settingsRepo->set('site.name', 'filament-settings');

    Livewire::test(RequiredFieldsWidget::class)
        ->assertSeeTextInOrder([
            __('filament-settings::widget.required fields title'),
            'site.name - ' . __('filament-settings::widget.setting ok'),
        ]);
});
