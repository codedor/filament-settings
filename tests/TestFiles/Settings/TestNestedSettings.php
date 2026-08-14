<?php

namespace Codedor\FilamentSettings\Tests\TestFiles\Settings;

use Codedor\FilamentSettings\Rules\SettingMustBeFilledIn;
use Codedor\FilamentSettings\Settings\SettingsInterface;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class TestNestedSettings implements SettingsInterface
{
    public static function schema(): array
    {
        return [
            Section::make('Contact')
                ->schema([
                    TextInput::make('nested.email')
                        ->email()
                        ->required()
                        ->rules([
                            new SettingMustBeFilledIn,
                        ]),
                ]),
            TextInput::make('nested.plain'),
        ];
    }
}
