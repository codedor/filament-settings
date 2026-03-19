<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Wotz\FilamentSettings\Drivers\DriverInterface;
use Wotz\FilamentSettings\Pages\Settings;
use Wotz\FilamentSettings\Repositories\SettingTabRepository;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestSettings;

uses(RefreshDatabase::class);

it('mounts with right values', function () {
    $fakeValue = fake()->word;

    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    $repo->registerTab([
        TestSettings::class,
    ]);

    /** @var DriverInterface $settingsRepo */
    $settingsRepo = app(DriverInterface::class);
    $settingsRepo->set('site.name', $fakeValue);

    $filamentState = Livewire::withQueryParams([
        'tab' => '-test-settings-tab',
    ])->test(Settings::class)
        ->assertOk()
        ->assertSee('site.name')
        ->get('form')->getState();

    expect($filamentState)->toBe([
        'site' => [
            'name' => $fakeValue,
            'url' => null,
        ],
    ]);
});

it('saves new setting', function () {
    $fakeOldValue = fake()->word;

    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    $repo->registerTab([
        TestSettings::class,
    ]);

    /** @var DriverInterface $settingsRepo */
    $settingsRepo = app(DriverInterface::class);
    $settingsRepo->set('site.name', $fakeOldValue);

    Livewire::test(Settings::class)
        ->fillForm([
            'site.name' => 'new-name',
            'site.url' => 'new-url',
        ])
        ->call('submit');

    expect(setting('site.name'))->toBe('new-name')
        ->and(setting('site.url'))->toBe('new-url');
});
