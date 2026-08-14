<?php

use Codedor\FilamentSettings\Drivers\DriverInterface;
use Codedor\FilamentSettings\Pages\Settings;
use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Codedor\FilamentSettings\Tests\TestFiles\Settings\TestNestedSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders nested components', function () {
    app(SettingTabRepository::class)->registerTab([TestNestedSettings::class]);

    Livewire::test(Settings::class)
        ->assertOk()
        ->assertSee('nested.email');
});

it('validates fields nested inside a layout component', function () {
    app(SettingTabRepository::class)->registerTab([TestNestedSettings::class]);

    Livewire::test(Settings::class)
        ->fillForm([
            'nested.email' => 'not-an-email',
            'nested.plain' => 'ok',
        ])
        ->call('submit')
        ->assertHasFormErrors(['nested.email']);

    expect(setting('nested.plain'))->toBeNull();
});

it('lists nested required keys', function () {
    $keys = app(SettingTabRepository::class)
        ->registerTab([TestNestedSettings::class])
        ->getRequiredKeys();

    expect($keys->toArray())->toHaveKey('nested.email');
});

it('applies stored defaults to nested fields', function () {
    app(DriverInterface::class)->set('nested.email', 'a@b.com');

    app(SettingTabRepository::class)->registerTab([TestNestedSettings::class]);

    $state = Livewire::test(Settings::class)->assertOk()->get('form')->getState();

    expect($state)->toBe([
        'nested' => [
            'email' => 'a@b.com',
            'plain' => null,
        ],
    ]);
});
