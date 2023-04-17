<?php

namespace Codedor\FilamentSettings\Tests\TestFiles\Settings;

use Codedor\FilamentSettings\Rules\SettingMustBeFilledIn;
use Codedor\FilamentSettings\Settings\SettingsInterface;
use Filament\Forms\Components\TextInput;

class TestSettings implements SettingsInterface
{
    public static function schema(): array
    {
        return [
            TextInput::make('site.name')->rules([
                new SettingMustBeFilledIn,
            ]),
            TextInput::make('site.url'),
        ];
    }
}
