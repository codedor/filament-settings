<?php

namespace Wotz\FilamentSettings\Repositories;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;
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
            return Tab::make($tabName)->schema($this->buildDefaults($schema, $focusKey));
        })->values()->toArray();
    }

    private function buildDefaults(array $schema, string $focusKey): array
    {
        return collect($schema)->map(function ($component) use ($focusKey) {
            if (! $component instanceof Field) {
                // A layout component (Section, Grid, Fieldset, ...): recurse into its children.
                return $this->mapChildComponents(
                    $component,
                    fn (array $children) => $this->buildDefaults($children, $focusKey),
                );
            }

            /** @var DriverInterface $repository */
            $repository = app(DriverInterface::class);
            $fieldName = $component->getName();

            if ($fieldName === $focusKey && method_exists($component, 'extraInputAttributes')) {
                $component = $component->extraInputAttributes([
                    'class' => 'ring-1 ring-inset ring-warning-500 border-warning-500',
                ]);
            }

            // Try to decode the value, if it fails, return the original value
            $value = $repository->get($fieldName);
            $value = json_decode($value, true) ?? $value;

            return $component->default($value);
        })->toArray();
    }

    /**
     * Rebuild a layout component's child components with the given callback.
     *
     * `getChildComponents()` cannot be used here: it resolves through
     * `getChildSchema()`, which needs a Livewire container that does not exist
     * yet while the schema is still being built. `getDefaultChildComponents()`
     * returns the raw components without touching the container.
     */
    private function mapChildComponents(mixed $component, callable $callback): mixed
    {
        if (! $component instanceof Component) {
            return $component;
        }

        try {
            $children = $component->getDefaultChildComponents();
        } catch (Throwable) {
            // Children are built by a closure that needs a container, so they
            // can only be resolved once the form is rendered.
            return $component;
        }

        if (! is_array($children) || $children === []) {
            return $component;
        }

        return $component->childComponents($callback($children));
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
            ->flatMap(fn (array $schema) => $this->flattenFields($schema))
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

    /**
     * Collect every field in a schema, including fields nested inside layout components.
     *
     * @return array<Field>
     */
    private function flattenFields(array $schema): array
    {
        return collect($schema)
            ->flatMap(function ($component) {
                if ($component instanceof Field) {
                    return [$component];
                }

                $children = [];

                $this->mapChildComponents($component, function (array $nested) use (&$children) {
                    $children = $this->flattenFields($nested);

                    return $nested;
                });

                return $children;
            })
            ->all();
    }
}
