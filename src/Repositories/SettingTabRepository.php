<?php

namespace Wotz\FilamentSettings\Repositories;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Wotz\FilamentSettings\Drivers\DriverInterface;
use Wotz\FilamentSettings\Rules\SettingMustBeFilledIn;
use Wotz\FilamentSettings\Settings\SettingsInterface;

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
            ->reject(fn ($tab) => ! is_subclass_of($tab, SettingsInterface::class))
            ->mapWithKeys(function ($tab) {
                if (method_exists($tab, 'title')) {
                    $tabTitle = $tab::title();
                } else {
                    $tabTitle = Str::of($tab)
                        ->afterLast('\\')
                        ->replaceMatches('/([A-Z])/', ' $1')
                        ->headline()
                        ->ucfirst();
                }

                return [(string) $tabTitle => $tab];
            })
            ->merge($this->tabs)
            ->sortBy(fn (string $settingsTab) => method_exists($settingsTab, 'priority') ? $settingsTab::priority() : 0)
            ->unique(fn ($value, $key) => $key);

        return $this;
    }

    public function toTabsSchema(string $focusKey = ''): array
    {
        return $this->getTabs()->map(function ($schema, $tabName) use ($focusKey) {
            $schema = collect($schema)->map(function (Field $field) use ($focusKey) {
                /** @var DriverInterface $repository */
                $repository = app(DriverInterface::class);
                $fieldName = $field->getName();

                if ($fieldName === $focusKey && method_exists($field, 'extraInputAttributes')) {
                    $field = $field->extraInputAttributes([
                        'class' => 'ring-1 ring-inset ring-warning-500 border-warning-500',
                    ]);
                }

                // Try to decode the value, if it fails, return the original value
                $value = $repository->get($fieldName);
                $value = json_decode($value, true) ?? $value;

                return $field->default($value);
            })->toArray();

            return Tab::make($tabName)->schema($schema);
        })->values()->toArray();
    }

    public function removeTab(string $class): Collection
    {
        return $this->tabs = $this->tabs
            ->reject(fn (string $settingsTab) => $settingsTab === $class);
    }

    public function getTabs(): Collection
    {
        return $this->tabs->map(fn (string $settingsTab) => $settingsTab::schema());
    }

    public function getAllTabs(): Collection
    {
        return $this->tabs;
    }

    public function getRequiredKeys()
    {
        return $this->getTabs()
            ->flatten()
            ->filter(fn ($field) => $field instanceof Field)
            ->filter(function (Field $field) {
                try {
                    return collect($field->getValidationRules())
                        ->contains(fn ($rule) => $rule instanceof SettingMustBeFilledIn);
                } catch (\Throwable) {
                    return false;
                }
            })
            ->mapWithKeys(function (Field $field) {
                try {
                    return [
                        $field->getName() => [
                            'label' => $field->getLabel(),
                            'tab' => Str::of($field->getName())->before('.')->slug() . '-tab',
                        ],
                    ];
                } catch (\Throwable) {
                    return [];
                }
            });
    }
}
