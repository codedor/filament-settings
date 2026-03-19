<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Wotz\FilamentSettings\Drivers\DriverInterface;
use Wotz\FilamentSettings\Repositories\SettingTabRepository;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestSettings;
use Wotz\FilamentSettings\Widgets\RequiredFieldsWidget;

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
            'Name - ' . __('filament-settings::widget.setting needs check'),
        ]);
});

it('Shows all settings that are oke', function () {
    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    $repo->registerTab([
        TestSettings::class,
    ]);

    /** @var DriverInterface $settingsRepo */
    $settingsRepo = app(DriverInterface::class);
    $settingsRepo->set('site.name', 'filament-settings');

    Livewire::test(RequiredFieldsWidget::class)
        ->assertSeeTextInOrder([
            __('filament-settings::widget.required fields title'),
            'Name - ' . __('filament-settings::widget.setting ok'),
        ]);
});
