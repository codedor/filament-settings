<?php

namespace Codedor\FilamentSettings\Tests\TestFiles\Settings;

use Codedor\FilamentSettings\Rules\SettingMustBeFilledIn;
use Codedor\FilamentSettings\Settings\SettingsInterface;
use Filament\Forms\Components\TextInput;

class TestSettingsWithPriority implements SettingsInterface
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

    public static function priority(): int
    {
        return 40;
    }
}
