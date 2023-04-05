<?php

namespace Codedor\FilamentSettings\Repositories;

use Codedor\FilamentSettings\Rules\SettingMustBeFilledIn;
use Codedor\FilamentSettings\Settings\SettingsInterface;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class SettingTabRepository
{
    public Collection $tabs;

    public function __construct()
    {
        $this->tabs = collect();
        $settingsPath = app_path('Settings');

        foreach ((new Finder)->in($settingsPath)->files() as $tab) {
            $namespace = app()->getNamespace();
            $tabName = Str::ucfirst(Str::headline(
                Str::replaceLast('.php', '', $tab->getRelativePathname())
            ));

            $tab = $namespace . str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($tab->getPathname(), app_path() . DIRECTORY_SEPARATOR));

            if (is_subclass_of($tab, SettingsInterface::class)) {
                $this->tabs->put($tabName, $tab);
            }
        }

        $this->tabs = $this->tabs
            ->sortBy(fn(string $settingsTab) => method_exists($settingsTab, 'priority') ? $settingsTab::priority() : INF)
            ->map(fn(string $settingsTab) => $settingsTab::schema());
    }

    public function toTabsSchema(): array
    {
        return $this->tabs->map(function ($schema, $tabName) {
            $schema = collect($schema)->map(function (Field $field) {
                /** @var \Codedor\FilamentSettings\Repositories\SettingRepositoryInterface $repository */
                $repository = app(DatabaseSettingsRepository::class);

                return $field->default(fn() => $repository->get($field->getName()));
            })->toArray();

            return Tab::make($tabName)->schema($schema);
        })->values()->toArray();
    }

    public function getRequiredKeys()
    {
        return $this->tabs->flatten()
            ->filter(fn(Field $field) => collect($field->getValidationRules())
                ->contains(fn($rule) => $rule instanceof SettingMustBeFilledIn))
            ->map(fn(Field $field) => $field->getName());
    }
}
