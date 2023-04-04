<?php

namespace Codedor\FilamentSettings\Pages;

use Codedor\FilamentSettings\Models\Setting;
use Codedor\FilamentSettings\Settings\SettingsInterface;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use function _PHPStan_cbfb23d84\React\Promise\map;
use function Pest\Laravel\instance;

class Settings extends Page
{
    protected static string $view = 'filament-settings::pages.settings';

    protected Collection $tabs;

    public function mount()
    {
//        dd(app('settings'));
        $this->hydrate();

        $this->form->fill();
    }

    public function hydrate()
    {
        $tabs = collect();
        $settingsPath = app_path('Settings');

        foreach ((new Finder)->in($settingsPath)->files() as $tab) {
            $namespace = app()->getNamespace();
            $tabName = Str::ucfirst(Str::headline(
                Str::replaceLast('.php', '', $tab->getRelativePathname())
            ));

            $tab = $namespace . str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($tab->getPathname(), app_path() . DIRECTORY_SEPARATOR)
                );

            if (is_subclass_of($tab, SettingsInterface::class)) {
                $tabs->put($tabName, $tab);
            }
        }

        $this->tabs = $tabs
            ->sortBy(fn (string $settingsTab) => method_exists($settingsTab, 'priority') ? $settingsTab::priority() : INF)
            ->map(fn (string $settingsTab) => $settingsTab::schema());
    }

    public function submit()
    {
        $state = $this->form->getState();
        dd($state);
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Settings')
                ->persistTabInQueryString()
                ->tabs($this->tabs->map(function ($schema, $tabName) {
                    return Tabs\Tab::make($tabName)
                        ->schema($schema);
                })->values()->toArray())
        ];
    }
}
