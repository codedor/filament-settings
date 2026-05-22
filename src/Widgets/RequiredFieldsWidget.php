<?php

namespace Wotz\FilamentSettings\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Wotz\FilamentSettings\Repositories\SettingTabRepository;

class RequiredFieldsWidget extends Widget
{
    protected string $view = 'filament-settings::widgets.required_fields_widget';

    protected int|string|array $columnSpan = 'full';

    protected $listeners = [
        'filament-settings::refresh-widget' => '$refresh',
    ];

    protected function getViewData(): array
    {
        return [
            'requiredKeys' => static::getMissingKeys(),
        ];
    }

    protected static function getMissingKeys(): Collection
    {
        return app(SettingTabRepository::class)
            ->getRequiredKeys()
            ->filter(fn (array $data, string $key): bool => blank(setting($key)));
    }
}
