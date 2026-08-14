<?php

namespace Wotz\FilamentSettings\Tests\TestFiles\Settings;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Wotz\FilamentSettings\Rules\SettingMustBeFilledIn;
use Wotz\FilamentSettings\Settings\SettingsInterface;

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
