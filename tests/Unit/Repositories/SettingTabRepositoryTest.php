<?php

use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Codedor\FilamentSettings\Rules\SettingMustBeFilledIn;
use Codedor\FilamentSettings\Tests\TestFiles\Settings\TestInvalidSettings;
use Codedor\FilamentSettings\Tests\TestFiles\Settings\TestSettings;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;

it('registers settings tabs', function () {
    /** @var \Codedor\FilamentSettings\Repositories\SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    expect($repo->registerTab([
        TestSettings::class,
    ]))
        ->toBeInstanceOf(SettingTabRepository::class)
        ->getTabs()->toArray()
        ->toMatchArray([
            'Test Settings' => TestSettings::schema(),
        ]);
});

it('registers single tab', function () {
    /** @var \Codedor\FilamentSettings\Repositories\SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    expect($repo->registerTab(TestSettings::class))
        ->toBeInstanceOf(SettingTabRepository::class)
        ->getTabs()->toArray()
        ->toMatchArray([
            'Test Settings' => TestSettings::schema(),
        ]);
});

it('does not register invalid test tab', function () {
    /** @var \Codedor\FilamentSettings\Repositories\SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    expect($repo->registerTab(TestInvalidSettings::class))
        ->toBeInstanceOf(SettingTabRepository::class)
        ->getTabs()->toHaveCount(0);
});

it('returns all fields with SettingsMustBeFilledIn rule', function () {
    /** @var \Codedor\FilamentSettings\Repositories\SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    expect($repo->registerTab(TestSettings::class))
        ->getRequiredKeys()
        ->toArray()
        ->toBe([
            'site.name',
        ]);
});

it('returns the schema for setting tabs', function () {
    /** @var \Codedor\FilamentSettings\Repositories\SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    $schema = collect($repo->registerTab(TestSettings::class)->toTabsSchema());

    expect($schema)
        ->toArray()
        ->toMatchArray([
            Tab::make('Test Settings')
                ->schema([
                    TextInput::make('site.name')
                        ->default(fn () => null)
                        ->rules([
                            new SettingMustBeFilledIn,
                        ]),
                    TextInput::make('site.url')
                        ->default(fn () => null),
                ]),
        ]);
});
