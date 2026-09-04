<?php

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs\Tab;
use Wotz\FilamentSettings\Repositories\SettingTabRepository;
use Wotz\FilamentSettings\Rules\SettingMustBeFilledIn;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestInvalidSettings;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestSettings;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestSettingsWithPriority;
use Wotz\FilamentSettings\Tests\TestFiles\Settings\TestTranslatedSettings;

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

it('resolves tab titles when the schema is built, not when the tab is registered', function () {
    // Tabs are registered from service providers, which run before any locale middleware.
    app()->setLocale('nl');

    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class)->registerTab(TestTranslatedSettings::class);

    // By the time the page renders, the request locale is in effect.
    app()->setLocale('en');

    expect($repo->toTabsSchema())
        ->toHaveCount(1)
        ->and($repo->toTabsSchema()[0]->getLabel())
        ->toBe('Translated settings');
});

it('falls back to a headline of the class name when a tab has no title', function () {
    /** @var SettingTabRepository $repo */
    $repo = app(SettingTabRepository::class);

    expect($repo->titleFor(TestSettings::class))->toBe('Test Settings');
});
