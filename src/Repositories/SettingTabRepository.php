<?php

namespace Codedor\FilamentSettings\Repositories;

use Codedor\FilamentSettings\Drivers\DriverInterface;
use Codedor\FilamentSettings\Rules\SettingMustBeFilledIn;
use Codedor\FilamentSettings\Settings\SettingsInterface;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SettingTabRepository
{
    protected Collection $tabs;

    public function __construct()
    {
        $this->tabs = collect();
    }

    public function registerTab(string|array $tab): static
    {
        if (! is_array($tab)) {
            $tab = [$tab];
        }

        $this->tabs = collect($tab)
            ->reject(fn($tab) => ! is_subclass_of($tab, SettingsInterface::class))
            ->mapWithKeys(function ($tab) {
                $className = Str::replace(
                    Str::beforeLast($tab, '\\') . '\\',
                    '',
                    $tab
                );

                return [Str::ucfirst(Str::headline($className)) => $tab];
            })
            ->merge($this->tabs)
            ->sortBy(fn(string $settingsTab) => method_exists($settingsTab, 'priority') ? $settingsTab::priority() : INF)
            ->unique(fn($value, $key) => $key);

        return $this;
    }

    public function toTabsSchema(): array
    {
        return $this->getTabs()->map(function ($schema, $tabName) {
            $schema = collect($schema)->map(function (Field $field) {
                /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $repository */
                $repository = app(DriverInterface::class);

                return $field->default(fn() => $repository->get($field->getName()));
            })->toArray();

            return Tab::make($tabName)->schema($schema);
        })->values()->toArray();
    }

    public function getTabs(): Collection
    {
        return $this->tabs->map(fn(string $settingsTab) => $settingsTab::schema());
    }

    public function getRequiredKeys()
    {
        return $this->getTabs()->flatten()
            ->filter(fn(Field $field) => collect($field->getValidationRules())
                ->contains(fn($rule) => $rule instanceof SettingMustBeFilledIn))
            ->map(fn(Field $field) => $field->getName());
    }
}
