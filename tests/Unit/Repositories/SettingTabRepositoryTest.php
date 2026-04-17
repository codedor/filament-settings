<?php

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs\Tab;
use Wotz\FilamentSettings\Repositories\SettingTabRepository;
use Wotz\FilamentSettings\Rules\SettingMustBeFilledIn;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestInvalidSettings;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestSettings;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestSettingsWithPriority;

it('registers settings tabs', function () {
    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    expect($repo->registerTab([
        TestSettings::class,
    ]))
        ->toBeInstanceOf(SettingTabRepository::class)
        ->getTabs()->toArray()
        ->toMatchArray([
            'Test Settings' => TestSettings::schema(),
        ]);
})->skip('Fails because of closure mismatch');

it('registers single tab', function () {
    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    expect($repo->registerTab(TestSettings::class))
        ->toBeInstanceOf(SettingTabRepository::class)
        ->getTabs()->toArray()
        ->toMatchArray([
            'Test Settings' => TestSettings::schema(),
        ]);
})->skip('Fails because of closure mismatch');

it('does not register invalid test tab', function () {
    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    expect($repo->registerTab(TestInvalidSettings::class))
        ->toBeInstanceOf(SettingTabRepository::class)
        ->getTabs()->toHaveCount(0);
});

it('returns all fields with SettingsMustBeFilledIn rule', function () {
    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    expect($repo->registerTab(TestSettings::class))
        ->getRequiredKeys()
        ->toArray()
        ->toBe([
            'site.name' => [
                'label' => 'Name',
                'tab' => 'site-tab',
            ],
        ]);
})->skip('Fails because of closure mismatch');

it('returns the schema for setting tabs', function () {
    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    $schema = collect($repo->registerTab(TestSettings::class)->toTabsSchema());

    expect($schema)
        ->toArray()
        ->toMatchArray([
            Tab::make('Test Settings')
                ->schema([
                    TextInput::make('site.name')
                        ->default(null)
                        ->rules([
                            new SettingMustBeFilledIn,
                        ]),
                    TextInput::make('site.url')
                        ->default(null),
                ]),
        ]);
})->skip('Fails because of closure mismatch');

it('will sort the tabs ascending based on priority', function () {
    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class)
        ->registerTab(TestSettingsWithPriority::class)
        ->registerTab(TestSettings::class);

    expect($repo->getAllTabs())
        ->sequence(
            fn ($tab, $key) => $tab->toBe(TestSettings::class),
            fn ($tab, $key) => $tab->toBe(TestSettingsWithPriority::class),
        );
});
