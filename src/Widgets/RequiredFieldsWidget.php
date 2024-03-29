<?php

namespace Codedor\FilamentSettings\Widgets;

use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Filament\Widgets\Widget;

class RequiredFieldsWidget extends Widget
{
    protected static string $view = 'filament-settings::widgets.required_fields_widget';

    protected int|string|array $columnSpan = 'full';

    protected $listeners = [
        'filament-settings::refresh-widget' => '$refresh',
    ];

    protected function getViewData(): array
    {
        return [
            'requiredKeys' => app(SettingTabRepository::class)->getRequiredKeys(),
        ];
    }
}
