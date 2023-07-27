<?php

namespace Codedor\FilamentSettings\Filament;

use Codedor\FilamentSettings\Pages\Settings;
use Codedor\FilamentSettings\Widgets\RequiredFieldsWidget;
use Filament\Contracts\Plugin;
use Filament\Panel;

class SettingsPlugin implements Plugin
{
    protected bool $hasSettingsPage = true;

    protected bool $hasRequiredFieldsWidget = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-menu';
    }

    public function register(Panel $panel): void
    {
        if ($this->hasSettingsPage()) {
            $panel->pages([
                Settings::class,
            ]);
        }

        if ($this->hasRequiredFieldsWidget()) {
            $panel->widgets([
                RequiredFieldsWidget::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
    }

    public function settingsPage(bool $condition = true): static
    {
        $this->hasSettingsPage = $condition;

        return $this;
    }

    public function hasSettingsPage(): bool
    {
        return $this->hasSettingsPage;
    }

    public function requiredFieldsWidget(bool $condition = true): static
    {
        $this->hasRequiredFieldsWidget = $condition;

        return $this;
    }

    public function hasRequiredFieldsWidget(): bool
    {
        return $this->hasRequiredFieldsWidget;
    }
}
