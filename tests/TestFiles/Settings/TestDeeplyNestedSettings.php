<?php

namespace Wotz\FilamentSettings\Tests\TestFiles\Settings;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Wotz\FilamentSettings\Rules\SettingMustBeFilledIn;
use Wotz\FilamentSettings\Settings\SettingsInterface;

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

            // Children resolved by a closure, which needs a container.
            Section::make('Lazy')->schema(fn () => [
                TextInput::make('deep.lazy'),
            ]),
        ];
    }
}
