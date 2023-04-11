<?php

namespace Codedor\FilamentSettings\Tests\TestFiles\Settings;

use Codedor\FilamentSettings\Settings\SettingsInterface;
use Filament\Tables\Columns\TextColumn;

class TestSettings implements SettingsInterface
{
    public static function schema(): array
    {
        return [
            TextColumn::make('site.name'),
        ];
    }
}
