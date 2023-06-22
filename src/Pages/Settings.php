<?php

namespace Codedor\FilamentSettings\Pages;

use Codedor\FilamentSettings\Drivers\DriverInterface;
use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Codedor\FilamentSettings\Widgets\RequiredFieldsWidget;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Tabs;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static string $view = 'filament-settings::pages.settings';

    public static function getNavigationGroup(): ?string
    {
        return config('filament-settings.navigation.group', parent::getNavigationGroup());
    }

    public static function getNavigationIcon(): string
    {
        return config('filament-settings.navigation.icon', parent::getNavigationIcon());
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function submit()
    {
        /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $repository */
        $interface = app(DriverInterface::class);

        app(SettingTabRepository::class)
            ->getTabs()
            ->flatten(1)
            ->each(function (Field $field) use ($interface) {
                $statePath = $field->getName();

                $interface->set($statePath, data_get($this, $statePath));
            });

        Notification::make()
            ->title('Settings')
            ->body(__('filament-settings::admin.saved'))
            ->success()
            ->send();
    }

    protected function getFormSchema(): array
    {
        /** @var SettingTabRepository $rep */
        $rep = app(SettingTabRepository::class);

        return [
            Tabs::make('Settings')
                ->persistTabInQueryString()
                ->tabs($rep->toTabsSchema()),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RequiredFieldsWidget::class,
        ];
    }
}
