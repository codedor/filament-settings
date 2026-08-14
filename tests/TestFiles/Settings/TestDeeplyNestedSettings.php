<?php

namespace Codedor\FilamentSettings\Tests\TestFiles\Settings;

use Codedor\FilamentSettings\Rules\SettingMustBeFilledIn;
use Codedor\FilamentSettings\Settings\SettingsInterface;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;

class TestDeeplyNestedSettings implements SettingsInterface
{
    public static function schema(): array
    {
        return [
            Section::make('Outer')->schema([
                Grid::make()->schema([
                    Fieldset::make('Inner')->schema([
                        TextInput::make('deep.email')
                            ->email()
                            ->rules([new SettingMustBeFilledIn]),
                    ]),
                ]),
            ]),

            Tabs::make('Nested tabs')->tabs([
                Tab::make('One')->schema([
                    TextInput::make('deep.number')->numeric(),
                ]),
            ]),

            // Children resolved by a closure.
            Section::make('Lazy')->schema(fn () => [
                TextInput::make('deep.lazy'),
            ]),
        ];
    }
}
