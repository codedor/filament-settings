<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Wotz\FilamentSettings\Drivers\DriverInterface;
use Wotz\FilamentSettings\Pages\Settings;
use Wotz\FilamentSettings\Repositories\SettingTabRepository;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestDeeplyNestedSettings;

uses(RefreshDatabase::class);

beforeEach(function () {
    app(SettingTabRepository::class)->registerTab([TestDeeplyNestedSettings::class]);
});

it('renders components nested several levels deep', function () {
    Livewire::test(Settings::class)
        ->assertOk()
        ->assertSee('deep.email')
        ->assertSee('deep.number')
        ->assertSee('deep.lazy');
});

it('validates fields nested several levels deep', function () {
    Livewire::test(Settings::class)
        ->fillForm([
            'deep.email' => 'not-an-email',
            'deep.number' => 'not-a-number',
        ])
        ->call('submit')
        ->assertHasFormErrors(['deep.email', 'deep.number']);
});

it('saves fields nested several levels deep', function () {
    Livewire::test(Settings::class)
        ->fillForm([
            'deep.email' => 'a@b.com',
            'deep.number' => '42',
            'deep.lazy' => 'lazy-value',
        ])
        ->call('submit')
        ->assertHasNoFormErrors();

    expect(setting('deep.email'))->toBe('a@b.com')
        ->and(setting('deep.number'))->toBe('42')
        ->and(setting('deep.lazy'))->toBe('lazy-value');
});

it('applies stored defaults to deeply nested fields', function () {
    app(DriverInterface::class)->set('deep.email', 'stored@b.com');
    app(DriverInterface::class)->set('deep.number', '7');

    $state = Livewire::test(Settings::class)->assertOk()->get('form')->getState();

    expect($state['deep']['email'])->toBe('stored@b.com')
        ->and($state['deep']['number'])->toEqual('7');
});

it('finds required keys nested several levels deep', function () {
    expect(app(SettingTabRepository::class)->getRequiredKeys()->toArray())
        ->toHaveKey('deep.email');
});
