<?php

namespace Wotz\FilamentSettings\Tests\TestFiles\Settings;

use Filament\Forms\Components\TextInput;
use Wotz\FilamentSettings\Settings\SettingsInterface;

class TestTranslatedSettings implements SettingsInterface
{
    public static function schema(): array
    {
        return [
            TextInput::make('translated.field'),
        ];
    }

    /**
     * Stands in for a `__()` call: the returned title depends on the locale that is
     * active at the moment the method runs.
     */
    public static function title(): string
    {
        return app()->getLocale() === 'nl'
            ? 'Vertaalde instellingen'
            : 'Translated settings';
    }
}
